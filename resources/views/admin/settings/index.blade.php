@extends('layouts.admin')

@section('title', 'Store Settings & Delivery Radius - Daily Catch Admin')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-2xl font-black text-brand-navy">Store Operations & Settings</h1>
        <p class="text-xs text-slate-500 font-medium">Configure store location, delivery fee, WhatsApp number, and order cancellation window</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6 text-xs font-semibold">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-700 mb-1">Official WhatsApp Notification Number *</label>
                    <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] }}" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 font-mono font-bold text-brand-navy focus:outline-none focus:border-brand-blue">
                    <span class="text-[10px] text-slate-400 font-medium mt-1 block">Used for sending final weighing bills & WhatsApp invoices</span>
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Standard Delivery Radius (KM) *</label>
                    <input type="number" step="0.1" name="default_delivery_radius_km" value="{{ $settings['default_delivery_radius_km'] }}" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-brand-navy focus:outline-none focus:border-brand-blue">
                    <span class="text-[10px] text-slate-400 font-medium mt-1 block">Google Maps pincode boundary check limit</span>
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Order Cancellation Window (Minutes) *</label>
                    <input type="number" name="cancellation_time_minutes" value="{{ $settings['cancellation_time_minutes'] }}" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-brand-navy focus:outline-none focus:border-brand-blue">
                    <span class="text-[10px] text-slate-400 font-medium mt-1 block">Time window allowing customers to cancel orders</span>
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Standard Delivery Fee (₹) *</label>
                    <input type="number" name="delivery_fee" value="{{ $settings['delivery_fee'] }}" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-brand-navy focus:outline-none focus:border-brand-blue">
                    <span class="text-[10px] text-slate-400 font-medium mt-1 block">Flat packing and ice box delivery fee</span>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6 space-y-4">
                <div>
                    <label class="block text-slate-700 mb-1">Physical Store Address *</label>
                    <input type="text" name="shop_address" value="{{ $settings['shop_address'] }}" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-brand-navy focus:outline-none focus:border-brand-blue">
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Customer Support Phone Number *</label>
                    <input type="text" name="shop_phone" value="{{ $settings['shop_phone'] }}" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-brand-navy focus:outline-none focus:border-brand-blue">
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-brand-navy hover:bg-slate-900 text-white font-extrabold px-8 py-3.5 rounded-xl shadow uppercase text-xs tracking-wider">
                    Save Store Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
