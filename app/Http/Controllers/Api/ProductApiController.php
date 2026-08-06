<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function categories()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'cuttingStyles'])
            ->where('is_active', true);

        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tamil_name', 'like', "%{$search}%")
                  ->orWhere('english_alias', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'cuttingStyles'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $product,
        ]);
    }
}
