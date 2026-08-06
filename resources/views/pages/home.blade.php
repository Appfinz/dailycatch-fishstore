@extends('layouts.app')

@section('title', 'Daily Catch Fish Shop - Fresh Fish Delivered Today')

@section('content')

<!-- 1. HERO BANNER SECTION (Exact Match with New Reference Image) -->
<section class="relative bg-no-repeat bg-cover bg-center w-full overflow-hidden border-b border-slate-200"
         style="background-image: url('{{ asset('images/user_hero_bg.jpg') }}'); background-size: 100% 100%;">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20 lg:py-24 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
        
        <!-- Left Text Overlay Content -->
        <div class="lg:col-span-7 space-y-5 text-center lg:text-left">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-brand-navy tracking-tight leading-none font-display uppercase">
                FRESH FISH<br>
                <span class="text-brand-blue">DELIVERED TODAY</span>
            </h1>

            <p class="text-slate-800 text-sm sm:text-base font-extrabold">
                Order before 3PM and get it today
            </p>

            <!-- 3 Feature Badges in a horizontal row -->
            <div class="flex items-center justify-center lg:justify-start gap-4 flex-wrap text-xs font-bold text-slate-800 pt-1">
                <div class="flex items-center gap-2 bg-white/80 backdrop-blur px-3 py-1.5 rounded-full border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-anchor text-brand-blue"></i>
                    <span>Fresh Morning Catch</span>
                </div>
                <div class="flex items-center gap-2 bg-white/80 backdrop-blur px-3 py-1.5 rounded-full border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-shield-halved text-brand-blue"></i>
                    <span>Hygienically Processed</span>
                </div>
                <div class="flex items-center gap-2 bg-white/80 backdrop-blur px-3 py-1.5 rounded-full border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-box text-brand-blue"></i>
                    <span>Delivered in Ice Box</span>
                </div>
            </div>

            <!-- Two CTA Buttons -->
            <div class="pt-3 flex flex-wrap items-center justify-center lg:justify-start gap-4">
                <a href="{{ route('catalog') }}" class="bg-brand-navy hover:bg-slate-900 text-white inline-flex items-center gap-2.5 px-7 py-3.5 rounded-xl font-black text-xs tracking-wider shadow-xl uppercase">
                    <span>SHOP FRESH FISH</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
                <a href="{{ route('catalog') }}" class="bg-white/90 hover:bg-white text-brand-navy border border-slate-300 inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl font-black text-xs tracking-wider shadow-sm uppercase">
                    <span>EXPLORE CATEGORIES</span>
                    <i class="fa-solid fa-arrow-right text-xs text-brand-blue"></i>
                </a>
            </div>
        </div>

    </div>

    <!-- Bottom Floating Stat Badges Bar (Exact Reference Match) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 pt-4">
        <div class="bg-white/95 backdrop-blur rounded-2xl border border-slate-200 shadow-lg p-4 grid grid-cols-2 lg:grid-cols-4 gap-4 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
            
            <div class="flex items-center justify-center gap-3 p-2">
                <div class="w-10 h-10 rounded-xl bg-brand-lightblue text-brand-blue flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-box-archive"></i>
                </div>
                <div>
                    <span class="font-black text-sm text-brand-navy block leading-none">200+</span>
                    <span class="text-[10px] font-semibold text-slate-500">Products</span>
                </div>
            </div>

            <div class="flex items-center justify-center gap-3 p-2 pt-3 lg:pt-2">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <span class="font-black text-sm text-brand-navy block leading-none">4.9 ★</span>
                    <span class="text-[10px] font-semibold text-slate-500">25,000+ Orders</span>
                </div>
            </div>

            <div class="flex items-center justify-center gap-3 p-2 pt-3 lg:pt-2">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <span class="font-black text-sm text-brand-navy block leading-none">30 Min</span>
                    <span class="text-[10px] font-semibold text-slate-500">Express Delivery</span>
                </div>
            </div>

            <div class="flex items-center justify-center gap-3 p-2 pt-3 lg:pt-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <span class="font-black text-sm text-brand-navy block leading-none">100%</span>
                    <span class="text-[10px] font-semibold text-slate-500">Fresh Guarantee</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- HOMEPAGE 3KM LOCATION SERVICEABILITY CHECKER -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-brand-lightblue text-brand-blue flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <h4 class="font-black text-sm text-brand-navy font-display uppercase tracking-tight">Check Express Delivery Serviceability</h4>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">3KM Radius Store: 22g, Thiruvalluvar Street, East Tambaram, Chennai</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 w-full md:w-auto">
                <input type="text" id="homePincodeInput" placeholder="Enter Pincode or City (e.g. Delhi or 600059)" 
                       class="bg-slate-50 border border-slate-300 text-xs font-bold text-brand-navy rounded-xl px-4 py-3 focus:outline-none focus:border-brand-blue w-full md:w-72">
                <button type="button" onclick="checkHomePincode()" class="btn-brand-blue px-5 py-3 rounded-xl text-xs font-extrabold shrink-0 shadow">
                    Check Service
                </button>
            </div>
        </div>

        <div id="homeLocationResult" class="hidden p-4 rounded-2xl text-xs font-bold leading-relaxed transition-all"></div>
    </div>
