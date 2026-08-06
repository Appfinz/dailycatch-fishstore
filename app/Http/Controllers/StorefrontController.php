<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CuttingStyle;
use App\Models\DeliverySlot;
use App\Models\Order;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Setting;
use App\Models\Branch;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $featuredProducts = Product::with(['category', 'cuttingStyles'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(8)
            ->get();

        $flashDeals = Product::with('category')
            ->where('is_active', true)
            ->whereNotNull('sale_price_per_kg')
            ->take(4)
            ->get();

        $combos = Product::whereHas('category', function ($q) {
            $q->where('slug', 'combos');
        })->take(4)->get();

        $recipes = Recipe::where('is_featured', true)->take(3)->get();

        return view('pages.home', compact('categories', 'featuredProducts', 'flashDeals', 'combos', 'recipes'));
    }

    public function catalog(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $query = Product::with(['category', 'cuttingStyles'])->where('is_active', true);

        // Category Filter
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Bone Type Filter
        if ($request->has('bone_type') && !empty($request->bone_type)) {
            $query->where('bone_type', $request->bone_type);
        }

        // Cooking Preference Filter
        if ($request->has('best_for') && !empty($request->best_for)) {
            $query->where('best_for', 'like', "%{$request->best_for}%");
        }

        // Search Query
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tamil_name', 'like', "%{$search}%")
                  ->orWhere('english_alias', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'popular');
        if ($sort === 'price_low') {
            $query->orderBy('price_per_kg', 'asc');
        } elseif ($sort === 'price_high') {
            $query->orderBy('price_per_kg', 'desc');
        } elseif ($sort === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);

        return view('pages.catalog', compact('categories', 'products'));
    }

    public function productDetail($slug)
    {
        $product = Product::with(['category', 'cuttingStyles', 'recipes'])->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }

    public function combos()
    {
        $combos = Product::whereHas('category', function ($q) {
            $q->where('slug', 'combos');
        })->get();

        return view('pages.combos', compact('combos'));
    }

    public function recipes()
    {
        $recipes = Recipe::with('product')->latest()->get();
        return view('pages.recipes.index', compact('recipes'));
    }

    public function recipeDetail($slug)
    {
        $recipe = Recipe::with('product.cuttingStyles')->where('slug', $slug)->firstOrFail();
        $relatedRecipes = Recipe::where('id', '!=', $recipe->id)->take(3)->get();

        return view('pages.recipes.show', compact('recipe', 'relatedRecipes'));
    }

    public function locations()
    {
        $branch = Branch::first();
        $settings = [
            'address' => Setting::get('shop_address', '22g, Thiruvalluvar street, East tambaram, Chennai-59'),
            'phone' => Setting::get('shop_phone', '91 8778199218'),
            'email' => Setting::get('shop_email', 'support@dailycatchfishshop.com'),
            'whatsapp' => Setting::get('whatsapp_number', '918778199218'),
        ];

        return view('pages.locations', compact('branch', 'settings'));
    }

    public function cart()
    {
        return view('pages.cart');
    }

    public function checkout()
    {
        $deliverySlots = DeliverySlot::where('is_active', true)->get();
        $branch = Branch::first();

        return view('pages.checkout', compact('deliverySlots', 'branch'));
    }

    public function orderTrack($orderNumber)
    {
        $order = Order::with(['items.product', 'items.cuttingStyle', 'branch'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $cancellationTimerMins = (int) Setting::get('cancellation_time_minutes', 2);
        $canCancel = false;
        $secondsRemaining = 0;

        if ($order->status === 'awaiting_fulfilment' && $order->cancellation_expires_at) {
            $now = now();
            if ($now->lt($order->cancellation_expires_at)) {
                $canCancel = true;
                $secondsRemaining = $now->diffInSeconds($order->cancellation_expires_at);
            }
        }

        $whatsappNumber = Setting::get('whatsapp_number', '918778199218');

        return view('pages.order-tracking', compact(
            'order',
            'canCancel',
            'secondsRemaining',
            'whatsappNumber',
            'cancellationTimerMins'
        ));
    }

    public function contact()
    {
        return redirect()->route('locations');
    }
}
