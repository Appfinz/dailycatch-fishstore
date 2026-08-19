@extends('layouts.app')

@section('title', $product->name . ' - Daily Catch Fish Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        
        <!-- Left Column: Product Image Gallery & Badges -->
        <div class="lg:col-span-6 space-y-4">
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm relative aspect-[4/3]">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                <span class="absolute top-4 left-4 bg-brand-navy text-white text-xs font-black uppercase px-3.5 py-1.5 rounded-full shadow">
                    Fresh Harbor Catch
                </span>
                @if($product->sale_price_per_kg)
                    <span class="absolute top-4 right-4 bg-emerald-600 text-white text-xs font-black uppercase px-3 py-1 rounded-full shadow">
                        Save Deal
                    </span>
                @endif
            </div>

            <!-- Transparency & Weight Guarantee Banner (Configurable) -->
            @if($product->has_weight_variation)
                <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200 flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-base shrink-0">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-xs text-amber-950 uppercase tracking-wider">Fish Weight Transparency Guarantee</h4>
                        <p class="text-xs text-amber-900 font-medium leading-relaxed mt-0.5">
                            Medium/Large fish is selected & weighed live in our store. Final bill amount will be calculated using actual whole fish weight.
                        </p>
                    </div>
                </div>
            @else
                <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-200 flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-base shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-xs text-emerald-950 uppercase tracking-wider">Exact Weight Guaranteed</h4>
                        <p class="text-xs text-emerald-900 font-medium leading-relaxed mt-0.5">
                            Small fish supplied in the exact weight requested with no price variation.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Product Specs & Options -->
        <div class="lg:col-span-6 space-y-6">
            <div>
                <span class="bg-brand-blue/10 text-brand-blue text-[11px] font-black px-3 py-1 rounded-full border border-brand-blue/20 uppercase tracking-wider">
                    {{ $product->category ? $product->category->name : 'Sea Fish' }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-navy mt-2 font-display">{{ $product->name }}</h1>
                <p class="text-sm text-brand-blue font-bold mt-1">{{ $product->tamil_name }}</p>
                
                <div class="mt-4 flex items-baseline gap-3">
                    @if($product->sale_price_per_kg)
                        <span class="text-3xl font-black text-brand-navy">₹{{ number_format($product->sale_price_per_kg, 0) }}</span>
                        <span class="text-sm text-slate-400 line-through font-bold">₹{{ number_format($product->price_per_kg, 0) }}</span>
                        <span class="text-xs text-emerald-600 font-bold bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">Per KG Rate</span>
                    @else
                        <span class="text-3xl font-black text-brand-navy">₹{{ number_format($product->price_per_kg, 0) }}</span>
                        <span class="text-xs text-slate-500 font-bold">Per KG Rate</span>
                    @endif
                </div>

                <p class="text-xs text-slate-600 mt-3 leading-relaxed font-medium">
                    {{ $product->description ?: 'Freshly sourced daily from Chennai harbor landings. Handpicked, pre-cleaned, and delivered fresh in hygienic food-grade packaging.' }}
                </p>
            </div>

            <!-- 1. Select Weight Quantity (KG) -->
            <div class="space-y-3 pt-2">
                <label class="block text-xs font-extrabold text-brand-navy uppercase tracking-wider">
                    Step 1: Select Weight Quantity (KG)
                </label>
                
                <div class="grid grid-cols-3 sm:grid-cols-5 gap-2.5">
                    @foreach([0.25, 0.5, 1.0, 1.5, 2.0] as $qty)
                        <button type="button" 
                                onclick="selectQty({{ $qty }})" 
                                class="qty-btn border-2 rounded-xl py-2.5 text-xs font-extrabold text-slate-700 hover:border-brand-blue hover:text-brand-blue transition-all flex flex-col items-center justify-center {{ $qty === 0.5 ? 'active border-brand-blue bg-blue-50 text-brand-blue' : 'border-slate-200 bg-white' }}"
                                data-qty="{{ $qty }}">
                            <span>{{ $qty >= 1 ? $qty . ' kg' : ($qty * 1000) . 'g' }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Custom Weight Input -->
                <div class="flex items-center gap-3 pt-1">
                    <span class="text-xs text-slate-500 font-semibold">Custom Weight:</span>
                    <div class="flex items-center border border-slate-300 rounded-xl bg-slate-50 overflow-hidden">
                        <button type="button" onclick="adjustQty(-0.25)" class="px-3 py-1.5 text-slate-600 hover:bg-slate-200 font-black text-sm">-</button>
                        <input type="number" id="qtyInput" value="0.5" step="0.25" min="0.25" max="10" 
                               onchange="calculateEstimateTotal()" 
                               class="w-16 text-center text-xs font-bold text-brand-navy bg-transparent focus:outline-none">
                        <button type="button" onclick="adjustQty(0.25)" class="px-3 py-1.5 text-slate-600 hover:bg-slate-200 font-black text-sm">+</button>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">KG</span>
                </div>
            </div>

            <!-- 2. Select Custom Cutting Style (Larger Images & High Visibility) -->
            <div class="space-y-3 pt-2">
                <label class="block text-xs font-extrabold text-brand-navy uppercase tracking-wider flex items-center justify-between">
                    <span>Step 2: Choose Cutting & Cleaning Style</span>
                    <span class="text-[10px] text-emerald-600 font-bold lowercase">prepped & cleaned free</span>
                </label>

                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    @foreach($product->cuttingStyles as $index => $cs)
                        @php
                            $charge = ($cs->pivot && $cs->pivot->additional_charge !== null) ? (float)$cs->pivot->additional_charge : (float)$cs->additional_charge;
                            $cutPhoto = ($cs->pivot && !empty($cs->pivot->image)) 
                                ? $cs->pivot->image 
                                : ($cs->image ?: ($product->image ?: 'https://images.unsplash.com/photo-1510130318145-ad4f04e849a7?auto=format&fit=crop&w=600&q=80'));
                        @endphp
                        <label onclick="selectCuttingStyle({{ $cs->id }}, {{ $charge }})" 
                               class="cutting-card relative border-2 rounded-2xl p-3 sm:p-4 cursor-pointer transition-all flex flex-col justify-between hover:border-brand-blue bg-white shadow-sm hover:shadow-md {{ $index === 0 ? 'border-brand-blue bg-blue-50/40 ring-4 ring-brand-blue/20' : 'border-slate-200' }}"
                               data-cs-id="{{ $cs->id }}">
                            <input type="radio" name="cutting_style" value="{{ $cs->id }}" class="hidden" {{ $index === 0 ? 'checked' : '' }}>
                            
                            <div>
                                <!-- Enlarged Image for high visibility -->
                                <div class="w-full h-32 sm:h-36 rounded-xl overflow-hidden mb-3 border border-slate-200 bg-slate-100 relative">
                                    <img src="{{ $cutPhoto }}" 
                                         alt="{{ $cs->name }}" 
                                         class="w-full h-full object-cover">
                                    @if($charge > 0)
                                        <span class="absolute top-2 right-2 bg-amber-500 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow">
                                            +₹{{ number_format($charge, 0) }}/kg
                                        </span>
                                    @else
                                        <span class="absolute top-2 right-2 bg-emerald-600 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow">
                                            FREE
                                        </span>
                                    @endif
                                </div>
                                <h4 class="font-extrabold text-xs sm:text-sm text-brand-navy leading-tight">{{ $cs->name }}</h4>
                                @if($cs->tamil_name)
                                    <p class="text-[11px] text-brand-blue font-bold mt-0.5">{{ $cs->tamil_name }}</p>
                                @endif
                            </div>

                            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">Extra Charge</span>
                                <span class="font-black {{ $charge > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                    {{ $charge > 0 ? '+₹' . number_format($charge, 0) : 'FREE' }}
                                </span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Desktop Estimated Calculation Footer & Add to Cart -->
            <div class="border-t border-slate-200 pt-6 space-y-4 hidden md:block">
                <div class="flex items-center justify-between bg-brand-navy text-white p-5 rounded-2xl shadow-lg">
                    <div>
                        <span class="text-xs text-slate-300 block font-semibold">Estimated Amount</span>
                        <div class="flex items-baseline gap-1">
                            <span id="estimatedTotalDisplay" class="text-2xl font-black text-white">₹0.00</span>
                            <span class="text-[11px] text-slate-300 font-medium">({{ $product->has_weight_variation ? 'Est. Bill' : 'Fixed Bill' }})</span>
                        </div>
                    </div>
                    
                    <button type="button" onclick="addToCartSubmit()" class="btn-brand-blue text-white px-8 py-3.5 rounded-xl font-extrabold text-xs tracking-wider shadow-xl flex items-center gap-2 uppercase">
                        <i class="fa-solid fa-cart-plus text-sm"></i>
                        <span>Add to Cart</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Mobile Fixed Sticky Bottom Action Bar -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-3.5 z-40 flex items-center justify-between shadow-2xl">
    <div>
        <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Est. Amount</span>
        <span id="mobileEstimatedTotalDisplay" class="text-lg font-black text-brand-navy">₹0.00</span>
    </div>

    <button type="button" onclick="addToCartSubmit()" class="btn-brand-blue text-white px-6 py-3 rounded-xl font-extrabold text-xs tracking-wider shadow-lg flex items-center gap-2 uppercase">
        <i class="fa-solid fa-cart-plus text-xs"></i>
        <span>Add to Basket</span>
    </button>
</div>

@push('scripts')
<script>
    let unitPrice = {{ $product->sale_price_per_kg ?: $product->price_per_kg }};
    let selectedQty = 0.5;
    @php
        $firstCs = $product->cuttingStyles->first();
        $firstCharge = $firstCs ? (($firstCs->pivot && $firstCs->pivot->additional_charge !== null) ? (float)$firstCs->pivot->additional_charge : (float)$firstCs->additional_charge) : 0;
    @endphp
    let selectedCsId = {{ $firstCs ? $firstCs->id : 1 }};
    let selectedCsCharge = {{ $firstCharge }};

    function selectQty(qty) {
        selectedQty = qty;
        document.getElementById('qtyInput').value = qty;
        
        document.querySelectorAll('.qty-btn').forEach(btn => {
            if (parseFloat(btn.dataset.qty) === qty) {
                btn.classList.add('active', 'border-brand-blue', 'bg-blue-50', 'text-brand-blue');
            } else {
                btn.classList.remove('active', 'border-brand-blue', 'bg-blue-50', 'text-brand-blue');
            }
        });

        calculateEstimateTotal();
    }

    function adjustQty(amount) {
        let input = document.getElementById('qtyInput');
        let current = parseFloat(input.value) || 0.5;
        let next = Math.max(0.25, Math.min(10.0, current + amount));
        input.value = next;
        selectedQty = next;
        calculateEstimateTotal();
    }

    function selectCuttingStyle(csId, charge) {
        selectedCsId = csId;
        selectedCsCharge = charge;

        document.querySelectorAll('.cutting-card').forEach(card => {
            if (parseInt(card.dataset.csId) === csId) {
                card.classList.add('border-brand-blue', 'bg-blue-50/40', 'ring-4', 'ring-brand-blue/20');
            } else {
                card.classList.remove('border-brand-blue', 'bg-blue-50/40', 'ring-4', 'ring-brand-blue/20');
            }
        });

        calculateEstimateTotal();
    }

    function calculateEstimateTotal() {
        let qty = parseFloat(document.getElementById('qtyInput').value) || 0.5;
        let total = (unitPrice * qty) + (selectedCsCharge * qty);
        const formatted = '₹' + total.toFixed(2);
        
        const deskDisp = document.getElementById('estimatedTotalDisplay');
        if (deskDisp) deskDisp.innerText = formatted;

        const mobDisp = document.getElementById('mobileEstimatedTotalDisplay');
        if (mobDisp) mobDisp.innerText = formatted;
    }

    async function addToCartSubmit() {
        const qty = parseFloat(document.getElementById('qtyInput').value) || 0.5;

        try {
            const res = await fetch('/api/v1/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    cutting_style_id: selectedCsId,
                    qty_kg: qty
                })
            });

            const data = await res.json();
            if (data.status === 'success') {
                openCartDrawer();
            } else {
                alert(data.message || 'Error adding to cart');
            }
        } catch (e) {
            console.error(e);
            alert('Failed to add to cart');
        }
    }

    calculateEstimateTotal();
</script>
@endpush
@endsection
