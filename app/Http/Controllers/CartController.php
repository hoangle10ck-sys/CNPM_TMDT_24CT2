<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $cart->load([
            'items.product.category',
        ]);

        return view('cart.index', compact('cart'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ], [
            'product_id.required' => 'Không tìm thấy sản phẩm.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng tối thiểu là 1.',
        ]);

        $product = Product::query()
            ->where('status', true)
            ->find($validated['product_id']);

        if (!$product) {
            return back()->withErrors([
                'quantity' => 'Sản phẩm không tồn tại hoặc đã ngừng bán.',
            ]);
        }

        if ($product->stock <= 0) {
            return back()->withErrors([
                'quantity' => 'Sản phẩm hiện đã hết hàng.',
            ]);
        }

        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        /** @var CartItem|null $cartItem */
        $cartItem = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        $newQuantity = (int) $validated['quantity']
            + ($cartItem?->quantity ?? 0);

        if ($newQuantity > $product->stock) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => "Chỉ còn {$product->stock} sản phẩm trong kho.",
                ]);
        }

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => (int) $validated['quantity'],
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(
        Request $request,
        CartItem $cartItem
    ): RedirectResponse {
        $cartItem->loadMissing(['cart', 'product']);

        abort_unless(
            $cartItem->cart
                && $cartItem->cart->user_id === $request->user()->id,
            403
        );

        if (!$cartItem->product || !$cartItem->product->status) {
            return back()->withErrors([
                'quantity' => 'Sản phẩm không còn được bán.',
            ]);
        }

        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:'.$cartItem->product->stock,
            ],
        ], [
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng tối thiểu là 1.',
            'quantity.max' => "Chỉ còn {$cartItem->product->stock} sản phẩm trong kho.",
        ]);

        $cartItem->update([
            'quantity' => (int) $validated['quantity'],
        ]);

        return back()->with(
            'success',
            'Đã cập nhật số lượng sản phẩm.'
        );
    }

    public function destroy(
        Request $request,
        CartItem $cartItem
    ): RedirectResponse {
        $cartItem->loadMissing('cart');

        abort_unless(
            $cartItem->cart
                && $cartItem->cart->user_id === $request->user()->id,
            403
        );

        $cartItem->delete();

        return back()->with(
            'success',
            'Đã xóa sản phẩm khỏi giỏ hàng.'
        );
    }

    public function clear(Request $request): RedirectResponse
    {
        $cart = Cart::query()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($cart) {
            CartItem::query()
                ->where('cart_id', $cart->id)
                ->delete();
        }

        return back()->with(
            'success',
            'Đã xóa toàn bộ giỏ hàng.'
        );
    }
}