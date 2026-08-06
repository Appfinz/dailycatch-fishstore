@extends('layouts.admin')

@section('title', 'Customer Roster - Daily Catch Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-brand-navy">Customer Directory & History</h1>
            <p class="text-xs text-slate-500 font-medium">Registered customers, mobile numbers, and delivery addresses</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 uppercase text-[10px]">
                    <tr>
                        <th class="p-3">Customer Name</th>
                        <th class="p-3">Phone Number</th>
                        <th class="p-3">Primary Delivery Address</th>
                        <th class="p-3">Total Orders</th>
                        <th class="p-3 text-right">Joined Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $cust)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-brand-lightblue text-brand-blue font-black flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($cust->name ?: 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-brand-navy">{{ $cust->name ?: 'Customer' }}</h4>
                                    <p class="text-[10px] text-slate-400">{{ $cust->email ?: 'No email registered' }}</p>
                                </div>
                            </td>
                            <td class="p-3 font-mono font-bold text-slate-700">+91 {{ $cust->phone }}</td>
                            <td class="p-3 text-slate-600 max-w-xs truncate">{{ $cust->address ?: 'East Tambaram, Chennai' }}</td>
                            <td class="p-3">
                                <span class="bg-blue-50 text-brand-blue font-extrabold px-2.5 py-1 rounded-full text-[10px]">
                                    {{ $cust->orders_count }} Orders
                                </span>
                            </td>
                            <td class="p-3 text-right font-medium text-slate-500">{{ $cust->created_at ? $cust->created_at->format('M d, Y') : 'Recent' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-400 font-medium">No registered customers yet. Customer records appear automatically upon checkout.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
