@extends('layouts.admin')

@section('title', 'Weigh & Manage Order #' . $order->order_number . ' - Daily Catch Admin')

@section('content')
<div class="space-y-8">
    
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between flex-wrap gap-4 border-b border-slate-800 pb-6">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-slate-400 hover:text-white">
                    <i class="fa-solid fa-arrow-left"></i> Back to Orders
                </a>
                <span class="text-slate-600">|</span>
                <span class="text-xs text-aqua-400 font-bold">Order #{{ $order->order_number }}</span>
            </div>
            <h1 class="text-2xl font-black text-white mt-1">Weigh Fish & Generate Final Bill</h1>
        </div>

        <div class="flex items-center gap-3">
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                if(strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                $billTrackUrl = route('orders.track', $order->order_number);
                $waText = "Hello {$order->customer_name}!\nYour fish at Daily Catch Fish Shop has been weighed & prepared fresh.\n\nOrder #{$order->order_number}\nEstimated Total: ₹{$order->estimated_total}\nFINAL BILL: ₹{$order->final_total}\n\nView invoice: {$billTrackUrl}";
            @endphp

            <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waText) }}" target="_blank" 
               class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow flex items-center gap-2">
                <i class="fa-brands fa-whatsapp text-base"></i> Send Bill on WhatsApp
            </a>

            <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl border border-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print Invoice
            </button>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Weighing Input Form & Order Items -->
        <div class="lg:col-span-8 space-y-6">
            
            <form action="{{ route('admin.orders.update-weight', $order->id) }}" method="POST" class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
                @csrf
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <div>
                        <h3 class="font-extrabold text-base text-white flex items-center gap-2">
                            <i class="fa-solid fa-scale-balanced text-amber-400"></i> Enter Actual Whole Fish Weight (Kg)
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Weigh the selected fish on shop digital scale and enter exact weight</p>
                    </div>
                    <span class="text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 px-3 py-1 rounded-full">
                        Step 1: Weigh & Calculate
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($order->items as $index => $item)
                        <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 space-y-3">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->product ? $item->product->image : 'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?auto=format&fit=crop&w=200&q=80' }}" class="w-12 h-12 object-cover rounded-xl border border-slate-800">
                                    <div>
                                        <h4 class="font-bold text-sm text-white">{{ $item->product_name }}</h4>
                                        <p class="text-xs text-aqua-400 font-semibold">Cutting Style: {{ $item->cutting_style_name ?: 'Whole Fish' }}</p>
                                        <p class="text-[11px] text-slate-400">Rate: ₹{{ number_format($item->unit_price_per_kg, 2) }}/kg (+₹{{ $item->cutting_charge }} cut charge)</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-400 block font-medium">Customer Requested</span>
                                    <span class="text-sm font-black text-amber-400">{{ number_format($item->requested_qty_kg, 2) }} Kg</span>
                                    <span class="text-xs text-slate-400 block">Est: ₹{{ number_format($item->estimated_item_total, 2) }}</span>
                                </div>
                            </div>

                            <!-- Weight Input Box -->
                            <div class="bg-slate-900 p-3 rounded-xl border border-slate-800 flex items-center justify-between gap-4">
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                
                                <label class="text-xs font-extrabold text-white flex items-center gap-1.5">
                                    <i class="fa-solid fa-weight-scale text-aqua-400"></i> Actual Whole Fish Weight (Kg):
                                </label>

                                <div class="flex items-center gap-2">
                                    <input type="number" step="0.01" min="0.05" max="20.0" 
                                           name="items[{{ $index }}][actual_qty_kg]" 
                                           value="{{ $item->actual_qty_kg ?: $item->requested_qty_kg }}" 
                                           class="w-32 bg-slate-950 border-2 border-aqua-400 text-white font-black text-center text-sm rounded-xl py-2 focus:outline-none focus:ring-2 focus:ring-aqua-400">
                                    <span class="text-xs font-bold text-slate-400">Kg</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-slate-800 pt-4 flex justify-between items-center">
                    <div>
                        <span class="text-xs text-slate-400 block">Current Status</span>
                        <span class="text-xs font-bold text-aqua-400 uppercase">{{ str_replace('_', ' ', $order->status) }}</span>
                    </div>

                    <button type="submit" class="bg-gradient-to-r from-ocean-600 to-aqua-600 hover:from-ocean-500 hover:to-aqua-500 text-white font-black text-sm px-8 py-3.5 rounded-xl shadow-xl transition-all flex items-center gap-2">
                        <i class="fa-solid fa-calculator"></i> Calculate Final Bill & Save
                    </button>
                </div>
            </form>

            <!-- Order Status Updater Box -->
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
                @csrf
                <h3 class="font-extrabold text-base text-white flex items-center gap-2">
                    <i class="fa-solid fa-arrows-spin text-ocean-500"></i> Update Fulfilment Status Workflow
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Status Step</label>
                        <select name="status" class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-3 focus:outline-none focus:border-ocean-500">
                            <option value="awaiting_fulfilment" {{ $order->status === 'awaiting_fulfilment' ? 'selected' : '' }}>Awaiting Fulfilment (Weighing Pending)</option>
                            <option value="final_bill_ready" {{ $order->status === 'final_bill_ready' ? 'selected' : '' }}>Final Bill Ready (Weighed)</option>
                            <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing (Cutting & Cleaning)</option>
                            <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Out For Delivery</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered (Paid)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Admin Notes / Remarks</label>
                        <input type="text" name="admin_notes" value="{{ $order->admin_notes }}" placeholder="e.g. Delivered by rider John" 
                               class="w-full bg-slate-950 border border-slate-700 text-xs font-medium text-white rounded-xl px-3.5 py-3 focus:outline-none focus:border-ocean-500">
                    </div>
                </div>

                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition-all">
                    Update Order Status
                </button>
            </form>

        </div>

        <!-- Right: Customer Info & Summary Invoice -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Customer Details Card with Google Maps Navigation Button -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
                <h3 class="font-extrabold text-base text-white border-b border-slate-800 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-user-tag text-aqua-400"></i> Customer & Delivery Location
                </h3>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-500 block">Customer Name</span>
                        <span class="font-extrabold text-white text-sm">{{ $order->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Mobile Phone</span>
                        <span class="font-bold text-aqua-400 text-sm">+91 {{ $order->customer_phone }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Delivery Type & Date</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="font-bold text-white uppercase bg-slate-800 px-2.5 py-1 rounded inline-block">{{ $order->delivery_type }}</span>
                            @if($order->is_preorder)
                                <span class="font-extrabold text-emerald-400 bg-emerald-950 border border-emerald-800 px-2 py-0.5 rounded text-[10px]">PRE-ORDER (Tomorrow)</span>
                            @endif
                        </div>
                    </div>
                    @if($order->delivery_type === 'delivery')
                        <div>
                            <span class="text-slate-500 block">Delivery Address</span>
                            <p class="text-slate-300 font-medium leading-relaxed mt-0.5">{{ $order->delivery_address }}</p>
                        </div>
                        @if($order->landmark)
                            <div>
                                <span class="text-slate-500 block">Landmark</span>
                                <p class="text-slate-300 font-medium">{{ $order->landmark }}</p>
                            </div>
                        @endif

                        <!-- Delivery Partner Google Maps Turn-by-Turn Button -->
                        @if($order->latitude && $order->longitude)
                            <div class="pt-2 border-t border-slate-800">
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}" 
                                   target="_blank" 
                                   class="w-full bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs py-3 rounded-xl shadow-lg flex items-center justify-center gap-2 transition-all">
                                    <i class="fa-solid fa-diamond-turn-right text-sm"></i>
                                    <span>Navigate to Customer Location (Google Maps)</span>
                                </a>
                                <span class="text-[10px] text-slate-400 block text-center mt-1">GPS Coordinates: {{ $order->latitude }}, {{ $order->longitude }}</span>
                            </div>
                        @endif
                    @endif
                    <div>
                        <span class="text-slate-500 block">Selected Slot</span>
                        <span class="font-bold text-slate-300">{{ $order->delivery_slot }}</span>
                    </div>
                </div>
            </div>

            <!-- Bill Comparison Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
                <h3 class="font-extrabold text-base text-white border-b border-slate-800 pb-3">Bill Comparison</h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-slate-400">
                        <span>Requested Est Subtotal</span>
                        <span class="font-bold text-slate-200">₹{{ number_format($order->estimated_subtotal, 2) }}</span>
                    </div>
                    @if($order->preorder_discount > 0)
                        <div class="flex justify-between text-emerald-400 font-bold">
                            <span>Pre-Order Discount</span>
                            <span>-₹{{ number_format($order->preorder_discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-slate-400">
                        <span>Estimated Total</span>
                        <span class="font-bold text-amber-400">₹{{ number_format($order->estimated_total, 2) }}</span>
                    </div>
                    
                    <div class="pt-3 border-t border-slate-800">
                        <div class="flex justify-between text-slate-400">
                            <span>Actual Weighed Subtotal</span>
                            <span class="font-bold text-slate-200">₹{{ number_format($order->final_subtotal ?: 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>Delivery Fee</span>
                            <span class="font-bold text-slate-200">₹{{ number_format($order->delivery_charge, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-base font-black text-white pt-2 mt-2 border-t border-slate-800">
                            <span>FINAL PAYABLE</span>
                            <span class="text-xl text-emerald-400">₹{{ number_format($order->final_total ?: $order->estimated_total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
