<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $cart = Cart::with('items.product.category')
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Giỏ hàng đang trống.');
        }

        $subtotal = $cart->total;
        $shippingFee = 0;
        $total = $subtotal + $shippingFee;

        return view('checkout.index', compact(
            'cart',
            'subtotal',
            'shippingFee',
            'total'
        ));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($request, $validated) {
            $cart = Cart::where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->first();

            if (!$cart) {
                throw ValidationException::withMessages([
                    'cart' => 'Không tìm thấy giỏ hàng.',
                ]);
            }

            $cart->load('items');

            if ($cart->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Giỏ hàng đang trống.',
                ]);
            }

            $orderItems = [];
            $subtotal = 0;

            foreach ($cart->items as $cartItem) {
                $product = Product::whereKey($cartItem->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product || !$product->status) {
                    throw ValidationException::withMessages([
                        'cart' => 'Có sản phẩm không còn được bán.',
                    ]);
                }

                if ($cartItem->quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => "Sản phẩm {$product->name} chỉ còn "
                            .$product->stock.' sản phẩm.',
                    ]);
                }

                $price = (float) (
                    $product->sale_price ?? $product->price
                );

                $itemSubtotal = $price * $cartItem->quantity;
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'price' => $price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $shippingFee = 0;
            $total = $subtotal + $shippingFee;

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_code' => $this->createOrderCode(),
                'customer_name' => $validated['customer_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'shipping_address' => $validated['shipping_address'],
                'note' => $validated['note'] ?? null,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'unpaid',
                'status' => Order::STATUS_PENDING,
            ]);

            foreach ($orderItems as $orderItem) {
                $order->items()->create($orderItem);

                Product::whereKey($orderItem['product_id'])
                    ->decrement('stock', $orderItem['quantity']);
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect()
            ->route('checkout.success', $order)
            ->with('success', 'Đặt hàng thành công.');
    }

    public function success(Request $request, Order $order): View
    {
        abort_unless(
            $order->user_id === $request->user()->id
                || $request->user()->isAdmin(),
            403
        );

        $order->load('items');

        return view('checkout.success', compact('order'));
    }

    private function createOrderCode(): string
    {
        do {
            $code = 'DH-'
                .now()->format('Ymd-His')
                .'-'
                .Str::upper(Str::random(4));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }
}