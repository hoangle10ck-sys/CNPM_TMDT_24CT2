<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::where('status', true)
            ->withCount([
                'products' => fn ($query) => $query->where('status', true),
            ])
            ->orderBy('name')
            ->get();

        $featuredProducts = Product::with('category')
            ->where('status', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::with('category')
            ->where('status', true)
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact(
            'categories',
            'featuredProducts',
            'latestProducts'
        ));
    }
}