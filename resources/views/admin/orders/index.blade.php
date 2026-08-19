@extends('layouts.admin')

@section('title', 'Order Operations & Kanban Workflow - Daily Catch Admin')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Order Operations Hub & Weighing Control</h1>
            <p class="text-xs text-slate-400 mt-1">Live order workflow from harbor weighing to customer delivery</p>
        </div>

        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Order # or Customer Phone..." 
                   class="bg-slate-900 border border-slate-700 text-xs text-white rounded-xl px-3.5 py-2 focus:outline-none focus:border-ocean-500">
            <button type="submit" class="bg-ocean-600 text-white font-bold text-xs px-4 py-2 rounded-xl">Search</button>
        </form>
    </div>

    <!-- Status Filter Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
        @php $currentStatus = request('status', 'all'); @endphp
        <a href="{{ route('admin.orders.index', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentStatus === 'all' ? 'bg-ocean-600 text-white' : 'bg-slate-900 text-slate-400 hover:bg-slate-800' }}">
            All Orders ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'awaiting_fulfilment']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ $currentStatus === 'awaiting_fulfilment' ? 'bg-amber-500 text-slate-950 font-black' : 'bg-slate-900 text-amber-400 hover:bg-slate-800' }}">
            <i class="fa-solid fa-scale-balanced"></i> Awaiting Weighing ({{ $counts['awaiting_fulfilment'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'final_bill_ready']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentStatus === 'final_bill_ready' ? 'bg-sky-500 text-white' : 'bg-slate-900 text-sky-400 hover:bg-slate-800' }}">
            Final Bill Ready ({{ $counts['final_bill_ready'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'preparing']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentStatus === 'preparing' ? 'bg-blue-600 text-white' : 'bg-slate-900 text-slate-400 hover:bg-slate-800' }}">
            Preparing Cut ({{ $counts['preparing'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'out_for_delivery']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentStatus === 'out_for_delivery' ? 'bg-indigo-600 text-white' : 'bg-slate-900 text-slate-400 hover:bg-slate-800' }}">
            Out for Delivery ({{ $counts['out_for_delivery'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentStatus === 'delivered' ? 'bg-emerald-600 text-white' : 'bg-slate-900 text-slate-400 hover:bg-slate-800' }}">
            Delivered ({{ $counts['delivered'] }})
        </a>
    </div>

    <!-- Orders Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950 text-slate-400 text-xs uppercase font-bold border-b border-slate-800">
                        <th class="p-4 rounded-l-xl">Order # & Date</th>
                        <th class="p-4">Customer Info</th>
                        <th class="p-4">Delivery Type & Slot</th>
                        <th class="p-4">Estimated Total</th>
                        <th class="p-4">Final Weighed Total</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right rounded-r-xl">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-xs">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-800/50 transition-colors">
                            <td class="p-4 font-bold text-white">
                                <span class="block text-sm">#{{ $order->order_number }}</span>
                                <span class="text-[10px] text-slate-500 font-medium">{{ $order->created_at->format('d M, h:i A') }}</span>
                            </td>
                            <td class="p-4 font-semibold text-slate-200">
                                {{ $order->customer_name }}
                                <span class="block text-[10px] text-aqua-400">+91 {{ $order->customer_phone }}</span>
                            </td>
                            <td class="p-4 text-slate-300">
                                <span class="uppercase text-[10px] font-black px-2 py-0.5 rounded bg-slate-800 text-aqua-400 inline-block mb-1">
                                    {{ $order->delivery_type }}
                                </span>
                                <span class="block text-[11px] text-slate-400">{{ $order->delivery_slot }}</span>
                            </td>
                            <td class="p-4 font-bold text-amber-400">₹{{ number_format($order->estimated_total, 2) }}</td>
                            <td class="p-4 font-bold">
                                @if($order->final_total)
                                    <span class="text-emerald-400 font-extrabold text-sm">₹{{ number_format($order->final_total, 2) }}</span>
                                @else
                                    <span class="text-amber-500 text-[11px] font-semibold flex items-center gap-1">
                                        <i class="fa-solid fa-clock"></i> Needs Weighing
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                    @if($order->status === 'awaiting_fulfilment') bg-amber-500/20 text-amber-300 border border-amber-500/40
                                    @elseif($order->status === 'final_bill_ready') bg-sky-500/20 text-sky-300 border border-sky-500/40
                                    @elseif($order->status === 'delivered') bg-emerald-500/20 text-emerald-300 border border-emerald-500/40
                                    @elseif($order->status === 'cancelled') bg-rose-500/20 text-rose-300 border border-rose-500/40
                                    @else bg-slate-800 text-slate-400 @endif">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                @if($order->latitude && $order->longitude)
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}" 
                                       target="_blank" 
                                       title="Open Google Maps Navigation" 
                                       class="bg-blue-600/30 hover:bg-blue-600 text-blue-300 hover:text-white font-bold p-2 rounded-xl text-xs inline-flex items-center gap-1 border border-blue-500/40 shadow">
                                        <i class="fa-solid fa-diamond-turn-right text-xs"></i> Maps
                                    </a>
                                @endif
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-3.5 py-2 rounded-xl text-xs inline-flex items-center gap-1.5 shadow">
                                    <i class="fa-solid fa-scale-balanced"></i> Weigh & Update
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection
