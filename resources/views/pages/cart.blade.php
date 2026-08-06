@extends('layouts.app')

@section('title', 'Shopping Basket - Daily Catch Fish Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-navy-900 tracking-tight">Your Seafood Basket</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review your fresh fish items and selected cutting options</p>
        </div>
        <a href="{{ route('home') }}" class="text-xs font-bold text-ocean-600 hover:underline flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Add More Fresh Catch
        </a>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Cart Items List -->
        <div class="lg:col-span-8 space-y-4">
            <div id="fullCartItemsContainer" class="space-y-4">
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-spinner fa-spin text-3xl text-ocean-600 mb-3"></i>
                    <p class="text-sm font-medium text-slate-600">Loading your seafood basket...</p>
                </div>
            </div>

            <!-- Weighing Notice Box -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs text-amber-900 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-circle-info text-amber-600 text-lg mt-0.5 shrink-0"></i>
                <div class="space-y-1">
                    <h5 class="font-bold text-amber-950">Important Pricing Transparency Note</h5>
                    <p class="leading-relaxed">
                        The final whole fish weight and bill amount will be updated after the fish is weighed in our shop. You will receive an updated bill link before dispatch!
                    </p>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Coupon Code Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-md space-y-4">
                <h3 class="font-bold text-sm text-navy-900 flex items-center gap-2">
                    <i class="fa-solid fa-ticket text-aqua-500"></i> Have a Coupon Code?
                </h3>
                <div class="flex gap-2">
                    <input type="text" id="couponCodeInput" placeholder="Try FIRSTFISH or FRESH10" 
                           class="flex-1 uppercase bg-slate-50 border border-slate-300 text-xs font-bold text-navy-900 rounded-xl px-3 py-2.5 focus:outline-none focus:border-ocean-500">
                    <button type="button" onclick="applyCouponCode()" class="bg-navy-900 hover:bg-ocean-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-all">
                        Apply
                    </button>
                </div>
                <div id="couponMsg" class="text-xs font-semibold hidden"></div>
            </div>

            <!-- Summary Breakdown Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-md space-y-4">
                <h3 class="font-extrabold text-base text-navy-900 border-b border-slate-100 pb-3">Order Summary</h3>
                
                <div class="space-y-2.5 text-xs text-slate-600 font-medium">
                    <div class="flex justify-between">
                        <span>Items Subtotal</span>
                        <span id="pageSubtotal" class="font-bold text-slate-800">₹0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Delivery Charge (3KM Radius)</span>
                        <span id="pageDeliveryFee" class="font-bold text-emerald-600">₹35.00</span>
                    </div>
                    <div id="pageDiscountRow" class="flex justify-between text-rose-600 hidden">
                        <span>Coupon Discount</span>
                        <span id="pageDiscount" class="font-bold">-₹0.00</span>
                    </div>
                    <div class="flex justify-between text-sm font-black text-navy-900 pt-3 border-t border-slate-200">
                        <span>Estimated Total</span>
                        <span id="pageTotal" class="text-2xl text-ocean-600">₹0.00</span>
                    </div>
                </div>

                <a href="{{ route('checkout') }}" class="w-full btn-gradient text-white py-3.5 rounded-xl font-bold text-sm text-center block shadow-lg transition-all">
                    Proceed to Checkout <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>

                <div class="text-[11px] text-slate-400 text-center flex items-center justify-center gap-1.5 pt-2">
                    <i class="fa-solid fa-shield-halved text-emerald-500"></i> Cash on Delivery available at checkout
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    async function loadFullCartPage() {
        try {
            const res = await fetch('/api/v1/cart');
            const data = await res.json();

            if (data.status === 'success') {
                const container = document.getElementById('fullCartItemsContainer');
                document.getElementById('pageSubtotal').innerText = '₹' + data.estimated_subtotal.toFixed(2);
                document.getElementById('pageDeliveryFee').innerText = '₹' + data.delivery_fee.toFixed(2);
                document.getElementById('pageTotal').innerText = '₹' + data.estimated_total.toFixed(2);

                if (data.discount_amount > 0) {
                    document.getElementById('pageDiscountRow').classList.remove('hidden');
                    document.getElementById('pageDiscount').innerText = '-₹' + data.discount_amount.toFixed(2);
                } else {
                    document.getElementById('pageDiscountRow').classList.add('hidden');
                }

                if (data.items.length === 0) {
                    container.innerHTML = `
                        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                            <i class="fa-solid fa-basket-shopping text-5xl text-slate-300 mb-4"></i>
                            <h3 class="text-lg font-bold text-slate-700">Your basket is empty</h3>
                            <p class="text-xs text-slate-500 mt-1 mb-6">Choose from wild-caught Seer fish, Sheela, Prawns & more!</p>
                            <a href="{{ route('home') }}" class="btn-gradient text-white font-bold text-xs px-6 py-3 rounded-full inline-block">
                                Explore Today's Fresh Catch
                            </a>
                        </div>
                    `;
                } else {
                    container.innerHTML = data.items.map(item => `
                        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <img src="${item.product_image}" class="w-20 h-20 object-cover rounded-xl border border-slate-200 shrink-0">
                                <div class="space-y-1">
                                    <h3 class="font-extrabold text-base text-navy-900">${item.product_name}</h3>
                                    <p class="text-xs text-ocean-600 font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-scissors"></i> Style: ${item.cutting_style_name}
                                    </p>
                                    <p class="text-xs text-slate-500">₹${item.price_per_kg}/kg + Extra Cutting Fee ₹${item.cutting_charge}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between w-full sm:w-auto gap-6 border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0">
                                <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden">
                                    <button onclick="updateCartQty('${item.cart_key}', ${item.qty_kg - 0.25})" class="px-3 py-1.5 bg-slate-100 text-xs font-bold">-</button>
                                    <span class="w-14 text-center text-xs font-extrabold text-navy-900">${item.qty_kg} kg</span>
                                    <button onclick="updateCartQty('${item.cart_key}', ${item.qty_kg + 0.25})" class="px-3 py-1.5 bg-slate-100 text-xs font-bold">+</button>
                                </div>

                                <div class="text-right">
                                    <span class="font-black text-base text-navy-900 block">₹${item.estimated_item_total.toFixed(2)}</span>
                                    <button onclick="removePageCartItem('${item.cart_key}')" class="text-rose-500 hover:text-rose-700 text-xs font-semibold">
                                        <i class="fa-solid fa-trash-can"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function updateCartQty(cartKey, newQty) {
        if (newQty < 0.25) return;
        try {
            await fetch('/api/v1/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cart_key: cartKey, qty_kg: newQty })
            });
            loadFullCartPage();
            fetchCartData();
        } catch (e) { console.error(e); }
    }

    async function removePageCartItem(cartKey) {
        try {
            await fetch('/api/v1/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cart_key: cartKey })
            });
            loadFullCartPage();
            fetchCartData();
        } catch (e) { console.error(e); }
    }

    async function applyCouponCode() {
        const code = document.getElementById('couponCodeInput').value;
        const msgDiv = document.getElementById('couponMsg');
        try {
            const res = await fetch('/api/v1/cart/apply-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ code: code })
            });
            const data = await res.json();
            msgDiv.classList.remove('hidden');
            if (data.status === 'success') {
                msgDiv.className = 'text-xs font-semibold text-emerald-600';
                msgDiv.innerText = data.coupon_message || 'Coupon applied!';
                loadFullCartPage();
            } else {
                msgDiv.className = 'text-xs font-semibold text-rose-600';
                msgDiv.innerText = data.message || 'Invalid coupon';
            }
        } catch (e) { console.error(e); }
    }

    document.addEventListener('DOMContentLoaded', loadFullCartPage);
</script>
@endpush
@endsection