</section>

<!-- 2. SHOP BY CATEGORY SECTION (Exact Reference Match) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-base sm:text-lg font-black text-brand-navy font-display uppercase tracking-tight">SHOP BY CATEGORY</h2>
        <a href="{{ route('catalog') }}" class="text-xs font-bold text-brand-blue hover:underline flex items-center gap-1">
            View All Categories <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Fish Card -->
        <a href="{{ route('catalog', ['category' => 'sea-fish']) }}" class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-sm hover:shadow-md transition-all relative group flex flex-col justify-between">
            <div class="aspect-square rounded-xl bg-slate-50 overflow-hidden mb-3 p-1 border border-slate-100 flex items-center justify-center">
                <img src="{{ asset('images/fish_category.png') }}" class="w-full h-full object-cover rounded-lg group-hover:scale-105 transition-transform">
            </div>
            <div class="text-left flex items-center justify-between">
                <div>
                    <h3 class="font-black text-xs text-brand-navy group-hover:text-brand-blue">Fish</h3>
                    <span class="text-[10px] text-slate-400 font-medium">45+ Products</span>
                </div>
                <div class="w-6 h-6 rounded-full bg-slate-100 group-hover:bg-brand-blue group-hover:text-white text-slate-500 flex items-center justify-center text-[10px] transition-colors">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </a>

        <!-- Prawn Card -->
        <a href="{{ route('catalog', ['category' => 'prawns']) }}" class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-sm hover:shadow-md transition-all relative group flex flex-col justify-between">
            <div class="aspect-square rounded-xl bg-slate-50 overflow-hidden mb-3 p-1 border border-slate-100 flex items-center justify-center">
                <img src="{{ asset('images/prawn_category.png') }}" class="w-full h-full object-cover rounded-lg group-hover:scale-105 transition-transform">
            </div>
            <div class="text-left flex items-center justify-between">
                <div>
                    <h3 class="font-black text-xs text-brand-navy group-hover:text-brand-blue">Prawn</h3>
                    <span class="text-[10px] text-slate-400 font-medium">25+ Products</span>
                </div>
                <div class="w-6 h-6 rounded-full bg-slate-100 group-hover:bg-brand-blue group-hover:text-white text-slate-500 flex items-center justify-center text-[10px] transition-colors">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </a>

        <!-- Crab Card -->
        <a href="{{ route('catalog', ['category' => 'crab-squid']) }}" class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-sm hover:shadow-md transition-all relative group flex flex-col justify-between">
            <div class="aspect-square rounded-xl bg-slate-50 overflow-hidden mb-3 p-1 border border-slate-100 flex items-center justify-center">
                <img src="{{ asset('images/crab_category.png') }}" class="w-full h-full object-cover rounded-lg group-hover:scale-105 transition-transform">
            </div>
            <div class="text-left flex items-center justify-between">
                <div>
                    <h3 class="font-black text-xs text-brand-navy group-hover:text-brand-blue">Crab</h3>
                    <span class="text-[10px] text-slate-400 font-medium">15+ Products</span>
                </div>
                <div class="w-6 h-6 rounded-full bg-slate-100 group-hover:bg-brand-blue group-hover:text-white text-slate-500 flex items-center justify-center text-[10px] transition-colors">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </a>

        <!-- Chicken (Coming Soon) -->
        <div class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-sm relative flex flex-col justify-between opacity-75">
            <div class="aspect-square rounded-xl bg-slate-100 overflow-hidden mb-3 p-1 border border-slate-100 flex items-center justify-center">
                <img src="{{ asset('images/fish_category.png') }}" class="w-full h-full object-cover rounded-lg">
            </div>
            <div class="text-left flex items-center justify-between">
                <div>
                    <h3 class="font-black text-xs text-slate-700">Chicken</h3>
                    <span class="text-[9px] text-amber-600 font-extrabold bg-amber-50 px-1.5 py-0.5 rounded">Coming Soon</span>
                </div>
                <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[10px]">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </div>

        <!-- Mutton (Coming Soon) -->
        <div class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-sm relative flex flex-col justify-between opacity-75">
            <div class="aspect-square rounded-xl bg-slate-100 overflow-hidden mb-3 p-1 border border-slate-100 flex items-center justify-center">
                <img src="{{ asset('images/fish_category.png') }}" class="w-full h-full object-cover rounded-lg">
            </div>
            <div class="text-left flex items-center justify-between">
                <div>
                    <h3 class="font-black text-xs text-slate-700">Mutton</h3>
                    <span class="text-[9px] text-amber-600 font-extrabold bg-amber-50 px-1.5 py-0.5 rounded">Coming Soon</span>
                </div>
                <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[10px]">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </div>

        <!-- Other Seafood -->
        <a href="{{ route('catalog') }}" class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-sm hover:shadow-md transition-all relative group flex flex-col justify-between">
            <div class="aspect-square rounded-xl bg-slate-50 overflow-hidden mb-3 p-1 border border-slate-100 flex items-center justify-center">
                <img src="{{ asset('images/other_seafood.png') }}" class="w-full h-full object-cover rounded-lg group-hover:scale-105 transition-transform">
            </div>
            <div class="text-left flex items-center justify-between">
                <div>
                    <h3 class="font-black text-xs text-brand-navy group-hover:text-brand-blue">Other Seafood</h3>
                    <span class="text-[9px] text-amber-600 font-extrabold bg-amber-50 px-1.5 py-0.5 rounded">Coming Soon</span>
                </div>
                <div class="w-6 h-6 rounded-full bg-slate-100 group-hover:bg-brand-blue group-hover:text-white text-slate-500 flex items-center justify-center text-[10px] transition-colors">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </a>
    </div>
