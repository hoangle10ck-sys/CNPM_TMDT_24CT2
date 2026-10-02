<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStatusRequest;
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
        $orders = Order::with('user')
            ->withCount('items')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->string('keyword')
                    ->trim()
                    ->toString();

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where('order_code', 'like', "%{$keyword}%")
                        ->orWhere('customer_name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product']);

        $allowedStatuses = $this->allowedStatuses($order->status);

        return view(
            'admin.orders.show',
            compact('order', 'allowedStatuses')
        );
    }

    public function update(
        UpdateOrderStatusRequest $request,
        Order $order
    ): RedirectResponse {
        $newStatus = $request->validated('status');

        $updated = DB::transaction(function () use ($order, $newStatus) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            $allowedStatuses = $this->allowedStatuses(
                $lockedOrder->status
            );

            if (!in_array($newStatus, $allowedStatuses, true)) {
                return false;
            }

            $lockedOrder->load('items');

            if ($newStatus === Order::STATUS_CANCELLED) {
                foreach ($lockedOrder->items as $item) {
                    if ($item->product_id) {
                        Product::whereKey($item->product_id)
                            ->increment('stock', $item->quantity);
                    }
                }
            }

            $data = [
                'status' => $newStatus,
            ];

            if ($newStatus === Order::STATUS_COMPLETED) {
                $data['completed_at'] = now();
                $data['payment_status'] = 'paid';
            }

            $lockedOrder->update($data);

            return true;
        });

        if (!$updated) {
            return back()->with(
                'error',
                'Không thể chuyển sang trạng thái đã chọn.'
            );
        }

        return back()->with(
            'success',
            'Cập nhật trạng thái đơn hàng thành công.'
        );
    }

    private function allowedStatuses(string $currentStatus): array
    {
        return match ($currentStatus) {
            Order::STATUS_PENDING => [
                Order::STATUS_CONFIRMED,
                Order::STATUS_CANCELLED,
            ],

            Order::STATUS_CONFIRMED => [
                Order::STATUS_SHIPPING,
                Order::STATUS_CANCELLED,
            ],

            Order::STATUS_SHIPPING => [
                Order::STATUS_COMPLETED,
            ],

            default => [],
        };
    }
}