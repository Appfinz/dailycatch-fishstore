@extends('layouts.app')

@section('title', 'Checkout - Daily Catch Fish Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-brand-navy tracking-tight font-display">Checkout & Fast Delivery</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Select your delivery location, choose your convenient delivery slot, and pay on delivery.</p>
        </div>

        <button type="button" id="detectLocBtn" onclick="locateUserGPS(true)" class="btn-brand-blue px-4 py-2.5 rounded-xl text-xs font-extrabold flex items-center gap-2 shadow self-start md:self-auto">
            <i class="fa-solid fa-location-crosshairs text-sm"></i>
            <span>Detect My Location</span>
        </button>
    </div>

    <!-- Prominent Top Location Warning Alert Banner (Hidden by default, shown if distance > Max Radius) -->
    <div id="topRadiusAlert" class="hidden mb-6 p-4 rounded-2xl bg-rose-50 border-2 border-rose-300 text-rose-900 shadow-md">
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-2xl mt-0.5 shrink-0"></i>
            <div class="space-y-1">
                <h4 class="font-extrabold text-sm uppercase text-rose-900">Delivery Service Unavailable Outside {{ $maxRadius }}KM</h4>
                <p id="topRadiusMsg" class="text-xs font-semibold leading-relaxed text-rose-800">
                    Your selected location is outside our {{ $maxRadius }} KM delivery radius from our East Tambaram shop.
                </p>
                <div class="pt-1 flex items-center gap-3">
                    <a href="https://wa.me/918778199218" target="_blank" class="inline-flex items-center gap-1.5 bg-rose-600 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg hover:bg-rose-700 transition-colors">
                        <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp for Special Request
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Checkout Form -->
    <form id="checkoutForm" onsubmit="handleCheckoutSubmit(event)">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Delivery Details -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 1. Delivery Method Selection & Pre-Order Toggle with Slots Integrated -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-5">
                    <h3 class="font-extrabold text-base text-brand-navy flex items-center gap-2 font-display">
                        <i class="fa-solid fa-truck-ramp-box text-brand-blue"></i> Step 1: Select Delivery Type & Time Slot
                    </h3>

                    <!-- Delivery Type (Home Delivery vs Pickup) -->
                    <div class="grid grid-cols-2 gap-4">
                        <label onclick="toggleDeliveryMode('delivery')" id="deliveryTabBtn" class="border-2 border-brand-blue bg-blue-50/40 rounded-2xl p-4 cursor-pointer text-center font-bold text-sm text-brand-navy transition-all flex flex-col items-center gap-2">
                            <input type="radio" name="delivery_type" value="delivery" checked class="hidden">
                            <i class="fa-solid fa-house-chimney text-2xl text-brand-blue"></i>
                            <span>Home Delivery</span>
                            <span class="text-[10px] text-slate-500 font-normal">Same Day Slot Delivery ({{ $maxRadius }}KM)</span>
                        </label>

                        <label onclick="toggleDeliveryMode('pickup')" id="pickupTabBtn" class="border-2 border-slate-200 bg-slate-50 rounded-2xl p-4 cursor-pointer text-center font-bold text-sm text-brand-navy transition-all flex flex-col items-center gap-2">
                            <input type="radio" name="delivery_type" value="pickup" class="hidden">
                            <i class="fa-solid fa-store text-2xl text-slate-500"></i>
                            <span>I Will Pick Up</span>
                            <span class="text-[10px] text-slate-500 font-normal">East Tambaram Shop</span>
                        </label>
                    </div>

                    <!-- Pre-Order for Tomorrow vs Today Same Day Delivery -->
                    <div class="pt-3 border-t border-slate-100 space-y-3">
                        <label class="block text-xs font-extrabold text-brand-navy uppercase tracking-wider">Delivery Date Preference</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label onclick="setPreorderMode(false)" id="tabToday" class="border-2 border-brand-blue bg-blue-50/40 rounded-2xl p-3.5 cursor-pointer flex items-center gap-3 transition-all">
                                <input type="radio" name="is_preorder_option" value="0" checked class="accent-brand-blue">
                                <div>
                                    <h4 class="font-extrabold text-xs text-brand-navy flex items-center gap-1.5">
                                        <i class="fa-solid fa-bolt text-amber-500"></i> Today's Same Day Delivery
                                    </h4>
                                    <p class="text-[11px] text-slate-500 font-medium">Delivered today in available 1-hr slot</p>
                                </div>
                            </label>

                            <label onclick="setPreorderMode(true)" id="tabTomorrow" class="border-2 border-emerald-500 bg-emerald-50/60 rounded-2xl p-3.5 cursor-pointer flex items-center gap-3 transition-all relative overflow-hidden">
                                <input type="radio" name="is_preorder_option" value="1" class="accent-emerald-600">
                                <div>
                                    <h4 class="font-extrabold text-xs text-emerald-950 flex items-center gap-1.5">
                                        <i class="fa-solid fa-calendar-check text-emerald-600"></i> Pre-Order for Tomorrow
                                    </h4>
                                    <p class="text-[11px] text-emerald-800 font-bold">Save ₹20 Pre-Order Discount!</p>
                                </div>
                                <span class="absolute -top-1 -right-1 bg-emerald-600 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded-bl-lg">
                                    -₹20 OFF
                                </span>
                            </label>
                        </div>

                        <!-- AVAILABLE 1-HOUR DELIVERY SLOTS (Integrated directly inside Step 1) -->
                        <div class="pt-2 space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-extrabold text-brand-navy flex items-center gap-1.5 uppercase tracking-wider">
                                    <i class="fa-solid fa-clock text-brand-blue"></i> Choose Delivery Slot
                                </label>
                                <span id="slotTimingBadge" class="text-[10px] text-brand-blue font-extrabold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">
                                    1-Hour Delivery Window
                                </span>
                            </div>

                            <div id="slotsContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Loaded dynamically via JS based on current time & preorder selection -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Customer Contact & Simplified Delivery Location -->
                <div id="addressSection" class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-5">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-base text-brand-navy flex items-center gap-2 font-display">
                            <i class="fa-solid fa-map-location-dot text-brand-blue"></i> Step 2: Contact & Delivery Address
                        </h3>
                    </div>

                    <!-- Contact Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" id="custName" required placeholder="e.g. Murugan" 
                                   class="w-full bg-slate-50 border border-slate-300 text-xs font-bold text-brand-navy rounded-xl px-3.5 py-3 focus:outline-none focus:border-brand-blue">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Number <span class="text-rose-500">*</span></label>
                            <input type="tel" id="custPhone" required placeholder="10 Digit Mobile Number" 
                                   class="w-full bg-slate-50 border border-slate-300 text-xs font-bold text-brand-navy rounded-xl px-3.5 py-3 focus:outline-none focus:border-brand-blue">
                        </div>
                    </div>

                    <!-- RETURNING CUSTOMER: ACTIVE SAVED ADDRESS CARD -->
                    <div id="savedAddressActiveCard" class="hidden bg-blue-50/50 border-2 border-brand-blue/40 rounded-2xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="bg-brand-blue text-white text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                    <i class="fa-solid fa-check-circle mr-1"></i> Delivering To
                                </span>
                                <span id="activeAddressLabel" class="font-extrabold text-xs text-brand-navy">Default Address</span>
                            </div>
                            <button type="button" onclick="showSavedAddressPicker()" class="text-xs font-extrabold text-brand-blue hover:underline flex items-center gap-1">
                                <i class="fa-solid fa-arrows-rotate"></i> Change Address / Pin Location
                            </button>
                        </div>
                        <p id="activeAddressText" class="text-xs font-bold text-slate-800 leading-relaxed"></p>
                        <div class="flex items-center gap-3 text-[11px] text-slate-500 font-semibold">
                            <span id="activeAddressDist" class="text-emerald-700 font-bold bg-emerald-100 px-2 py-0.5 rounded-md"></span>
                            <span id="activeAddressLandmark"></span>
                        </div>
                    </div>

                    <!-- SAVED ADDRESSES SELECTOR LIST (Only shown when customer clicks "Change Address") -->
                    <div id="savedAddressesPickerContainer" class="hidden space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-extrabold text-brand-navy uppercase tracking-wider">Choose Saved Delivery Location</label>
                            <button type="button" onclick="enableNewAddressMapMode()" class="text-xs font-extrabold text-brand-blue hover:underline flex items-center gap-1">
                                <i class="fa-solid fa-plus-circle"></i> + Add New Address on Map
                            </button>
                        </div>
                        <div id="savedAddressesList" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- NEW ADDRESS & MAP SECTION (Shown for first-time customers, or when adding a new address) -->
                    <div id="homeDeliveryAddressFields" class="space-y-4 border-t border-slate-100 pt-4">
                        <!-- Interactive Location Picker Map -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-extrabold text-brand-navy flex items-center gap-1.5 uppercase tracking-wider">
                                    <i class="fa-solid fa-location-dot text-brand-blue"></i> Pinpoint Exact Door Location on Map
                                </label>
                                <button type="button" onclick="locateUserGPS(true)" class="text-[11px] text-brand-blue font-bold hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-crosshairs"></i> Detect My Location
                                </button>
                            </div>

                            <p class="text-[11px] text-slate-500 font-medium">
                                Drag the blue map pin directly over your house or delivery door for accurate turn-by-turn navigation.
                            </p>

                            <div id="map" class="w-full h-64 rounded-2xl border-2 border-slate-300 shadow-inner z-10"></div>
                            
                            <input type="hidden" id="custLat" value="{{ $branch->latitude ?: 12.9249 }}">
                            <input type="hidden" id="custLng" value="{{ $branch->longitude ?: 80.1278 }}">

                            <div id="radiusAlert" class="p-3.5 rounded-2xl text-xs font-medium flex items-start gap-2.5 bg-emerald-50 text-emerald-900 border border-emerald-200">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5 shrink-0" id="radiusIcon"></i>
                                <div>
                                    <p id="radiusMsg" class="leading-relaxed font-bold">Location within {{ $maxRadius }}KM of East Tambaram Branch. Delivery Available!</p>
                                </div>
                            </div>
                        </div>

                        <!-- Address Details Input -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">House / Flat No / Street Address <span class="text-rose-500">*</span></label>
                                <textarea id="custAddress" rows="2" placeholder="e.g. Flat 3B, Door No 22G, Thiruvalluvar Street, East Tambaram" 
                                          class="w-full bg-slate-50 border border-slate-300 text-xs font-medium text-brand-navy rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-brand-blue">22G, Thiruvalluvar Street, East Tambaram, Chennai – 600059</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Landmark / Special Instructions (Optional)</label>
                                <input type="text" id="custLandmark" placeholder="Near Vendavarasi Amman Temple / Opposite FASTTRACK Computers" 
                                       class="w-full bg-slate-50 border border-slate-300 text-xs font-medium text-brand-navy rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-brand-blue">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Payment Method (LOCKED TO CASH ON DELIVERY) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-extrabold text-base text-brand-navy flex items-center gap-2 font-display">
                        <i class="fa-solid fa-wallet text-brand-blue"></i> Step 3: Payment Method
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="border-2 border-brand-blue bg-blue-50/40 rounded-2xl p-4 cursor-pointer flex items-center gap-3">
                            <input type="radio" name="payment_method" value="COD" checked class="accent-brand-blue">
                            <div>
                                <h4 class="font-extrabold text-xs text-brand-navy">Cash on Delivery (COD) / Pay on Delivery</h4>
                                <p class="text-[11px] text-slate-600 font-medium mt-0.5 leading-relaxed">Pay via Cash / UPI while receiving the fish after live weighing and quality inspection</p>
                            </div>
                        </label>

                        <div class="border-2 border-slate-200 bg-slate-50 rounded-2xl p-4 opacity-60 flex items-center gap-3">
                            <input type="radio" disabled class="accent-slate-400">
                            <div>
                                <h4 class="font-bold text-xs text-slate-400">Prepaid Online Payment</h4>
                                <p class="text-[10px] text-slate-400 font-medium">Final bill determined post-weighing (COD Active)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Review & Place Order -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-5 sticky top-28">
                    <h3 class="font-extrabold text-base text-brand-navy border-b border-slate-100 pb-3 font-display">Order Summary</h3>
                    
                    <div id="checkoutItemsReview" class="space-y-3 max-h-60 overflow-y-auto pr-1"></div>

                    <div class="space-y-2 text-xs text-slate-600 font-medium border-t border-slate-100 pt-4">
                        <div class="flex justify-between">
                            <span>Estimated Subtotal</span>
                            <span id="chkSubtotal" class="font-bold text-slate-800">₹0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Delivery Fee</span>
                            <span id="chkDelivery" class="font-bold text-emerald-600">₹0.00</span>
                        </div>
                        <div id="preorderDiscountRow" class="flex justify-between text-emerald-600 font-bold hidden">
                            <span>Pre-Order Discount</span>
                            <span id="chkPreorderDiscount">-₹20.00</span>
                        </div>
                        <div class="flex justify-between text-base font-black text-brand-navy pt-3 border-t border-slate-200">
                            <span>Estimated Total</span>
                            <span id="chkTotal" class="text-2xl text-brand-blue">₹0.00</span>
                        </div>
                    </div>

                    <button type="submit" id="placeOrderBtn" class="w-full btn-brand-blue py-4 rounded-xl font-extrabold text-xs uppercase tracking-wider text-center shadow-xl transition-all">
                        Place Order (Cash on Delivery) <i class="fa-solid fa-arrow-right ml-1"></i>
                    </button>

                    <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                        Order will be sent to East Tambaram branch for live weighing & instant dispatch.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
    let map, marker;
    let mapInitialized = false;
    let shopLat = {{ $branch->latitude ?: 12.9249 }};
    let shopLng = {{ $branch->longitude ?: 80.1278 }};
    let maxRadiusKm = {{ $maxRadius }};
    let cartDataCache = null;
    let isWithinMaxRadius = true;
    let isPreorder = false;
    const preorderDiscountVal = 20;

    let customerSavedAddresses = [];

    const allSlots = [
        { name: "Morning Slot", time_range: "07:00 AM - 08:00 AM", endHour: 8 },
        { name: "Mid-day Slot", time_range: "11:00 AM - 12:00 PM", endHour: 12 },
        { name: "Afternoon Slot", time_range: "02:00 PM - 03:00 PM", endHour: 15 },
        { name: "Evening Slot", time_range: "07:00 PM - 08:00 PM", endHour: 20 }
    ];

    // Custom Leaflet Icons for clear Source vs Destination distinction
    const storeIcon = L.divIcon({
        className: 'custom-store-marker',
        html: `<div style="background-color: #081E3F; border: 3px solid #F59E0B; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.35);">
                 <i class="fa-solid fa-store" style="color: #F59E0B; font-size: 16px;"></i>
               </div>`,
        iconSize: [38, 38],
        iconAnchor: [19, 19],
        popupAnchor: [0, -19]
    });

    const customerIcon = L.divIcon({
        className: 'custom-customer-marker',
        html: `<div style="position: relative; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                 <div style="position: absolute; inset: 0; background-color: rgba(30, 109, 235, 0.4); border-radius: 50%; animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;"></div>
                 <div style="position: relative; background-color: #1E6DEB; border: 3px solid #ffffff; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 6px 16px rgba(30, 109, 235, 0.5);">
                   <i class="fa-solid fa-location-dot" style="font-size: 18px;"></i>
                 </div>
               </div>`,
        iconSize: [42, 42],
        iconAnchor: [21, 21],
        tooltipAnchor: [0, -22]
    });

    function initMap() {
        if (mapInitialized) return;
        const mapContainer = document.getElementById('map');
        if (!mapContainer) return;

        map = L.map('map').setView([shopLat, shopLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // 1. Source (Store) Marker
        L.marker([shopLat, shopLng], { icon: storeIcon })
            .addTo(map)
            .bindPopup("<b>🏪 Daily Catch Store</b><br><span class='text-xs'>East Tambaram Branch (Source)</span>");

        // 2. Destination (Customer) Draggable Marker with Sticky Tooltip
        marker = L.marker([shopLat, shopLng], { draggable: true, icon: customerIcon }).addTo(map);
        
        marker.bindTooltip("<b>📍 You are here</b><br><span style='font-size:10px;'>Drag to adjust exact house door</span>", {
            permanent: true,
            direction: 'top',
            offset: [0, -18]
        }).openTooltip();

        marker.on('dragend', function (e) {
            let latlng = marker.getLatLng();
            updateLocationCoordinates(latlng.lat, latlng.lng);
            reverseGeocode(latlng.lat, latlng.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateLocationCoordinates(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        updateLocationCoordinates(shopLat, shopLng);
        mapInitialized = true;
    }

    async function updateLocationCoordinates(lat, lng) {
        document.getElementById('custLat').value = lat;
        document.getElementById('custLng').value = lng;

        try {
            const res = await fetch('/api/v1/validate-location', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ latitude: lat, longitude: lng })
            });
            const data = await res.json();

            const alertDiv = document.getElementById('radiusAlert');
            const msg = document.getElementById('radiusMsg');
            const icon = document.getElementById('radiusIcon');
            const topAlert = document.getElementById('topRadiusAlert');
            const topMsg = document.getElementById('topRadiusMsg');
            const btn = document.getElementById('placeOrderBtn');

            isWithinMaxRadius = data.is_within_radius;

            if (data.is_within_radius) {
                if (alertDiv) alertDiv.className = "p-3.5 rounded-2xl text-xs font-medium flex items-start gap-2.5 bg-emerald-50 text-emerald-900 border border-emerald-200";
                if (icon) icon.className = "fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5 shrink-0";
                if (msg) msg.innerText = data.message;

                if (topAlert) topAlert.classList.add('hidden');
                btn.disabled = false;
                btn.className = "w-full btn-brand-blue py-4 rounded-xl font-extrabold text-xs uppercase tracking-wider text-center shadow-xl transition-all";
            } else {
                if (alertDiv) alertDiv.className = "p-3.5 rounded-2xl text-xs font-medium flex items-start gap-2.5 bg-rose-50 text-rose-900 border border-rose-200";
                if (icon) icon.className = "fa-solid fa-triangle-exclamation text-rose-600 text-sm mt-0.5 shrink-0";
                if (msg) msg.innerText = data.message;

                if (topAlert) {
                    topAlert.classList.remove('hidden');
                    topMsg.innerText = data.message;
                }
                btn.disabled = true;
                btn.className = "w-full bg-slate-300 text-slate-500 cursor-not-allowed py-4 rounded-xl font-extrabold text-xs uppercase tracking-wider text-center";
            }
        } catch (e) { console.error(e); }
    }

    function locateUserGPS(userClicked = false) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                let lat = pos.coords.latitude;
                let lng = pos.coords.longitude;
                if (map) {
                    map.setView([lat, lng], 15);
                    marker.setLatLng([lat, lng]);
                }
                updateLocationCoordinates(lat, lng);
                reverseGeocode(lat, lng);
            }, (err) => {
                if (userClicked) alert("GPS detection denied or unavailable. Drag the map pin to select location.");
            });
        } else {
            if (userClicked) alert("Geolocation is not supported by your browser.");
        }
    }

    async function reverseGeocode(lat, lng) {
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
            const data = await res.json();
            if (data && data.display_name) {
                document.getElementById('custAddress').value = data.display_name;
            }
        } catch (e) { console.error(e); }
    }

    function showSavedAddressPicker() {
        document.getElementById('savedAddressesPickerContainer').classList.remove('hidden');
        document.getElementById('homeDeliveryAddressFields').classList.add('hidden');
    }

    function enableNewAddressMapMode() {
        document.getElementById('savedAddressesPickerContainer').classList.add('hidden');
        document.getElementById('homeDeliveryAddressFields').classList.remove('hidden');
        if (!mapInitialized) {
            initMap();
        } else {
            setTimeout(() => { map.invalidateSize(); }, 200);
        }
        document.getElementById('custAddress').focus();
    }

    function selectSavedAddress(lat, lng, addressText, label, distanceKm, landmark) {
        document.getElementById('custLat').value = lat;
        document.getElementById('custLng').value = lng;
        document.getElementById('custAddress').value = addressText;
        if (landmark) document.getElementById('custLandmark').value = landmark;

        document.getElementById('activeAddressLabel').innerText = label || 'Saved Address';
        document.getElementById('activeAddressText').innerText = addressText;
        document.getElementById('activeAddressDist').innerText = distanceKm ? `${distanceKm} KM Away` : '';
        document.getElementById('activeAddressLandmark').innerText = landmark ? `Landmark: ${landmark}` : '';

        document.getElementById('savedAddressActiveCard').classList.remove('hidden');
        document.getElementById('savedAddressesPickerContainer').classList.add('hidden');
        document.getElementById('homeDeliveryAddressFields').classList.add('hidden');

        updateLocationCoordinates(lat, lng);
    }

    async function loadCustomerAndAddresses() {
        try {
            const res = await fetch('/api/v1/auth/me');
            if (res.ok) {
                const authData = await res.json();
                if (authData.status === 'success' && authData.customer) {
                    if (authData.customer.name && !authData.customer.name.startsWith('Customer (')) {
                        document.getElementById('custName').value = authData.customer.name;
                    }
                    if (authData.customer.phone) {
                        document.getElementById('custPhone').value = authData.customer.phone;
                    }
                    if (authData.customer.addresses && authData.customer.addresses.length > 0) {
                        customerSavedAddresses = authData.customer.addresses;
                        renderSavedAddresses(customerSavedAddresses);
                        return;
                    }
                }
            }
        } catch(e) { console.error(e); }

        // First-time or guest customer flow: Show map directly
        initMap();
    }

    function renderSavedAddresses(addresses) {
        const container = document.getElementById('savedAddressesList');
        container.innerHTML = addresses.map((addr, idx) => `
            <div onclick="selectSavedAddress(${addr.latitude}, ${addr.longitude}, '${addr.street_address.replace(/'/g, "\\'")}', '${addr.label}', ${addr.distance_km}, '${(addr.landmark || '').replace(/'/g, "\\'")}')" 
                 class="border-2 rounded-2xl p-3.5 cursor-pointer transition-all flex flex-col justify-between hover:border-brand-blue hover:bg-blue-50/30 ${idx === 0 ? 'border-brand-blue bg-blue-50/20' : 'border-slate-200 bg-slate-50'}">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="font-extrabold text-xs text-brand-navy flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-brand-blue"></i> ${addr.label || 'Home'}
                    </span>
                    <span class="text-[10px] text-emerald-700 font-black bg-emerald-100 px-2 py-0.5 rounded-full">${addr.distance_km} KM</span>
                </div>
                <p class="text-xs text-slate-700 font-semibold line-clamp-2">${addr.street_address}</p>
                ${addr.landmark ? `<p class="text-[10px] text-slate-400 font-medium mt-1">Landmark: ${addr.landmark}</p>` : ''}
            </div>
        `).join('');

        // Select the default / first saved address cleanly
        const def = addresses.find(a => a.is_default) || addresses[0];
        selectSavedAddress(def.latitude, def.longitude, def.street_address, def.label, def.distance_km, def.landmark);
    }

    function setPreorderMode(preorder) {
        isPreorder = preorder;
        const tabToday = document.getElementById('tabToday');
        const tabTomorrow = document.getElementById('tabTomorrow');

        if (preorder) {
            tabTomorrow.className = "border-2 border-emerald-600 bg-emerald-100/80 rounded-2xl p-3.5 cursor-pointer flex items-center gap-3 transition-all relative overflow-hidden ring-2 ring-emerald-500/20";
            tabToday.className = "border-2 border-slate-200 bg-slate-50 rounded-2xl p-3.5 cursor-pointer flex items-center gap-3 transition-all opacity-75";
        } else {
            tabToday.className = "border-2 border-brand-blue bg-blue-50/40 rounded-2xl p-3.5 cursor-pointer flex items-center gap-3 transition-all";
            tabTomorrow.className = "border-2 border-emerald-500 bg-emerald-50/60 rounded-2xl p-3.5 cursor-pointer flex items-center gap-3 transition-all relative overflow-hidden";
        }

        renderDeliverySlots();
        updateSummaryTotal();
    }

    function renderDeliverySlots() {
        const container = document.getElementById('slotsContainer');
        const currentHour = new Date().getHours();
        
        const badge = document.getElementById('slotTimingBadge');
        if (badge) {
            badge.innerText = isPreorder ? "Tomorrow's 1-Hour Windows (-₹20 OFF)" : "Today's 1-Hour Delivery Windows";
        }

        let available = [];
        if (isPreorder) {
            // All slots for tomorrow available
            available = allSlots;
        } else {
            // Filter today's slots based on current time
            available = allSlots.filter(s => s.endHour > currentHour);
            if (available.length === 0) {
                // If today's slots are over, suggest tomorrow
                container.innerHTML = `
                    <div class="col-span-2 p-4 bg-amber-50 rounded-2xl border border-amber-200 text-amber-900 text-xs">
                        <p class="font-extrabold"><i class="fa-solid fa-moon"></i> Today's delivery slots are closed for the day.</p>
                        <p class="mt-1">Please select <strong>Pre-Order for Tomorrow</strong> above to save ₹20 on your order!</p>
                    </div>
                `;
                return;
            }
        }

        container.innerHTML = available.map((slot, idx) => `
            <label class="border-2 rounded-2xl p-3.5 cursor-pointer transition-all flex items-center justify-between border-slate-200 bg-slate-50 hover:bg-white">
                <div class="flex items-center gap-3">
                    <input type="radio" name="delivery_slot" value="${slot.name} (${slot.time_range})" ${idx === 0 ? 'checked' : ''} class="accent-brand-blue">
                    <div>
                        <h4 class="font-bold text-xs text-brand-navy">${slot.name} ${isPreorder ? '(Tomorrow)' : '(Today)'}</h4>
                        <p class="text-[11px] text-slate-500 font-semibold">${slot.time_range}</p>
                    </div>
                </div>
            </label>
        `).join('');
    }

    function toggleDeliveryMode(mode) {
        const delBtn = document.getElementById('deliveryTabBtn');
        const pickBtn = document.getElementById('pickupTabBtn');
        const addressSec = document.getElementById('addressSection');

        if (mode === 'delivery') {
            delBtn.className = "border-2 border-brand-blue bg-blue-50/40 rounded-2xl p-4 cursor-pointer text-center font-bold text-sm text-brand-navy transition-all flex flex-col items-center gap-2";
            pickBtn.className = "border-2 border-slate-200 bg-slate-50 rounded-2xl p-4 cursor-pointer text-center font-bold text-sm text-brand-navy transition-all flex flex-col items-center gap-2";
            addressSec.classList.remove('hidden');
        } else {
            pickBtn.className = "border-2 border-brand-blue bg-blue-50/40 rounded-2xl p-4 cursor-pointer text-center font-bold text-sm text-brand-navy transition-all flex flex-col items-center gap-2";
            delBtn.className = "border-2 border-slate-200 bg-slate-50 rounded-2xl p-4 cursor-pointer text-center font-bold text-sm text-brand-navy transition-all flex flex-col items-center gap-2";
            addressSec.classList.add('hidden');
        }
    }

    async function loadCheckoutReview() {
        try {
            const res = await fetch('/api/v1/cart');
            cartDataCache = await res.json();

            if (cartDataCache.status === 'success') {
                const container = document.getElementById('checkoutItemsReview');
                container.innerHTML = cartDataCache.items.map(item => `
                    <div class="flex items-center justify-between text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        <div>
                            <span class="font-bold text-brand-navy block">${item.product_name}</span>
                            <span class="text-brand-blue text-[10px] font-semibold">${item.cutting_style_name} • ${item.qty_kg} kg</span>
                        </div>
                        <span class="font-black text-brand-navy">₹${item.estimated_item_total.toFixed(2)}</span>
                    </div>
                `).join('');

                updateSummaryTotal();
            }
        } catch (e) { console.error(e); }
    }

    function updateSummaryTotal() {
        if (!cartDataCache) return;

        let subtotal = cartDataCache.estimated_subtotal || 0;
        let delFee = cartDataCache.delivery_fee || 0;
        let discount = isPreorder ? preorderDiscountVal : 0;

        let finalTotal = Math.max(0, subtotal + delFee - discount);

        document.getElementById('chkSubtotal').innerText = '₹' + subtotal.toFixed(2);
        document.getElementById('chkDelivery').innerText = delFee > 0 ? '₹' + delFee.toFixed(2) : 'FREE';

        const preRow = document.getElementById('preorderDiscountRow');
        if (isPreorder) {
            preRow.classList.remove('hidden');
        } else {
            preRow.classList.add('hidden');
        }

        document.getElementById('chkTotal').innerText = '₹' + finalTotal.toFixed(2);
    }

    async function handleCheckoutSubmit(e) {
        e.preventDefault();

        const delType = document.querySelector('input[name="delivery_type"]:checked').value;
        if (delType === 'delivery' && !isWithinMaxRadius) {
            alert(`Delivery unavailable. Your location is outside our ${maxRadiusKm}KM radius from East Tambaram branch.`);
            return;
        }

        const phone = document.getElementById('custPhone').value;
        const name = document.getElementById('custName').value;
        const address = document.getElementById('custAddress').value;
        const landmark = document.getElementById('custLandmark').value;
        const lat = document.getElementById('custLat').value;
        const lng = document.getElementById('custLng').value;
        
        const slotRadio = document.querySelector('input[name="delivery_slot"]:checked');
        const slot = slotRadio ? slotRadio.value : 'Morning Slot (07:00 AM - 08:00 AM)';

        if (!cartDataCache || !cartDataCache.items || cartDataCache.items.length === 0) {
            alert("Your basket is empty. Please add items to order.");
            return;
        }

        const items = cartDataCache.items.map(i => ({
            product_id: i.product_id,
            cutting_style_id: i.cutting_style_id,
            qty_kg: i.qty_kg
        }));

        const btn = document.getElementById('placeOrderBtn');
        btn.innerText = 'Placing Your Order...';
        btn.disabled = true;

        try {
            const res = await fetch('/api/v1/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    customer_name: name,
                    customer_phone: phone,
                    delivery_type: delType,
                    delivery_address: address,
                    landmark: landmark,
                    latitude: lat,
                    longitude: lng,
                    delivery_slot: slot,
                    is_preorder: isPreorder,
                    payment_method: 'COD',
                    cart_items: items
                })
            });

            const data = await res.json();
            if (res.status === 401) {
                openOtpModal();
                btn.innerText = 'Place Order (Cash on Delivery) \u2192';
                btn.disabled = false;
                return;
            }

            if (data.status === 'success') {
                window.location.href = '/order/track/' + data.order_number;
            } else {
                alert(data.message || 'Error placing order');
                btn.innerText = 'Place Order (Cash on Delivery) \u2192';
                btn.disabled = false;
            }
        } catch (err) {
            console.error(err);
            alert("Order placement failed. Please try again.");
            btn.innerText = 'Place Order (Cash on Delivery) \u2192';
            btn.disabled = false;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderDeliverySlots();
        loadCheckoutReview();
        loadCustomerAndAddresses();
    });
</script>
@endpush
@endsection

