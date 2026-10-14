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
    private const MAX_AMOUNT_IN_CENTS = 999999999999999;

    public function index(Request $request): View|RedirectResponse
    {
        /** @var Cart|null $cart */
        $cart = Cart::with('items.product.category')
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
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
        $userId = (int) $request->user()->getAuthIdentifier();

        $order = DB::transaction(function () use ($userId, $validated) {
            $cart = Cart::where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            if (! $cart) {
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
            $subtotalInCents = 0;

            foreach ($cart->items as $cartItem) {
                /** @var Product|null $product */
                $product = Product::with('category')
                    ->whereKey($cartItem->product_id)
                    ->lockForUpdate()
                    ->first();

                $category = $product?->category;

                if (
                    ! $product
                    || ! (bool) $product->getAttribute('status')
                    || ! $category
                    || ! (bool) $category->getAttribute('status')
                ) {
                    throw ValidationException::withMessages([
                        'cart' => 'Có sản phẩm không còn được bán.',
                    ]);
                }

                $productName = (string) $product->getAttribute('name');
                $productStock = (int) $product->getAttribute('stock');

                if ($cartItem->quantity > $productStock) {
                    throw ValidationException::withMessages([
                        'cart' => "Sản phẩm {$productName} chỉ còn "
                            .$productStock.' sản phẩm.',
                    ]);
                }

                $productPrice = $product->getAttribute('sale_price')
                    ?? $product->getAttribute('price');
                $priceInCents = $this->amountToCents((string) $productPrice);

                if (
                    $priceInCents < 0
                    || $priceInCents > intdiv(
                        self::MAX_AMOUNT_IN_CENTS,
                        $cartItem->quantity
                    )
                ) {
                    throw ValidationException::withMessages([
                        'cart' => 'Tổng tiền đơn hàng vượt giới hạn cho phép.',
                    ]);
                }

                $itemSubtotalInCents = $priceInCents * $cartItem->quantity;

                if (
                    $subtotalInCents
                    > self::MAX_AMOUNT_IN_CENTS - $itemSubtotalInCents
                ) {
                    throw ValidationException::withMessages([
                        'cart' => 'Tổng tiền đơn hàng vượt giới hạn cho phép.',
                    ]);
                }

                $subtotalInCents += $itemSubtotalInCents;
                $productSku = $product->getAttribute('sku');

                $orderItems[] = [
                    'product_id' => (int) $product->getKey(),
                    'product_name' => $productName,
                    'product_sku' => \is_string($productSku)
                        ? $productSku
                        : null,
                    'price' => $this->centsToAmount($priceInCents),
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $this->centsToAmount($itemSubtotalInCents),
                ];
            }

            $shippingFee = 0;
            $subtotal = $this->centsToAmount($subtotalInCents);
            $total = $this->centsToAmount($subtotalInCents + $shippingFee);

            $order = Order::create([
                'user_id' => $userId,
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
            $order->user_id === (int) $request->user()->getAuthIdentifier()
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

    private function amountToCents(string $amount): int
    {
        [$whole, $fraction] = array_pad(explode('.', $amount, 2), 2, '0');

        return (int) $whole * 100
            + (int) str_pad(substr($fraction, 0, 2), 2, '0');
    }

    private function centsToAmount(int $amount): string
    {
        return intdiv($amount, 100)
            .'.'
            .str_pad((string) ($amount % 100), 2, '0', STR_PAD_LEFT);
    }
}
