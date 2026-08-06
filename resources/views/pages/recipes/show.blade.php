@extends('layouts.app')

@section('title', $recipe->title . ' - Daily Catch Fish Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Breadcrumb -->
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-brand-blue">Home</a>
        <span class="mx-2 text-slate-300">/</span>
        <a href="{{ route('recipes') }}" class="hover:text-brand-blue">Seafood Recipes</a>
        <span class="mx-2 text-slate-300">/</span>
        <span class="text-brand-navy font-bold">{{ $recipe->title }}</span>
    </nav>

    <!-- Header Section -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-7 space-y-4">
            <span class="bg-brand-lightblue text-brand-blue text-xs font-black px-3.5 py-1.5 rounded-full border border-blue-200 uppercase tracking-wider">
                Authentic Recipe Guide
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-brand-navy font-display leading-tight">{{ $recipe->title }}</h1>
            @if($recipe->tamil_title)
                <p class="text-base font-extrabold text-brand-blue">{{ $recipe->tamil_title }}</p>
            @endif

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">{{ $recipe->short_desc }}</p>

            <div class="flex flex-wrap gap-3 text-xs font-bold text-slate-700 pt-2">
                <div class="bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-200">
                    <i class="fa-solid fa-clock text-brand-blue mr-1.5"></i> Prep: {{ $recipe->prep_time }}
                </div>
                <div class="bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-200">
                    <i class="fa-solid fa-fire text-amber-500 mr-1.5"></i> Cook: {{ $recipe->cook_time }}
                </div>
                <div class="bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-200">
                    <i class="fa-solid fa-user-group text-emerald-600 mr-1.5"></i> Serves: {{ $recipe->servings }}
                </div>
                <div class="bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-200">
                    <i class="fa-solid fa-gauge-high text-purple-600 mr-1.5"></i> Difficulty: {{ $recipe->difficulty }}
                </div>
            </div>
        </div>

        <div class="lg:col-span-5">
            <img src="{{ $recipe->image }}" class="w-full h-64 object-cover rounded-2xl border border-slate-200 shadow-md">
        </div>
    </div>

    <!-- Main Grid: Ingredients + Step-by-Step Instructions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Ingredients Sidebar & Linked Fish Card -->
        <div class="lg:col-span-5 space-y-6">
            
            @if($recipe->product)
                <!-- Linked Fish Callout Box -->
                <div class="bg-brand-lightblue/50 p-6 rounded-3xl border border-blue-200 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 text-brand-blue text-xs font-black uppercase tracking-wider">
                        <i class="fa-solid fa-fish"></i> Required Main Ingredient
                    </div>

                    <div class="flex items-center gap-4">
                        <img src="{{ $recipe->product->image }}" class="w-16 h-16 object-cover rounded-xl border border-slate-200">
                        <div>
                            <h3 class="font-extrabold text-sm text-brand-navy font-display">{{ $recipe->product->name }}</h3>
                            <p class="text-xs text-slate-500 font-bold">Price: ₹{{ number_format($recipe->product->sale_price_per_kg ?: $recipe->product->price_per_kg, 0) }}/kg</p>
                        </div>
                    </div>

                    <a href="{{ route('product.detail', $recipe->product->slug) }}" class="w-full btn-brand-blue py-3 rounded-xl font-black text-xs text-center block shadow">
                        Order {{ $recipe->product->name }} Now <i class="fa-solid fa-cart-plus ml-1"></i>
                    </a>
                </div>
            @endif

            <!-- Ingredients List Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-extrabold text-base text-brand-navy border-b border-slate-100 pb-3 flex items-center gap-2 font-display">
                    <i class="fa-solid fa-list-check text-brand-blue"></i> Ingredients List
                </h3>

                <div class="text-xs text-slate-700 space-y-2 whitespace-pre-line font-medium leading-relaxed">
                    {{ $recipe->ingredients }}
                </div>
            </div>

        </div>

        <!-- Cooking Instructions Column -->
        <div class="lg:col-span-7">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <h3 class="font-extrabold text-lg text-brand-navy border-b border-slate-100 pb-3 flex items-center gap-2 font-display">
                    <i class="fa-solid fa-utensils text-amber-500"></i> Step-by-Step Preparation Guide
                </h3>

                <div class="text-xs sm:text-sm text-slate-700 space-y-4 whitespace-pre-line leading-relaxed font-semibold">
                    {{ $recipe->instructions }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
