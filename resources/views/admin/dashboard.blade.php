@extends('layouts.admin')

@section('title', 'Dashboard Overview - Daily Catch Admin')

@section('content')
<div class="space-y-8">
    
    <div>
        <h1 class="text-2xl font-black text-logonavy tracking-tight">Admin Dashboard Overview</h1>
        <p class="text-xs text-slate-500 mt-1">Real-time daily catch order management and fish weighing control</p>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white border border-slate-200 rounded-3xl p-5 space-y-2 relative overflow-hidden shadow-sm">
            <div class="flex justify-between items-start">
                <span class="text-xs text-slate-500 font-bold uppercase">Today's Revenue</span>
                <div class="w-9 h-9 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold border border-emerald-100">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-logonavy">₹{{ number_format($totalRevenueToday, 2) }}</h3>
            <p class="text-[11px] text-slate-500 font-medium">{{ $todayOrders->count() }} Orders Placed Today</p>
        </div>

        <div class="bg-white border-2 border-amber-400 rounded-3xl p-5 space-y-2 relative overflow-hidden shadow-sm">
            <div class="flex justify-between items-start">
                <span class="text-xs text-amber-800 font-bold uppercase">Awaiting Fish Weighing</span>
                <div class="w-9 h-9 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold border border-amber-200">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-amber-700">{{ $awaitingWeighing }}</h3>
            <p class="text-[11px] text-amber-900/80 font-medium">Orders need actual whole fish weight entry</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-3xl p-5 space-y-2 relative overflow-hidden shadow-sm">
            <div class="flex justify-between items-start">
                <span class="text-xs text-slate-500 font-bold uppercase">Out For Delivery</span>
                <div class="w-9 h-9 rounded-2xl bg-sky-50 text-logoocean flex items-center justify-center text-sm font-bold border border-sky-100">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-logonavy">{{ $outForDelivery }}</h3>
            <p class="text-[11px] text-slate-500 font-medium">In transit in East Tambaram zone</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-3xl p-5 space-y-2 relative overflow-hidden shadow-sm">
            <div class="flex justify-between items-start">
                <span class="text-xs text-slate-500 font-bold uppercase">Active Products</span>
                <div class="w-9 h-9 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-bold border border-purple-100">
                    <i class="fa-solid fa-fish"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-logonavy">{{ $totalProducts }}</h3>
            <p class="text-[11px] text-slate-500 font-medium">{{ $totalCustomers }} Registered Customers</p>
        </div>

    </div>

    <!-- Recent Orders Section -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 space-y-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-base text-logonavy">Recent Customer Orders</h3>
                <p class="text-xs text-slate-500">Incoming fish orders ready for shop weighing and dispatch</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-logoocean hover:underline flex items-center gap-1">
                View All Orders <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 text-xs uppercase font-bold border-b border-slate-200">
                        <th class="p-3.5 rounded-l-xl">Order #</th>
                        <th class="p-3.5">Customer</th>
                        <th class="p-3.5">Delivery Slot</th>
                        <th class="p-3.5">Est. Bill</th>
                        <th class="p-3.5">Final Bill</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-right rounded-r-xl">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-3.5 font-bold text-logonavy">#{{ $order->order_number }}</td>
                            <td class="p-3.5 font-semibold text-slate-800">
                                {{ $order->customer_name }}
                                <span class="block text-[10px] text-slate-500">{{ $order->customer_phone }}</span>
                            </td>
                            <td class="p-3.5 text-slate-600 font-medium">{{ $order->delivery_slot }}</td>
                            <td class="p-3.5 font-bold text-amber-700">₹{{ number_format($order->estimated_total, 2) }}</td>
                            <td class="p-3.5 font-bold text-emerald-700">
                                {{ $order->final_total ? '₹' . number_format($order->final_total, 2) : 'Awaiting Weighing' }}
                            </td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                    @if($order->status === 'awaiting_fulfilment') bg-amber-100 text-amber-900 border border-amber-300
                                    @elseif($order->status === 'final_bill_ready') bg-sky-100 text-sky-900 border border-sky-300
                                    @elseif($order->status === 'delivered') bg-emerald-100 text-emerald-900 border border-emerald-300
                                    @else bg-slate-100 text-slate-700 @endif">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-logonavy hover:bg-logoocean text-white font-bold px-3 py-1.5 rounded-xl text-xs inline-flex items-center gap-1 shadow">
                                    <i class="fa-solid fa-scale-balanced"></i> Weigh & Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500 font-medium">No orders recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
