<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDate('created_at', today())->get();
        $totalRevenueToday = $todayOrders->sum(function ($order) {
            return $order->final_total ?: $order->estimated_total;
        });

        $awaitingWeighing = Order::where('status', 'awaiting_fulfilment')->count();
        $outForDelivery = Order::where('status', 'out_for_delivery')->count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();

        $recentOrders = Order::with('items')->latest()->take(8)->get();
        $branches = Branch::all();

        return view('admin.dashboard', compact(
            'todayOrders',
            'totalRevenueToday',
            'awaitingWeighing',
            'outForDelivery',
            'totalCustomers',
            'totalProducts',
            'recentOrders',
            'branches'
        ));
    }
}
