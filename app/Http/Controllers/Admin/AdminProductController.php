<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CuttingStyle;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'cuttingStyles'])->latest()->paginate(15);
        $categories = Category::all();
        $cuttingStyles = CuttingStyle::all();

        return view('admin.products.index', compact('products', 'categories', 'cuttingStyles'));
    }

    public function quickPriceUpdate(Request $request)
    {
        $request->validate([
            'prices' => 'required|array',
            'prices.*.id' => 'required|exists:products,id',
            'prices.*.price_per_kg' => 'required|numeric|min:0',
            'prices.*.sale_price_per_kg' => 'nullable|numeric|min:0',
            'prices.*.availability_status' => 'required|in:in_stock,out_of_stock,limited',
        ]);

        foreach ($request->prices as $item) {
            Product::where('id', $item['id'])->update([
                'price_per_kg' => $item['price_per_kg'],
                'sale_price_per_kg' => $item['sale_price_per_kg'] ?: null,
                'availability_status' => $item['availability_status'],
            ]);
        }

        return redirect()->back()->with('success', 'Daily catch fish prices updated successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_per_kg' => 'required|numeric|min:0',
            'sale_price_per_kg' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|numeric|min:0',
            'tamil_name' => 'nullable|string',
            'english_alias' => 'nullable|string',
            'short_desc' => 'nullable|string',
            'cutting_styles' => 'nullable|array',
            'cutting_style_fees' => 'nullable|array',
        ]);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'tamil_name' => $request->tamil_name,
            'english_alias' => $request->english_alias,
            'slug' => Str::slug($request->name) . '-' . rand(100, 999),
            'short_desc' => $request->short_desc,
            'description' => $request->short_desc,
            'price_per_kg' => $request->price_per_kg,
            'sale_price_per_kg' => $request->sale_price_per_kg,
            'image' => $request->image ?: 'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?auto=format&fit=crop&w=600&q=80',
            'stock_quantity' => $request->stock_quantity,
            'availability_status' => 'in_stock',
            'has_weight_variation' => $request->has('has_weight_variation'),
            'is_featured' => $request->has('is_featured'),
            'is_active' => true,
        ]);

        $syncData = [];
        if ($request->has('cutting_styles')) {
            foreach ($request->cutting_styles as $csId) {
                $customFee = isset($request->cutting_style_fees[$csId]) && $request->cutting_style_fees[$csId] !== ''
                    ? (float) $request->cutting_style_fees[$csId]
                    : null;
                $syncData[$csId] = ['additional_charge' => $customFee];
            }
        }
        $product->cuttingStyles()->sync($syncData);

        return redirect()->back()->with('success', 'New fish product added successfully!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_per_kg' => 'required|numeric|min:0',
            'sale_price_per_kg' => 'nullable|numeric|min:0',
            'cutting_styles' => 'nullable|array',
            'cutting_style_fees' => 'nullable|array',
        ]);

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'tamil_name' => $request->tamil_name,
            'english_alias' => $request->english_alias,
            'price_per_kg' => $request->price_per_kg,
            'sale_price_per_kg' => $request->sale_price_per_kg,
            'has_weight_variation' => $request->has('has_weight_variation'),
            'availability_status' => $request->availability_status ?: 'in_stock',
        ]);

        $syncData = [];
        if ($request->has('cutting_styles')) {
            foreach ($request->cutting_styles as $csId) {
                $customFee = isset($request->cutting_style_fees[$csId]) && $request->cutting_style_fees[$csId] !== ''
                    ? (float) $request->cutting_style_fees[$csId]
                    : null;
                $syncData[$csId] = ['additional_charge' => $customFee];
            }
        }
        $product->cuttingStyles()->sync($syncData);

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully!');
    }
}