</section>

<!-- 3. WHY 20,000+ FAMILIES CHOOSE DAILY CATCH SECTION (Exact Reference Match) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-[#F0F6FD] border border-blue-100 rounded-3xl p-6 sm:p-8 space-y-6">
        <h2 class="text-center text-base sm:text-lg font-black text-brand-navy font-display uppercase tracking-tight">
            WHY 20,000+ FAMILIES CHOOSE DAILY CATCH
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-white text-brand-blue flex items-center justify-center font-bold text-lg mx-auto shadow-sm">
                    <i class="fa-solid fa-sun"></i>
                </div>
                <h4 class="font-extrabold text-xs text-brand-navy">Fresh Every Morning</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">We source directly from trusted fishermen every morning.</p>
            </div>

            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-white text-brand-blue flex items-center justify-center font-bold text-lg mx-auto shadow-sm">
                    <i class="fa-solid fa-scissors"></i>
                </div>
                <h4 class="font-extrabold text-xs text-brand-navy">Expertly Cleaned</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Hygienically cleaned and cut by trained professionals.</p>
            </div>

            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-white text-brand-blue flex items-center justify-center font-bold text-lg mx-auto shadow-sm">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <h4 class="font-extrabold text-xs text-brand-navy">No Frozen Stock</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">We never sell frozen fish. Only 100% fresh seafood every day.</p>
            </div>

            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-white text-brand-blue flex items-center justify-center font-bold text-lg mx-auto shadow-sm">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h4 class="font-extrabold text-xs text-brand-navy">Delivered in Ice Box</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Your order is packed with care and delivered in an ice box.</p>
            </div>

            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-white text-brand-blue flex items-center justify-center font-bold text-lg mx-auto shadow-sm">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h4 class="font-extrabold text-xs text-brand-navy">On-Time Delivery</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Express delivery within 30 minutes in select locations.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. TODAY'S FRESH CATCH SECTION (Exact Reference Match) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <h2 class="text-base sm:text-lg font-black text-brand-navy font-display uppercase tracking-tight">TODAY'S FRESH CATCH</h2>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('catalog') }}" class="text-xs font-bold text-brand-blue hover:underline">View All &rarr;</a>
            <div class="flex items-center gap-1">
                <button class="w-7 h-7 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 hover:border-brand-blue hover:text-brand-blue text-xs"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="w-7 h-7 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 hover:border-brand-blue hover:text-brand-blue text-xs"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        @foreach($featuredProducts->take(6) as $product)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden card-shadow flex flex-col justify-between">
                <div>
                    <!-- Image with FRESH TODAY Badge -->
                    <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded shadow">
                            FRESH TODAY
                        </span>
                    </div>

                    <div class="p-3 space-y-1.5">
                        <h3 class="font-extrabold text-xs text-brand-navy truncate">{{ $product->name }}</h3>
                        
                        <!-- Rating Stars -->
                        <div class="flex items-center gap-1 text-[10px] text-amber-500 font-bold">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <span class="text-slate-400 font-normal">({{ rand(45, 180) }})</span>
                        </div>

                        <!-- Price -->
                        <div class="pt-1">
                            <span class="font-black text-xs text-brand-navy">₹{{ number_format($product->sale_price_per_kg ?: $product->price_per_kg, 0) }} <span class="text-[9px] text-slate-500 font-normal">/ Kg</span></span>
                        </div>

                        <!-- Weight Dropdown & Add Button Row -->
                        <div class="pt-1 flex items-center justify-between gap-2">
                            <select class="bg-slate-50 border border-slate-200 text-[10px] font-bold text-slate-700 rounded-lg px-1.5 py-1 focus:outline-none focus:border-brand-blue">
                                <option value="1">1 Kg</option>
                                <option value="0.5">500 g</option>
                                <option value="1.5">1.5 Kg</option>
                                <option value="2">2 Kg</option>
                            </select>

                            <a href="{{ route('product.detail', $product->slug) }}" class="btn-brand-blue px-3 py-1 rounded-lg text-xs font-bold shadow-sm shrink-0">
                                + Add
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- 5. LIMITED TIME OFFER BANNER WITH COUNTDOWN TIMER (Exact Reference Match) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-gradient-to-r from-brand-navy via-brand-navy to-[#0D3168] rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border border-white/10">
        
        <div class="space-y-3 text-center md:text-left z-10">
            <span class="bg-amber-400 text-slate-950 text-[10px] font-black uppercase px-3 py-1 rounded-md tracking-wider">
                LIMITED TIME OFFER
            </span>
            <h3 class="text-3xl sm:text-4xl font-black font-display tracking-tight leading-none text-white">
                FLAT ₹150 OFF
            </h3>
            <p class="text-slate-300 text-xs sm:text-sm font-medium">
                on orders above ₹999
            </p>

            <div class="pt-2 flex flex-wrap items-center gap-3 justify-center md:justify-start">
                <span class="bg-white/10 border border-white/20 text-white font-mono font-bold text-xs px-3.5 py-2 rounded-xl">
                    Use Code: <strong class="text-amber-300">CATCH150</strong>
                </span>
                <a href="{{ route('catalog') }}" class="bg-white hover:bg-slate-100 text-brand-navy px-6 py-2.5 rounded-full font-black text-xs transition-colors shadow">
                    ORDER NOW &rarr;
                </a>
            </div>
        </div>

        <!-- Countdown Timer Box (Exact Reference Match) -->
        <div class="relative z-10 flex flex-col items-center md:items-end gap-2">
            <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">OFFER ENDS IN</span>
            <div class="flex items-center gap-2">
                <div class="bg-slate-950/80 border border-slate-700/80 rounded-xl px-3 py-2 text-center min-w-[50px]">
                    <span class="font-black text-xl text-white block leading-none">02</span>
                    <span class="text-[8px] font-bold text-slate-400 uppercase">HRS</span>
                </div>
                <span class="font-black text-white">:</span>
                <div class="bg-slate-950/80 border border-slate-700/80 rounded-xl px-3 py-2 text-center min-w-[50px]">
                    <span class="font-black text-xl text-white block leading-none">36</span>
                    <span class="text-[8px] font-bold text-slate-400 uppercase">MIN</span>
                </div>
                <span class="font-black text-white">:</span>
                <div class="bg-slate-950/80 border border-slate-700/80 rounded-xl px-3 py-2 text-center min-w-[50px]">
                    <span class="font-black text-xl text-white block leading-none" id="offerSec">54</span>
                    <span class="text-[8px] font-bold text-slate-400 uppercase">SEC</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. BEST SELLERS SECTION (Exact Reference Match) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-base sm:text-lg font-black text-brand-navy font-display uppercase tracking-tight">BEST SELLERS</h2>
        <a href="{{ route('catalog') }}" class="text-xs font-bold text-brand-blue hover:underline">View All &rarr;</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach($featuredProducts->take(5) as $product)
            <a href="{{ route('product.detail', $product->slug) }}" class="bg-white border border-slate-200 rounded-2xl overflow-hidden card-shadow p-3 block group">
                <div class="aspect-[4/3] bg-slate-100 rounded-xl overflow-hidden mb-3">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                </div>
                <h3 class="font-extrabold text-xs text-brand-navy truncate group-hover:text-brand-blue">{{ $product->name }}</h3>
                <div class="flex items-center gap-1 text-[10px] text-amber-500 font-bold my-1">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <span class="text-slate-400 font-normal">({{ rand(60, 150) }})</span>
                </div>
                <span class="font-black text-xs text-brand-navy block">₹{{ number_format($product->sale_price_per_kg ?: $product->price_per_kg, 0) }} <span class="text-[9px] text-slate-500 font-normal">/ Kg</span></span>
            </a>
        @endforeach
    </div>
</section>

<!-- 7. OUR FRESHNESS JOURNEY SECTION (Exact Reference Match Horizontal Stepper) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-gradient-to-r from-blue-50 via-slate-50 to-blue-50 border border-blue-100 rounded-3xl p-6 sm:p-8 space-y-6">
        <h2 class="text-center text-base sm:text-lg font-black text-brand-navy font-display uppercase tracking-tight">
            OUR FRESHNESS JOURNEY
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-6 relative">
            
            <div class="text-center space-y-2 relative">
                <div class="w-12 h-12 rounded-full bg-brand-lightblue text-brand-blue flex items-center justify-center font-bold text-lg mx-auto shadow-sm border border-blue-200">
                    <i class="fa-solid fa-ship"></i>
                </div>
                <h4 class="font-extrabold text-xs text-brand-navy">1. Caught Daily</h4>
                <p class="text-[10px] text-slate-500 font-medium">Sourced fresh from trusted fishermen</p>
            </div>

            <div class="text-center space-y-2 relative">
                <div class="w-12 h-12 rounded-full bg-brand-lightblue text-brand-blue flex items-center justify-center font-bold text-lg mx-auto shadow-sm border border-blue-200">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <h4 class="font-extrabold text-xs text-brand-navy">2. Quality Check</h4>
                <p class="text-[10px] text-slate-500 font-medium">Every catch is checked for quality</p>
            </div>

            <div class="text-center space-y-2 relative">
                <div class="w-12 h-12 rounded-full bg-brand-lightblue text-brand-blue flex items-center justify-center font-bold text-lg mx-auto shadow-sm border border-blue-200">
                    <i class="fa-solid fa-scissors"></i>
                </div>
                <h4 class="font-extrabold text-xs text-brand-navy">3. Hygienic Cleaning</h4>
                <p class="text-[10px] text-slate-500 font-medium">Professionally cleaned & cut</p>
            </div>

            <div class="text-center space-y-2 relative">
                <div class="w-12 h-12 rounded-full bg-brand-lightblue text-brand-blue flex items-center justify-center font-bold text-lg mx-auto shadow-sm border border-blue-200">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h4 class="font-extrabold text-xs text-brand-navy">4. Ice Box Packing</h4>
                <p class="text-[10px] text-slate-500 font-medium">Packed in ice box to retain freshness</p>
            </div>

            <div class="text-center space-y-2 relative">
                <div class="w-12 h-12 rounded-full bg-brand-lightblue text-brand-blue flex items-center justify-center font-bold text-lg mx-auto shadow-sm border border-blue-200">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h4 class="font-extrabold text-xs text-brand-navy">5. Delivered to You</h4>
                <p class="text-[10px] text-slate-500 font-medium">Fast delivery to your doorstep</p>
            </div>

        </div>
    </div>
</section>

<!-- 8. RECIPES & TIPS SECTION (Uniform 3-Card Dark Navy Grid) -->
@if($recipes->count() > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-base sm:text-lg font-black text-brand-navy font-display uppercase tracking-tight">RECIPES & TIPS</h2>
        <a href="{{ route('recipes') }}" class="text-xs font-bold text-brand-blue hover:underline">View All &rarr;</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($recipes->take(3) as $recipe)
            <div class="bg-brand-navy text-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 flex flex-col justify-between h-full border border-white/10 group">
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-900">
                    <img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-3 left-3 bg-white text-brand-navy text-[10px] font-black uppercase px-2.5 py-1 rounded-md shadow tracking-wider">
                        {{ str_contains(strtolower($recipe->title), 'tip') ? 'TIPS' : 'RECIPE' }}
                    </span>
                </div>
                
                <div class="p-6 space-y-3 flex flex-col justify-between flex-grow">
                    <div class="space-y-2">
                        <h3 class="font-black text-lg text-white font-display leading-tight group-hover:text-sky-300 transition-colors">
                            {{ $recipe->title }}
                        </h3>
                        <p class="text-xs text-slate-300 leading-relaxed line-clamp-3">
                            {{ $recipe->short_desc }}
                        </p>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('recipe.detail', $recipe->slug) }}" class="text-xs font-bold text-sky-300 hover:text-white inline-flex items-center gap-1.5 transition-colors">
                            <span>Read Details</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

@push('scripts')
<script>
    async function checkHomePincode() {
        const val = document.getElementById('homePincodeInput').value;
        if (!val.trim()) return;

        const resBox = document.getElementById('homeLocationResult');
        resBox.className = "mt-3 p-4 rounded-2xl text-xs font-bold bg-blue-50 text-brand-navy border border-blue-200 block";
        resBox.innerText = "Checking serviceability for " + val + "...";
        resBox.classList.remove('hidden');

        try {
            const geoRes = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(val)}`);
            const geoData = await geoRes.json();

            if (geoData && geoData.length > 0) {
                let lat = parseFloat(geoData[0].lat);
                let lng = parseFloat(geoData[0].lon);

                const valRes = await fetch('/api/v1/validate-location', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng })
                });
                const valData = await valRes.json();

                if (valData.is_within_radius) {
                    resBox.className = "mt-3 p-4 rounded-2xl text-xs font-bold bg-emerald-50 text-emerald-900 border border-emerald-200 flex items-center gap-2 block";
                    resBox.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i> <span>Great News! We deliver home express orders to ${val}! (${valData.distance_km} KM from store)</span>`;
                } else {
                    resBox.className = "mt-3 p-4 rounded-2xl text-xs font-bold bg-rose-50 text-rose-900 border border-rose-200 flex items-center justify-between gap-2 block";
                    resBox.innerHTML = `
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base shrink-0"></i> 
                            <span>We do not serve home delivery in ${val}. Your area is ${valData.distance_km} KM away from our store (3.0 KM Limit). You can still pick up from our East Tambaram shop!</span>
                        </div>
                        <a href="/locations" class="bg-rose-600 text-white px-3 py-1.5 rounded-lg text-[11px] font-extrabold hover:bg-rose-700 shrink-0">Store Info</a>
                    `;
                }
            } else {
                resBox.className = "mt-3 p-4 rounded-2xl text-xs font-bold bg-rose-50 text-rose-900 border border-rose-200 block";
                resBox.innerText = "Area or Pincode not found. Please try entering city or full area name.";
            }
        } catch (e) { console.error(e); }
    }

    // Countdown Timer Logic
    setInterval(() => {
        const secEl = document.getElementById('offerSec');
        if (secEl) {
            let sec = parseInt(secEl.innerText);
            if (sec > 0) secEl.innerText = (sec - 1).toString().padStart(2, '0');
            else secEl.innerText = '59';
        }
    }, 1000);
</script>
@endpush
@endsection
