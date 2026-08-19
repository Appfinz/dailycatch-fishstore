@extends('layouts.app')

@section('title', 'Seafood Catalog - Daily Catch Fish Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
    
    <!-- Top Header & Search Summary -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <span class="text-xs font-bold text-brand-blue uppercase tracking-wider">Fresh Fish Market</span>
            <h1 class="text-2xl sm:text-3xl font-black text-brand-navy font-display">Full Seafood Catalog</h1>
            <p class="text-xs text-slate-500 mt-1 font-medium">Showing {{ $products->count() }} fresh sea catches, prawns & crabs</p>
        </div>

        <form method="GET" action="{{ route('catalog') }}" class="flex items-center gap-2 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search fish, prawns..." 
                   class="bg-white border border-slate-200 text-xs font-bold text-slate-800 rounded-full px-4 py-2.5 w-full md:w-64 focus:outline-none focus:border-brand-blue shadow-sm">
            <button type="submit" class="btn-brand-blue font-extrabold text-xs px-5 py-2.5 rounded-full shrink-0 shadow">Search</button>
        </form>
    </div>

    <!-- Mobile Horizontal Category Touch Pills (Visible on Mobile Only) -->
    <div class="lg:hidden flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
        <a href="{{ route('catalog') }}" class="px-4 py-2 rounded-full text-xs font-extrabold shrink-0 border {{ !request('category') || request('category') === 'all' ? 'bg-brand-navy text-white border-brand-navy' : 'bg-white text-slate-700 border-slate-200' }}">
            All Categories
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('catalog', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-full text-xs font-extrabold shrink-0 border {{ request('category') === $cat->slug ? 'bg-brand-navy text-white border-brand-navy' : 'bg-white text-slate-700 border-slate-200' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Main Grid: Filter Sidebar + Products Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
        
        <!-- Filter Sidebar -->
        <aside class="lg:col-span-3 space-y-6">
            <!-- Mobile Toggle Accordion Button -->
            <button type="button" onclick="document.getElementById('catalogFilterBox').classList.toggle('hidden')" class="lg:hidden w-full bg-white border border-slate-200 p-3.5 rounded-2xl font-bold text-xs text-brand-navy flex items-center justify-between shadow-sm">
                <span class="flex items-center gap-2"><i class="fa-solid fa-filter text-brand-blue"></i> Filter Catalog</span>
                <i class="fa-solid fa-chevron-down text-slate-400"></i>
            </button>

            <form id="catalogFilterBox" method="GET" action="{{ route('catalog') }}" class="hidden lg:block bg-white border border-slate-200 p-5 sm:p-6 rounded-3xl space-y-5 card-shadow">
                <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                    <h3 class="font-extrabold text-sm text-brand-navy flex items-center gap-2 font-display">
                        <i class="fa-solid fa-filter text-brand-blue"></i> Filter Catalog
                    </h3>
                    <a href="{{ route('catalog') }}" class="text-[11px] font-bold text-slate-400 hover:text-brand-blue">Reset All</a>
                </div>

                <!-- Category Filter -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Category</label>
                    <select name="category" class="w-full bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 rounded-xl px-3 py-2.5 focus:border-brand-blue">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bone Type Filter -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Bone Content</label>
                    <select name="bone_type" class="w-full bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 rounded-xl px-3 py-2.5 focus:border-brand-blue">
                        <option value="">All Bone Types</option>
                        <option value="single_bone" {{ request('bone_type') === 'single_bone' ? 'selected' : '' }}>Single Bone</option>
                        <option value="boneless" {{ request('bone_type') === 'boneless' ? 'selected' : '' }}>Boneless</option>
                        <option value="low_bone" {{ request('bone_type') === 'low_bone' ? 'selected' : '' }}>Low Bone</option>
                        <option value="multi_bone" {{ request('bone_type') === 'multi_bone' ? 'selected' : '' }}>Multi-Bone</option>
                    </select>
                </div>

                <!-- Cooking Preference Filter -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Ideal Cooking Style</label>
                    <select name="best_for" class="w-full bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 rounded-xl px-3 py-2.5 focus:border-brand-blue">
                        <option value="">All Cooking Styles</option>
                        <option value="fry" {{ request('best_for') === 'fry' ? 'selected' : '' }}>Tawa / Deep Fry</option>
                        <option value="curry" {{ request('best_for') === 'curry' ? 'selected' : '' }}>Fish Curry</option>
                        <option value="grill" {{ request('best_for') === 'grill' ? 'selected' : '' }}>Grill & Tandoori</option>
                    </select>
                </div>

                <button type="submit" class="w-full btn-brand-blue py-3 rounded-full font-black text-xs shadow">
                    Apply Filters
                </button>
            </form>
        </aside>

        <!-- Products Grid -->
        <main class="lg:col-span-9 space-y-6">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3.5 sm:gap-6">
                @forelse($products as $product)
                    <div class="bg-white border border-slate-200 rounded-2xl sm:rounded-3xl overflow-hidden card-shadow flex flex-col justify-between">
                        <div>
                            <!-- Image -->
                            <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden border-b border-slate-100">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[8px] sm:text-[9px] font-black uppercase px-2 py-0.5 rounded-full shadow">
                                    FRESH TODAY
                                </span>
                            </div>

                            <!-- Info -->
                            <div class="p-3 sm:p-4 space-y-1.5 sm:space-y-2">
                                <h3 class="font-extrabold text-xs sm:text-sm text-brand-navy line-clamp-1 font-display">{{ $product->name }}</h3>
                                
                                <div class="flex items-center gap-1 text-[9px] sm:text-[10px] text-amber-500 font-bold">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <span class="text-slate-400 font-normal">({{ rand(45, 180) }})</span>
                                </div>

                                <p class="text-[11px] sm:text-xs text-slate-500 line-clamp-2 leading-relaxed hidden sm:block">
                                    {{ $product->short_desc }}
                                </p>

                                <div class="pt-1 flex items-baseline justify-between">
                                    <span class="font-black text-sm sm:text-base text-brand-navy">₹{{ number_format($product->sale_price_per_kg ?: $product->price_per_kg, 0) }} <span class="text-[9px] sm:text-[10px] text-slate-500 font-normal">/ Kg</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 pt-0">
                            <a href="{{ route('product.detail', $product->slug) }}" class="w-full btn-brand-blue py-2 sm:py-2.5 rounded-full font-extrabold text-[11px] sm:text-xs text-center block shadow-sm">
                                Select Cut & Add
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white border border-slate-200 rounded-3xl">
                        <i class="fa-solid fa-fish text-5xl text-slate-300 mb-3"></i>
                        <h3 class="text-lg font-bold text-slate-700">No products match your filters</h3>
                        <a href="{{ route('catalog') }}" class="text-xs font-bold text-brand-blue hover:underline mt-2 inline-block">Clear All Filters</a>
                    </div>
                @endforelse
            </div>

        </main>

    </div>
</div>
@endsection
