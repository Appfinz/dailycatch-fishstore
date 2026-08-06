@extends('layouts.app')

@section('title', 'Authentic Seafood Recipes - Daily Catch Fish Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <span class="bg-brand-blue/10 text-brand-blue text-xs font-black px-4 py-1.5 rounded-full border border-brand-blue/20 uppercase tracking-wider">
            Traditional Tamil Nadu Cuisine
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-brand-navy font-display">Seafood Cooking & Recipe Hub</h1>
        <p class="text-xs sm:text-sm text-slate-500 font-semibold">Master authentic Chettinad fish curries and spicy tawa fries with step-by-step recipes and 1-click fish ordering!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($recipes as $recipe)
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 flex flex-col justify-between hover:shadow-lg transition-all shadow-sm">
                <div>
                    <div class="relative aspect-[16/9] bg-slate-100 overflow-hidden">
                        <img src="{{ $recipe->image }}" class="w-full h-full object-cover">
                        <span class="absolute top-3 right-3 bg-brand-navy text-white text-[10px] font-extrabold px-3 py-1 rounded-full shadow">
                            {{ $recipe->difficulty }}
                        </span>
                    </div>

                    <div class="p-6 space-y-3">
                        <div class="flex items-center gap-3 text-[11px] font-bold text-slate-500">
                            <span><i class="fa-solid fa-clock text-brand-blue"></i> Prep: {{ $recipe->prep_time }}</span>
                            <span>•</span>
                            <span><i class="fa-solid fa-fire text-amber-500"></i> Cook: {{ $recipe->cook_time }}</span>
                            <span>•</span>
                            <span><i class="fa-solid fa-user-group text-emerald-600"></i> {{ $recipe->servings }}</span>
                        </div>

                        <h3 class="text-lg font-extrabold text-brand-navy font-display">{{ $recipe->title }}</h3>
                        <p class="text-xs text-brand-blue font-bold">{{ $recipe->tamil_title }}</p>
                        
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed font-medium">
                            {{ $recipe->short_desc }}
                        </p>
                    </div>
                </div>

                <div class="p-6 pt-0">
                    <a href="{{ route('recipe.detail', $recipe->slug) }}" class="w-full btn-brand-blue py-3 rounded-xl font-extrabold text-xs text-center block shadow">
                        View Full Recipe & Order Fish <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
