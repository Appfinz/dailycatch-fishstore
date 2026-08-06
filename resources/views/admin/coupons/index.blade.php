@extends('layouts.admin')

@section('title', 'Coupons & Discounts - Daily Catch Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-brand-navy">Coupons & Promo Codes</h1>
            <p class="text-xs text-slate-500 font-medium">Create and manage customer promotional discount codes</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Coupons Table -->
        <div class="lg:col-span-8 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <h3 class="font-extrabold text-sm text-brand-navy mb-4">Active Discount Coupons</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 uppercase text-[10px]">
                        <tr>
                            <th class="p-3">Coupon Code</th>
                            <th class="p-3">Discount</th>
                            <th class="p-3">Min Order</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($coupons as $cp)
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-3 font-mono font-black text-brand-blue text-sm">
                                    {{ $cp->code }}
                                    <span class="block text-[10px] text-slate-400 font-normal font-sans">{{ $cp->description }}</span>
                                </td>
                                <td class="p-3 font-extrabold text-brand-navy">
                                    {{ $cp->discount_type === 'fixed' ? '₹' . number_format($cp->discount_value, 0) . ' FLAT OFF' : number_format($cp->discount_value, 0) . '% OFF' }}
                                </td>
                                <td class="p-3 font-bold text-slate-600">₹{{ number_format($cp->min_order_amount, 0) }}</td>
                                <td class="p-3">
                                    <span class="bg-emerald-100 text-emerald-800 font-extrabold px-2.5 py-1 rounded-full text-[10px]">
                                        ACTIVE
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('admin.coupons.destroy', $cp->id) }}" method="POST" onsubmit="return confirm('Delete this coupon code?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold text-xs">
                                            <i class="fa-solid fa-trash-can"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Coupon Form -->
        <div class="lg:col-span-4 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="font-extrabold text-sm text-brand-navy">Create Promo Coupon</h3>

            <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4 text-xs font-semibold">
                @csrf
                <div>
                    <label class="block text-slate-700 mb-1">Coupon Code *</label>
                    <input type="text" name="code" required placeholder="e.g. CATCH200" class="w-full border border-slate-200 rounded-xl px-3 py-2 uppercase font-mono font-bold text-brand-blue focus:outline-none focus:border-brand-blue">
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Description</label>
                    <input type="text" name="description" placeholder="e.g. Flat ₹200 off on weekend orders" class="w-full border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-brand-blue">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-700 mb-1">Discount Type</label>
                        <select name="discount_type" class="w-full border border-slate-200 rounded-xl px-3 py-2 font-bold focus:outline-none focus:border-brand-blue">
                            <option value="fixed">Fixed (₹)</option>
                            <option value="percentage">Percentage (%)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-700 mb-1">Discount Value</label>
                        <input type="number" step="0.01" name="discount_value" required placeholder="150" class="w-full border border-slate-200 rounded-xl px-3 py-2 font-bold focus:outline-none focus:border-brand-blue">
                    </div>
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Minimum Order Amount (₹)</label>
                    <input type="number" step="0.01" name="min_order_amount" required value="500" class="w-full border border-slate-200 rounded-xl px-3 py-2 font-bold focus:outline-none focus:border-brand-blue">
                </div>

                <button type="submit" class="w-full bg-brand-navy hover:bg-slate-900 text-white font-extrabold py-3 rounded-xl shadow uppercase text-xs tracking-wider">
                    Create Coupon Code
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
