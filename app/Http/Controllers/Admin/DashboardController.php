<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statistics = [
            'customers' => User::where('role', 'customer')->count(),
            'categories' => Category::count(),
            'products' => Product::count(),
            'orders' => Order::count(),

            'pending_orders' => Order::where(
                'status',
                Order::STATUS_PENDING
            )->count(),

            'low_stock_products' => Product::where('status', true)
                ->where('stock', '<=', 5)
                ->count(),

            'revenue' => Order::where(
                'status',
                Order::STATUS_COMPLETED
            )->sum('total'),
        ];

        $recentOrders = Order::with('user')
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::with('category')
            ->where('status', true)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'statistics',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}