<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->string('keyword')
                    ->trim()
                    ->toString();

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.customers.index',
            compact('customers')
        );
    }

    public function show(User $user): View
    {
        abort_unless($user->role === 'customer', 404);

        $orders = $user->orders()
            ->latest()
            ->paginate(10);

        $statistics = [
            'total_orders' => $user->orders()->count(),

            'completed_orders' => $user->orders()
                ->where('status', Order::STATUS_COMPLETED)
                ->count(),

            'total_spent' => $user->orders()
                ->where('status', Order::STATUS_COMPLETED)
                ->sum('total'),
        ];

        return view(
            'admin.customers.show',
            compact('user', 'orders', 'statistics')
        );
    }
}