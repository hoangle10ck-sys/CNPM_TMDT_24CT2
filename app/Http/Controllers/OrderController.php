<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless(
            $order->user_id === $request->user()->id,
            403
        );

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    public function cancel(
        Request $request,
        Order $order
    ): RedirectResponse {
        abort_unless(
            $order->user_id === $request->user()->id,
            403
        );

        DB::transaction(function () use ($order) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status !== Order::STATUS_PENDING) {
                return;
            }

            $lockedOrder->load('items');

            foreach ($lockedOrder->items as $item) {
                if ($item->product_id) {
                    Product::whereKey($item->product_id)
                        ->increment('stock', $item->quantity);
                }
            }

            $lockedOrder->update([
                'status' => Order::STATUS_CANCELLED,
            ]);
        });

        $order->refresh();

        if ($order->status !== Order::STATUS_CANCELLED) {
            return back()->with(
                'error',
                'Chỉ có thể hủy đơn hàng đang chờ xác nhận.'
            );
        }

        return back()->with(
            'success',
            'Đã hủy đơn hàng thành công.'
        );
    }
}