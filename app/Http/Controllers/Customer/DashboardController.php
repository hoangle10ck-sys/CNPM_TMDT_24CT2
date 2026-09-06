<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $statistics = [
            'total_orders' => $user->orders()->count(),

            'pending_orders' => $user->orders()
                ->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_CONFIRMED,
                    Order::STATUS_SHIPPING,
                ])
                ->count(),

            'completed_orders' => $user->orders()
                ->where('status', Order::STATUS_COMPLETED)
                ->count(),

            'total_spent' => $user->orders()
                ->where('status', Order::STATUS_COMPLETED)
                ->sum('total'),

            'cart_items' => $user->cart
                ? $user->cart->items()->sum('quantity')
                : 0,
        ];

        $recentOrders = $user->orders()
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'statistics',
            'recentOrders'
        ));
    }
}