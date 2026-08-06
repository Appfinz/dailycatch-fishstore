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

            <!-- Transparency & Weight Guarantee Banner -->
            <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200 flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-xs text-amber-950 uppercase tracking-wider">Fish Weight Transparency Guarantee</h4>
                    <p class="text-xs text-amber-900 font-medium leading-relaxed mt-0.5">
                        Fresh fish is selected & weighed live in our store. Final bill amount will be calculated using actual whole fish weight.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Column: Product Specs & Options -->
        <div class="lg:col-span-6 space-y-6">
            <div>
                <span class="bg-brand-blue/10 text-brand-blue text-[11px] font-black px-3 py-1 rounded-full border border-brand-blue/20 uppercase tracking-wider">
                    {{ $product->category ? $product->category->name : 'Sea Fish' }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-navy mt-2 font-display">{{ $product->name }}</h1>
                <p class="text-xs text-brand-blue font-bold mt-1 text-base">{{ $product->tamil_name }}</p>
                
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
                    {{ $product->description ?: 'Freshly sourced daily from Chennai harbor landings. Handpicked, pre-cleaned, and delivered in food-grade ice boxes.' }}
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

            <!-- 2. Select Custom Cutting Style -->
            <div class="space-y-3 pt-2">
                <label class="block text-xs font-extrabold text-brand-navy uppercase tracking-wider">
                    Step 2: Select Cutting Style (Prepped & Cleaned Free)
                </label>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($product->cuttingStyles as $index => $cs)
                        <label onclick="selectCuttingStyle({{ $cs->id }}, {{ $cs->additional_charge }})" 
                               class="cutting-card relative border-2 rounded-2xl p-3 cursor-pointer transition-all flex flex-col justify-between hover:border-brand-blue bg-slate-50 hover:bg-white shadow-sm {{ $index === 0 ? 'border-brand-blue bg-blue-50/40 ring-2 ring-blue-400/20' : 'border-slate-200' }}"
                               data-cs-id="{{ $cs->id }}">
                            <input type="radio" name="cutting_style" value="{{ $cs->id }}" class="hidden" {{ $index === 0 ? 'checked' : '' }}>
                            
                            <div>
                                <img src="{{ $cs->image ?: 'https://images.unsplash.com/photo-1510130318145-ad4f04e849a7?auto=format&fit=crop&w=300&q=80' }}" alt="{{ $cs->name }}" class="w-full h-16 sm:h-20 object-cover rounded-xl mb-2 border border-slate-200">
                                <h4 class="font-extrabold text-xs text-brand-navy leading-tight">{{ $cs->name }}</h4>
                                @if($cs->tamil_name)
                                    <p class="text-[10px] text-brand-blue font-bold mt-0.5">{{ $cs->tamil_name }}</p>
                                @endif
                            </div>

                            <div class="mt-2 pt-2 border-t border-slate-200 flex items-center justify-between text-[11px]">
                                <span class="text-slate-500 font-semibold">Extra Fee</span>
                                <span class="font-extrabold {{ $cs->additional_charge > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                    {{ $cs->additional_charge > 0 ? '+₹' . number_format($cs->additional_charge, 0) : 'FREE' }}
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
                            <span class="text-[11px] text-slate-300 font-medium">(Est. Bill)</span>
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
    let selectedCsId = {{ $product->cuttingStyles->first() ? $product->cuttingStyles->first()->id : 1 }};
    let selectedCsCharge = {{ $product->cuttingStyles->first() ? $product->cuttingStyles->first()->additional_charge : 0 }};

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
                card.classList.add('border-brand-blue', 'bg-blue-50/40', 'ring-2', 'ring-blue-400/20');
            } else {
                card.classList.remove('border-brand-blue', 'bg-blue-50/40', 'ring-2', 'ring-blue-400/20');
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
