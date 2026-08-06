@extends('layouts.app')

@section('title', 'Contact Us - Daily Catch Fish Shop East Tambaram')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <div>
        <h1 class="text-3xl font-black text-navy-900 tracking-tight">Visit Our Store & Contact Us</h1>
        <p class="text-sm text-slate-500 mt-1">We are located in East Tambaram, Chennai. Stop by or reach out on WhatsApp for daily fish updates!</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Contact Information Cards -->
        <div class="lg:col-span-5 space-y-4">
            
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-md space-y-4">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                    <img src="{{ asset('images/logo.jpeg') }}" class="w-12 h-12 rounded-xl border border-aqua-400">
                    <div>
                        <h3 class="font-extrabold text-base text-navy-900">Daily Catch Fish Shop</h3>
                        <p class="text-xs text-ocean-600 font-semibold">Freshness Delivered to your home.</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-navy-50 text-navy-900 flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-slate-800 block text-xs">Physical Store Address</span>
                            <p class="text-slate-600 leading-relaxed mt-0.5">{{ $settings['address'] }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-slate-800 block text-xs">WhatsApp & Orders</span>
                            <p class="text-slate-600 mt-0.5">+91 8778199218</p>
                            <a href="https://wa.me/918778199218" target="_blank" class="text-emerald-600 font-bold hover:underline inline-block mt-1">
                                Click here to Chat on WhatsApp <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-ocean-50 text-ocean-600 flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-slate-800 block text-xs">Email Support</span>
                            <p class="text-slate-600 mt-0.5">{{ $settings['email'] }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-slate-800 block text-xs">Store Business Hours</span>
                            <p class="text-slate-600 mt-0.5">Monday – Sunday: 6:00 AM – 8:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Google Maps Display -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-md space-y-4 h-full flex flex-col">
                <h3 class="font-extrabold text-base text-navy-900 flex items-center gap-2">
                    <i class="fa-solid fa-map text-ocean-600"></i> Store Location Map (East Tambaram)
                </h3>
                
                <div id="contactMap" class="w-full flex-1 min-h-[350px] rounded-2xl border border-slate-200 shadow-inner"></div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cMap = L.map('contactMap').setView([12.9249, 80.1278], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(cMap);

        L.marker([12.9249, 80.1278]).addTo(cMap)
            .bindPopup("<b>Daily Catch Fish Shop</b><br>22g, Thiruvalluvar street, East Tambaram, Chennai-59")
            .openPopup();
    });
</script>
@endpush
@endsection
