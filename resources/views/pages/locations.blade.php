@extends('layouts.app')

@section('title', 'Store Locator & Coverage Radius - Daily Catch Fish Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <span class="bg-brand-blue/10 text-brand-blue text-xs font-black px-4 py-1.5 rounded-full border border-brand-blue/20 uppercase tracking-wider">
            East Tambaram & Beyond
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-brand-navy font-display">Store Locator & Express Delivery Radius</h1>
        <p class="text-xs sm:text-sm text-slate-500 font-semibold">Locate our physical fish market store and check express delivery coverage to your doorstep!</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Store Address Details -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <div class="flex items-center gap-4 border-b border-slate-100 pb-5">
                    <img src="{{ asset('images/logo.jpeg') }}" class="w-12 h-12 rounded-2xl border border-slate-200 shadow-sm">
                    <div>
                        <h3 class="font-black text-base text-brand-navy font-display">East Tambaram Store</h3>
                        <p class="text-xs text-brand-blue font-bold">Primary Harbor Hub</p>
                    </div>
                </div>

                <div class="space-y-5 text-xs">
                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center font-bold text-base shrink-0 border border-blue-100">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-brand-navy block text-xs">Physical Address</span>
                            <p class="text-slate-600 font-medium leading-relaxed mt-0.5">{{ $settings['address'] }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base shrink-0 border border-emerald-100">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-brand-navy block text-xs">WhatsApp & Orders</span>
                            <p class="text-slate-600 font-bold mt-0.5">+91 8778199218</p>
                            <a href="https://wa.me/918778199218" target="_blank" class="text-emerald-600 font-extrabold hover:underline inline-block mt-1">
                                Chat on WhatsApp <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base shrink-0 border border-amber-100">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-brand-navy block text-xs">Store Hours</span>
                            <p class="text-slate-600 font-semibold mt-0.5">Monday – Sunday: 6:00 AM – 8:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Map -->
        <div class="lg:col-span-7">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4 h-full flex flex-col">
                <h3 class="font-extrabold text-base text-brand-navy flex items-center gap-2 font-display">
                    <i class="fa-solid fa-map-location-dot text-brand-blue"></i> Interactive Store Map
                </h3>
                
                <div id="locationsMap" class="w-full flex-1 min-h-[380px] rounded-2xl border border-slate-200 shadow-inner"></div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const lMap = L.map('locationsMap').setView([12.9249, 80.1278], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(lMap);

        L.marker([12.9249, 80.1278]).addTo(lMap)
            .bindPopup("<b>Daily Catch Fish Shop</b><br>22g, Thiruvalluvar street, East Tambaram, Chennai-59")
            .openPopup();
    });
</script>
@endpush
@endsection
