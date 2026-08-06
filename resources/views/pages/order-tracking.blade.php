@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' - Daily Catch Fish Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Order Header & Status Card -->
    <div class="bg-navy-900 text-white rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden border border-navy-800">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-navy-800 pb-6">
            <div>
                <span class="text-xs text-aqua-400 font-bold uppercase tracking-wider">Order Status & Invoice</span>
                <h1 class="text-2xl sm:text-4xl font-black text-white mt-1">Order #{{ $order->order_number }}</h1>
                <p class="text-xs text-slate-400 mt-1">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>

            <!-- Status Pill -->
            <div class="text-right">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider shadow-lg 
                    @if($order->status === 'awaiting_fulfilment') bg-amber-500 text-amber-950
                    @elseif($order->status === 'final_bill_ready') bg-sky-400 text-slate-950 badge-pulse
                    @elseif($order->status === 'preparing') bg-blue-500 text-white
                    @elseif($order->status === 'out_for_delivery') bg-indigo-500 text-white
                    @elseif($order->status === 'delivered') bg-emerald-500 text-white
                    @else bg-rose-500 text-white @endif">
                    <i class="fa-solid fa-circle text-[8px]"></i>
                    {{ str_replace('_', ' ', strtoupper($order->status)) }}
                </span>
            </div>
        </div>

        <!-- Timeline Workflow Progress Bar -->
        <div class="pt-8 max-w-4xl mx-auto">
            @php
                $statuses = ['awaiting_fulfilment', 'final_bill_ready', 'preparing', 'out_for_delivery', 'delivered'];
                $currentIndex = array_search($order->status, $statuses);
                if ($currentIndex === false && $order->status === 'cancelled') $currentIndex = -1;
            @endphp

            @if($order->status === 'cancelled')
                <div class="bg-rose-950/80 border border-rose-800/80 rounded-2xl p-4 text-center text-rose-200 text-xs font-bold">
                    <i class="fa-solid fa-ban text-lg mb-1 block text-rose-400"></i>
                    This order has been cancelled.
                </div>
            @else
                <div class="grid grid-cols-5 gap-2 text-center relative">
                    <div class="col-span-5 absolute top-4 left-6 right-6 h-1 bg-navy-800 -z-0">
                        <div class="h-full bg-gradient-to-r from-ocean-500 to-aqua-400 transition-all duration-500" style="width: {{ max(0, ($currentIndex / 4) * 100) }}%"></div>
                    </div>

                    <div class="relative z-10 space-y-2">
                        <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs {{ $currentIndex >= 0 ? 'bg-aqua-400 text-navy-950 font-black shadow-lg ring-4 ring-aqua-400/20' : 'bg-navy-800 text-slate-400' }}">1</div>
                        <span class="text-[10px] font-bold block {{ $currentIndex >= 0 ? 'text-white' : 'text-slate-400' }}">Weighing Fish</span>
                    </div>

                    <div class="relative z-10 space-y-2">
                        <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs {{ $currentIndex >= 1 ? 'bg-aqua-400 text-navy-950 font-black shadow-lg ring-4 ring-aqua-400/20' : 'bg-navy-800 text-slate-400' }}">2</div>
                        <span class="text-[10px] font-bold block {{ $currentIndex >= 1 ? 'text-white' : 'text-slate-400' }}">Final Bill Ready</span>
                    </div>

                    <div class="relative z-10 space-y-2">
                        <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs {{ $currentIndex >= 2 ? 'bg-aqua-400 text-navy-950 font-black shadow-lg ring-4 ring-aqua-400/20' : 'bg-navy-800 text-slate-400' }}">3</div>
                        <span class="text-[10px] font-bold block {{ $currentIndex >= 2 ? 'text-white' : 'text-slate-400' }}">Cutting & Cleaning</span>
                    </div>

                    <div class="relative z-10 space-y-2">
                        <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs {{ $currentIndex >= 3 ? 'bg-aqua-400 text-navy-950 font-black shadow-lg ring-4 ring-aqua-400/20' : 'bg-navy-800 text-slate-400' }}">4</div>
                        <span class="text-[10px] font-bold block {{ $currentIndex >= 3 ? 'text-white' : 'text-slate-400' }}">Out for Delivery</span>
                    </div>

                    <div class="relative z-10 space-y-2">
                        <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs {{ $currentIndex >= 4 ? 'bg-emerald-400 text-navy-950 font-black shadow-lg' : 'bg-navy-800 text-slate-400' }}">5</div>
                        <span class="text-[10px] font-bold block {{ $currentIndex >= 4 ? 'text-emerald-400' : 'text-slate-400' }}">Delivered</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- 2-Minute Cancellation Widget (If eligible) -->
    @if($canCancel)
        <div class="bg-amber-500 text-amber-950 rounded-3xl p-5 shadow-lg flex flex-wrap items-center justify-between gap-4 border border-amber-400">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-stopwatch text-2xl shrink-0"></i>
                <div>
                    <h4 class="font-black text-sm">Instant Cancellation Available</h4>
                    <p class="text-xs font-semibold">You can cancel this order within {{ $cancellationTimerMins }} minutes of placing it.</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-black bg-amber-950 text-amber-300 px-3.5 py-1.5 rounded-xl" id="countdownTimer">
                    {{ sprintf('%02d:%02d', floor($secondsRemaining / 60), $secondsRemaining % 60) }}
                </span>
                <button onclick="cancelOrderNow()" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow transition-colors">
                    Cancel Order
                </button>
            </div>
        </div>
    @endif

    <!-- Core Feature: Transparent 2-Column Price Comparison Table -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200 space-y-6">
        <div class="flex items-center justify-between flex-wrap gap-4 border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-xl font-extrabold text-navy-900">Transparent Fish Weighing & Bill Breakdown</h2>
                <p class="text-xs text-slate-500">Comparison of your Requested Estimated Order vs. Actual Whole Fish Weight weighed in shop</p>
            </div>
            
            <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hi Daily Catch, inquiring status for Order #' . $order->order_number) }}" 
               target="_blank" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2 rounded-xl shadow flex items-center gap-1.5 transition-colors">
                <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp Support
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-navy-900 text-xs font-black uppercase tracking-wider border-b border-slate-200">
                        <th class="p-4 rounded-l-xl">Product</th>
                        <th class="p-4">Cutting Style</th>
                        <th class="p-4">Price / kg</th>
                        <th class="p-4 bg-sky-50 text-sky-900 border-x border-sky-200">Estimated Order (Req)</th>
                        <th class="p-4 bg-emerald-50 text-emerald-950 rounded-r-xl border-r border-emerald-200">Final Order (Actual Weighed)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @foreach($order->items as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 font-bold text-navy-900">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->product ? $item->product->image : 'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?auto=format&fit=crop&w=200&q=80' }}" class="w-10 h-10 object-cover rounded-lg border border-slate-200">
                                    <div>
                                        <span class="block font-extrabold text-slate-800">{{ $item->product_name }}</span>
                                        @if($item->product && $item->product->tamil_name)
                                            <span class="text-[10px] text-ocean-600 font-semibold">{{ $item->product->tamil_name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 font-bold text-slate-700">
                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 text-slate-800">
                                    {{ $item->cutting_style_name ?: 'Whole Fish' }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-slate-800">
                                ₹{{ number_format($item->unit_price_per_kg, 2) }}
                            </td>
                            <!-- Estimated Column -->
                            <td class="p-4 bg-sky-50/60 font-semibold border-x border-sky-100">
                                <span class="block text-slate-700 font-extrabold">{{ number_format($item->requested_qty_kg, 2) }} Kg</span>
                                <span class="text-sky-800 font-black text-sm">₹{{ number_format($item->estimated_item_total, 2) }}</span>
                            </td>
                            <!-- Final Weighed Column -->
                            <td class="p-4 bg-emerald-50/60 font-semibold border-r border-emerald-100">
                                @if($item->actual_qty_kg)
                                    <span class="block text-emerald-950 font-extrabold text-sm">{{ number_format($item->actual_qty_kg, 2) }} Kg</span>
                                    <span class="text-emerald-700 font-black text-sm">₹{{ number_format($item->final_item_total, 2) }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-amber-700 bg-amber-100/80 px-2.5 py-1 rounded-full text-[10px] font-bold">
                                        <i class="fa-solid fa-clock text-[9px]"></i> Weighing in shop...
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Comparison Boxes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
            
            <!-- Est Box -->
            <div class="bg-sky-50/80 border border-sky-200 rounded-2xl p-5 space-y-2">
                <h4 class="font-extrabold text-xs text-sky-900 uppercase tracking-wider">Estimated Order Summary</h4>
                <div class="flex justify-between text-xs text-sky-950">
                    <span>Requested Items Total</span>
                    <span class="font-bold">₹{{ number_format($order->estimated_subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs text-sky-950">
                    <span>Delivery Charge</span>
                    <span class="font-bold">₹{{ number_format($order->delivery_charge, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm font-black text-sky-950 pt-2 border-t border-sky-200">
                    <span>Estimated Total</span>
                    <span>₹{{ number_format($order->estimated_total, 2) }}</span>
                </div>
            </div>

            <!-- Final Bill Box -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 space-y-2">
                <h4 class="font-extrabold text-xs text-emerald-900 uppercase tracking-wider flex items-center justify-between">
                    <span>Final Weighed Order Bill</span>
                    @if($order->weight_updated_at)
                        <span class="text-[10px] font-normal text-emerald-700">Weighed: {{ $order->weight_updated_at->format('h:i A') }}</span>
                    @endif
                </h4>

                @if($order->final_total)
                    <div class="flex justify-between text-xs text-emerald-950">
                        <span>Actual Weighed Items Subtotal</span>
                        <span class="font-bold">₹{{ number_format($order->final_subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-emerald-950">
                        <span>Delivery Charge</span>
                        <span class="font-bold">₹{{ number_format($order->delivery_charge, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-black text-emerald-950 pt-2 border-t border-emerald-300">
                        <span>FINAL PAYABLE BILL</span>
                        <span class="text-xl text-emerald-700">₹{{ number_format($order->final_total, 2) }}</span>
                    </div>
                @else
                    <div class="py-4 text-center text-xs text-amber-800 font-semibold">
                        <i class="fa-solid fa-scale-balanced text-lg mb-1 block text-amber-600"></i>
                        Store is currently weighing your fresh fish. This bill will auto-update live!
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>

@push('scripts')
@if($canCancel)
<script>
    let remainingSeconds = {{ $secondsRemaining }};
    const timerElem = document.getElementById('countdownTimer');

    const interval = setInterval(() => {
        remainingSeconds--;
        if (remainingSeconds <= 0) {
            clearInterval(interval);
            window.location.reload();
        } else {
            let m = Math.floor(remainingSeconds / 60);
            let s = remainingSeconds % 60;
            timerElem.innerText = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        }
    }, 1000);

    async function cancelOrderNow() {
        if (!confirm('Are you sure you want to cancel this order?')) return;
        try {
            const res = await fetch('/api/v1/orders/{{ $order->order_number }}/cancel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await res.json();
            if (data.status === 'success') {
                alert('Order cancelled successfully!');
                window.location.reload();
            } else {
                alert(data.message || 'Error cancelling order');
            }
        } catch (e) { console.error(e); }
    }
</script>
@endif
@endpush
@endsection
