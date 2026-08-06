@extends('layouts.app')

@section('title', 'Seafood Family Combos - Daily Catch Fish Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <span class="bg-amber-100 text-amber-800 text-xs font-black px-4 py-1.5 rounded-full border border-amber-200 uppercase tracking-wider">
            Weekend Value Bundles
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-brand-navy font-display">Seafood Family Combo Packs</h1>
        <p class="text-xs sm:text-sm text-slate-500 font-semibold">Specially curated seafood combinations for curry, tawa fry & soups at discounted prices!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($combos as $combo)
            <div class="bg-white rounded-3xl p-6 border border-slate-200 space-y-5 flex flex-col justify-between hover:shadow-lg transition-all shadow-sm">
                <div class="space-y-4">
                    <div class="relative aspect-[16/9] rounded-2xl overflow-hidden bg-slate-100">
                        <img src="{{ $combo->image }}" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-amber-400 text-slate-950 text-xs font-black uppercase px-3 py-1 rounded-full shadow">
                            Save Bundle Deal
                        </span>
                    </div>

                    <div>
                        <h2 class="text-xl font-extrabold text-brand-navy font-display">{{ $combo->name }}</h2>
                        <p class="text-xs text-brand-blue font-bold mt-0.5">{{ $combo->tamil_name }}</p>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed font-medium">{{ $combo->description }}</p>
                    </div>

                    <!-- What's Inside Box -->
                    <div class="bg-blue-50/60 p-4 rounded-2xl border border-blue-100 space-y-2">
                        <span class="text-xs font-extrabold text-brand-navy block uppercase tracking-wider">Combo Contents:</span>
                        <p class="text-xs text-slate-700 font-bold">{{ $combo->short_desc }}</p>
                    </div>

                    <div class="flex items-baseline justify-between border-t border-slate-100 pt-3">
                        <div>
                            <span class="text-xs text-slate-500 block font-medium">Combo Package Rate</span>
                            @if($combo->sale_price_per_kg)
                                <span class="text-sm text-slate-400 line-through mr-1">₹{{ number_format($combo->price_per_kg, 0) }}</span>
                                <span class="text-2xl font-black text-brand-navy">₹{{ number_format($combo->sale_price_per_kg, 0) }}</span>
                            @else
                                <span class="text-2xl font-black text-brand-navy">₹{{ number_format($combo->price_per_kg, 0) }}</span>
                            @endif
                        </div>

                        <span class="text-xs text-emerald-700 font-bold bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                            Pre-Cleaned & Prepped
                        </span>
                    </div>
                </div>

                <a href="{{ route('product.detail', $combo->slug) }}" class="w-full btn-brand-blue py-3.5 rounded-xl font-extrabold text-xs text-center block shadow-md">
                    Select Cutting Style & Order Combo <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
