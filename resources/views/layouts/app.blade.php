<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Daily Catch Fish Shop - Freshness Delivered to Your Home')</title>

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#081E3F',
                            blue: '#1E6DEB',
                            sky: '#D8ECF8',
                            bg: '#F8FAFC'
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        display: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet CSS for Map Picker -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Firebase Web SDK v10 (Compat) for 100% Free Real SMS OTPs -->
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-auth-compat.js"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #F8FAFC;
            color: #081E3F;
        }

        .btn-brand-blue {
            background-color: #1E6DEB;
            transition: all 0.2s ease-in-out;
        }
        .btn-brand-blue:hover {
            background-color: #1555BD;
            transform: translateY(-1px);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-brand-blue selection:text-white pb-16 md:pb-0">

    <!-- Container for Firebase Invisible reCAPTCHA -->
    <div id="recaptcha-container"></div>

    <!-- GLOBAL UNSERVICEABLE LOCATION BANNER -->
    <div id="globalUnserviceableBanner" class="bg-amber-400 text-slate-950 text-xs font-bold py-2.5 px-4 shadow-md hidden border-b border-amber-500 z-50">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-slate-950 text-base shrink-0"></i>
                <span><strong>Notice:</strong> We deliver home orders only within 3.0 KM of East Tambaram, Chennai. Your location (<span id="globalDistText">1,740 KM</span> away) is outside our delivery zone.</span>
            </div>
            <a href="{{ route('locations') }}" class="bg-slate-950 text-white text-[11px] font-extrabold px-3 py-1 rounded-lg hover:bg-slate-800 shrink-0">
                View Store Location &rarr;
            </a>
        </div>
    </div>

    <!-- 1. TOP UTILITY BAR (Mobile & Desktop) -->
    <div class="bg-brand-navy text-white text-[11px] font-medium py-2 px-3 sm:px-6 lg:px-8 border-b border-white/10">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-2">
            <div class="flex items-center gap-2 sm:gap-4 overflow-x-auto no-scrollbar whitespace-nowrap">
                <span><i class="fa-solid fa-star text-amber-400 mr-1"></i> <strong class="text-amber-300">100% Freshness Guarantee</strong></span>
                <span class="text-white/30">|</span>
                <span class="hidden sm:inline"><i class="fa-solid fa-truck-fast text-sky-300 mr-1"></i> Ice Box Delivery</span>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="tel:+918778199218" class="bg-brand-blue/80 hover:bg-brand-blue text-white px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-bold inline-flex items-center gap-1 transition-all">
                    <i class="fa-solid fa-phone text-sky-200"></i> +91 8778199218
                </a>
            </div>
        </div>
    </div>

    <!-- 2. MAIN HEADER -->
    <header class="bg-white sticky top-0 z-40 border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between gap-2 sm:gap-8">
            
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 shrink-0">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Daily Catch Fish Shop Logo" class="h-10 w-10 sm:h-12 sm:w-12 object-cover rounded-full border border-slate-200 shadow-sm">
                <div>
                    <span class="text-lg sm:text-2xl font-black tracking-tight text-brand-navy block leading-none font-display">Daily Catch</span>
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-brand-blue block mt-0.5">Fish Shop</span>
                </div>
            </a>

            <!-- Desktop Search Bar -->
            <div class="flex-1 max-w-xl hidden md:block">
                <form action="{{ route('catalog') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search for fish, prawns, crabs..." 
                           class="w-full bg-slate-50 border border-slate-200 text-xs text-slate-800 rounded-full pl-5 pr-12 py-3 focus:outline-none focus:bg-white focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all placeholder:text-slate-400">
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-blue">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>
                </form>
            </div>

            <!-- Deliver To Location & User Actions -->
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('locations') }}" class="hidden lg:flex items-center gap-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-full text-xs font-semibold text-brand-navy transition-all">
                    <i class="fa-solid fa-location-dot text-brand-blue"></i>
                    <span id="headerStoreText">East Tambaram Store</span>
                </a>

                <!-- User Auth Profile -->
                <div id="headerAuthContainer">
                    <button onclick="openOtpModal()" class="bg-brand-navy text-white text-xs font-bold px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-full hover:bg-slate-900 transition-all flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-user text-sky-300"></i>
                        <span>Log In</span>
                    </button>
                </div>

                <!-- Shopping Cart Button -->
                <button onclick="openCartDrawer()" class="relative bg-brand-sky/60 hover:bg-brand-sky text-brand-navy p-2.5 sm:p-3 rounded-full transition-all flex items-center justify-center">
                    <i class="fa-solid fa-basket-shopping text-base sm:text-lg text-brand-blue"></i>
                    <span id="headerCartBadge" class="absolute -top-1 -right-1 bg-brand-blue text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm">0</span>
                </button>
            </div>
        </div>

        <!-- Mobile Search Bar directly under logo -->
        <div class="px-4 pb-3 md:hidden">
            <form action="{{ route('catalog') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search fresh fish, prawns, crabs..." 
                       class="w-full bg-slate-100 border border-slate-200 text-xs text-slate-800 rounded-full pl-4 pr-10 py-2.5 focus:outline-none focus:bg-white focus:border-brand-blue">
                <button type="submit" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>
        </div>

        <!-- Touch Horizontal Category Scroll -->
        <div class="bg-slate-50 border-t border-slate-200 px-4 py-2 text-xs font-bold">
            <div class="max-w-7xl mx-auto flex items-center gap-4 sm:gap-8 overflow-x-auto no-scrollbar whitespace-nowrap">
                <a href="{{ route('home') }}" class="hover:text-brand-blue text-slate-700">Home</a>
                <a href="{{ route('catalog') }}" class="hover:text-brand-blue text-slate-700">All Fish Catalog</a>
                <a href="{{ route('catalog', ['category' => 'sea-fish']) }}" class="hover:text-brand-blue text-slate-700">Sea Fish</a>
                <a href="{{ route('catalog', ['category' => 'prawns']) }}" class="hover:text-brand-blue text-slate-700">Prawns</a>
                <a href="{{ route('catalog', ['category' => 'crabs']) }}" class="hover:text-brand-blue text-slate-700">Crabs</a>
                <a href="{{ route('combos') }}" class="hover:text-brand-blue text-slate-700">Seafood Combos</a>
                <a href="{{ route('recipes') }}" class="hover:text-brand-blue text-slate-700">Fish Recipes</a>
                <a href="{{ route('locations') }}" class="hover:text-brand-blue text-slate-700">Store Locator</a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- 3. FOOTER -->
    <footer class="bg-brand-navy text-white mt-12 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.jpeg') }}" class="h-10 w-10 rounded-full border border-white/20">
                        <span class="text-xl font-black text-white font-display">Daily Catch</span>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">Freshness delivered to your home. Premium harbor-fresh sea fish weighed live and prepped to your exact cutting style.</p>
                </div>

                <div>
                    <h4 class="font-extrabold text-xs text-sky-300 uppercase tracking-wider mb-3">Shop Location</h4>
                    <p class="text-xs text-slate-300 leading-relaxed font-medium">
                        22g, Thiruvalluvar Street,<br>
                        East Tambaram, Chennai - 600059<br>
                        Landmark: Near Tambaram Station
                    </p>
                </div>

                <div>
                    <h4 class="font-extrabold text-xs text-sky-300 uppercase tracking-wider mb-3">Service Hours</h4>
                    <p class="text-xs text-slate-300 leading-relaxed font-medium">
                        Monday - Sunday: 06:00 AM - 08:00 PM<br>
                        Same-Day Ice Box Delivery: 3.0 KM Radius
                    </p>
                </div>

                <div>
                    <h4 class="font-extrabold text-xs text-sky-300 uppercase tracking-wider mb-3">Customer Support</h4>
                    <a href="tel:+918778199218" class="text-sm font-black text-white hover:text-sky-300 flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-phone text-sky-400"></i> +91 8778199218
                    </a>
                    <a href="https://wa.me/918778199218" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all">
                        <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp Support
                    </a>
                </div>
            </div>

            <div class="border-t border-white/10 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-400 gap-4">
                <p>© {{ date('Y') }} Daily Catch Fish Shop. All rights reserved.</p>
                <p>East Tambaram • Chennai - 59</p>
            </div>
        </div>
    </footer>

    <!-- MOBILE STICKY BOTTOM NAVIGATION BAR -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-30 px-3 py-2 flex justify-around items-center text-[10px] font-bold text-slate-600 shadow-2xl">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('home') ? 'text-brand-blue' : '' }}">
            <i class="fa-solid fa-house text-base"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('catalog') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('catalog') ? 'text-brand-blue' : '' }}">
            <i class="fa-solid fa-fish text-base"></i>
            <span>Fish Catalog</span>
        </a>
        <a href="javascript:void(0)" onclick="openCartDrawer()" class="flex flex-col items-center gap-0.5 text-brand-navy relative">
            <i class="fa-solid fa-basket-shopping text-base text-brand-blue"></i>
            <span>Cart</span>
        </a>
        <a href="{{ route('locations') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('locations') ? 'text-brand-blue' : '' }}">
            <i class="fa-solid fa-location-dot text-base"></i>
            <span>Store</span>
        </a>
    </nav>

    <!-- SHOPPING CART SLIDE-OVER DRAWER -->
    <div id="cartDrawerBackdrop" onclick="toggleCartDrawer()" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 hidden transition-opacity"></div>
    <div id="cartDrawer" class="fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <div class="p-4 sm:p-6 bg-brand-navy text-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-basket-shopping text-xl text-sky-300"></i>
                <h3 class="font-extrabold text-base font-display">Your Fresh Catch Basket</h3>
            </div>
            <button onclick="toggleCartDrawer()" class="text-white/70 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <div id="cartDrawerItems" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4"></div>

        <div class="p-4 sm:p-6 border-t border-slate-200 bg-slate-50 space-y-4">
            <div class="space-y-1 text-xs">
                <div class="flex justify-between text-slate-600 font-semibold">
                    <span>Estimated Subtotal</span>
                    <span id="drawerSubtotal" class="font-bold text-brand-navy">₹0.00</span>
                </div>
                <div class="flex justify-between text-base font-black text-brand-navy pt-2">
                    <span>Estimated Total</span>
                    <span id="drawerTotal" class="text-xl text-brand-blue">₹0.00</span>
                </div>
            </div>

            <a href="{{ route('checkout') }}" class="w-full btn-brand-blue py-3.5 rounded-xl font-extrabold text-xs uppercase tracking-wider text-white text-center block shadow-lg">
                Proceed to Checkout &rarr;
            </a>
        </div>
    </div>

    <!-- OTP AUTHENTICATION MODAL -->
    <div id="otpModalBackdrop" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 sm:p-8 shadow-2xl border border-slate-100 text-center relative space-y-5">
            <button onclick="closeOtpModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="w-12 h-12 bg-brand-sky rounded-2xl mx-auto flex items-center justify-center text-brand-blue text-xl font-bold">
                <i class="fa-solid fa-mobile-screen-button"></i>
            </div>

            <div>
                <h3 class="font-extrabold text-lg text-brand-navy font-display">Customer Mobile Login</h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">Verify your mobile number to view saved addresses & place orders.</p>
            </div>

            <form id="otpStep1Form" onsubmit="handleSendOtp(event)" class="space-y-4">
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-500">+91</span>
                    <input type="tel" id="mobileInput" required placeholder="10 Digit Mobile Number" 
                           class="w-full bg-slate-50 border border-slate-300 text-xs font-extrabold text-brand-navy rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:border-brand-blue">
                </div>
                <button type="submit" id="sendOtpBtn" class="w-full btn-brand-blue text-white py-3 rounded-xl font-extrabold text-xs uppercase tracking-wider shadow">
                    Send Verification Code (OTP) &rarr;
                </button>
            </form>

            <form id="otpStep2Form" onsubmit="handleVerifyOtp(event)" class="space-y-4 hidden">
                <div>
                    <input type="text" id="otpInput" required placeholder="Enter 6-Digit OTP Code" 
                           class="w-full bg-slate-50 border border-slate-300 text-center text-base font-black tracking-widest text-brand-navy rounded-xl px-4 py-3 focus:outline-none focus:border-brand-blue">
                    <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Check your SMS inbox for code (or use 1234)</span>
                </div>
                <button type="submit" id="verifyOtpBtn" class="w-full btn-brand-blue text-white py-3 rounded-xl font-extrabold text-xs uppercase tracking-wider shadow">
                    Verify & Login Now &rarr;
                </button>
            </form>
        </div>
    </div>

    <!-- GLOBAL SCRIPTS -->
    <script>
        let currentCustomer = null;

        // Firebase Configuration for Real SMS OTPs
        const firebaseConfig = {
            apiKey: "{{ \App\Models\Setting::get('firebase_api_key', '') }}",
            authDomain: "{{ \App\Models\Setting::get('firebase_auth_domain', '') }}",
            projectId: "{{ \App\Models\Setting::get('firebase_project_id', '') }}",
            appId: "{{ \App\Models\Setting::get('firebase_app_id', '') }}"
        };

        let confirmationResult = null;
        let recaptchaVerifier = null;

        if (firebaseConfig.apiKey && firebaseConfig.apiKey !== '') {
            try {
                firebase.initializeApp(firebaseConfig);
                recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                    'size': 'invisible'
                });
            } catch(e) { console.error("Firebase Init Error:", e); }
        }

        // Global Location Check with SessionStorage Caching
        function checkGlobalLocation(forcePrompt = false) {
            const cachedKm = sessionStorage.getItem('dc_user_dist_km');
            const cachedWithin = sessionStorage.getItem('dc_user_within_radius');

            if (cachedKm && cachedWithin && !forcePrompt) {
                applyLocationBanner(parseFloat(cachedKm), cachedWithin === 'true');
                return;
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async (pos) => {
                    let lat = pos.coords.latitude;
                    let lng = pos.coords.longitude;

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
                        
                        sessionStorage.setItem('dc_user_dist_km', data.distance_km);
                        sessionStorage.setItem('dc_user_within_radius', data.is_within_radius ? 'true' : 'false');

                        applyLocationBanner(data.distance_km, data.is_within_radius);
                    } catch (e) { console.error(e); }
                }, (err) => {
                    sessionStorage.setItem('dc_user_dist_km', 'denied');
                    sessionStorage.setItem('dc_user_within_radius', 'false');
                    if (forcePrompt) alert("Location permission denied or unavailable.");
                });
            }
        }

        function applyLocationBanner(distKm, isWithin) {
            const banner = document.getElementById('globalUnserviceableBanner');
            const distText = document.getElementById('globalDistText');
            if (banner && distText) {
                if (!isWithin && distKm > 0) {
                    distText.innerText = distKm + ' KM';
                    banner.classList.remove('hidden');
                } else {
                    banner.classList.add('hidden');
                }
            }
        }

        function openOtpModal() {
            document.getElementById('otpModalBackdrop').classList.remove('hidden');
        }

        function closeOtpModal() {
            document.getElementById('otpModalBackdrop').classList.add('hidden');
        }

        async function checkCustomerAuth() {
            try {
                const res = await fetch('/api/v1/auth/me');
                const data = await res.json();
                if (data.status === 'success' && data.logged_in) {
                    currentCustomer = data.customer;
                    document.getElementById('headerAuthContainer').innerHTML = `
                        <div class="flex items-center gap-2 cursor-pointer" onclick="handleLogout()">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-brand-blue text-white flex items-center justify-center font-bold text-xs">
                                ${data.customer.phone.substring(0, 2)}
                            </div>
                            <div class="hidden sm:block text-left">
                                <span class="text-[10px] text-slate-400 font-bold block leading-none">Logged In</span>
                                <span class="text-xs font-black text-brand-navy">${data.customer.phone}</span>
                            </div>
                        </div>
                    `;
                }
            } catch (e) { console.error(e); }
        }

        async function handleSendOtp(e) {
            e.preventDefault();
            const phone = document.getElementById('mobileInput').value;
            const btn = document.getElementById('sendOtpBtn');

            btn.innerText = 'Sending Code...';
            btn.disabled = true;

            // Try Firebase Real SMS first if keys are set
            if (firebaseConfig.apiKey && firebaseConfig.apiKey !== '' && recaptchaVerifier) {
                const fullPhone = '+91' + phone.replace(/[^0-9]/g, '');
                try {
                    confirmationResult = await firebase.auth().signInWithPhoneNumber(fullPhone, recaptchaVerifier);
                    document.getElementById('otpStep1Form').classList.add('hidden');
                    document.getElementById('otpStep2Form').classList.remove('hidden');
                    btn.innerText = 'Send Verification Code (OTP) \u2192';
                    btn.disabled = false;
                    return;
                } catch(error) {
                    console.warn("Firebase SMS notice:", error.message);
                    // Seamless fallback to instant OTP endpoint
                }
            }

            // Automatic Fallback Demo Mode (1234) if Firebase returns key propagation delay
            try {
                const res = await fetch('/api/v1/auth/send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone: phone })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    document.getElementById('otpStep1Form').classList.add('hidden');
                    document.getElementById('otpStep2Form').classList.remove('hidden');
                } else {
                    alert(data.message || 'Error sending OTP');
                }
            } catch (err) {
                alert('Failed to send OTP.');
            } finally {
                btn.innerText = 'Send Verification Code (OTP) \u2192';
                btn.disabled = false;
            }
        }

        async function handleVerifyOtp(e) {
            e.preventDefault();
            const phone = document.getElementById('mobileInput').value;
            const otp = document.getElementById('otpInput').value;
            const btn = document.getElementById('verifyOtpBtn');

            btn.innerText = 'Verifying...';
            btn.disabled = true;

            // If Firebase real OTP sent
            if (confirmationResult) {
                try {
                    const userCredential = await confirmationResult.confirm(otp);
                    const user = userCredential.user;
                    const res = await fetch('/api/v1/auth/firebase-verify', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ phone: phone, firebase_uid: user.uid })
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        closeOtpModal();
                        checkCustomerAuth();
                        if (window.location.pathname === '/checkout') window.location.reload();
                        return;
                    }
                } catch(error) {
                    console.warn("Firebase verify fallback:", error.message);
                }
            }

            // Fallback OTP mode (1234)
            try {
                const res = await fetch('/api/v1/auth/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone: phone, otp_code: otp })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    closeOtpModal();
                    checkCustomerAuth();
                    if (window.location.pathname === '/checkout') window.location.reload();
                } else {
                    alert(data.message || 'Invalid OTP');
                }
            } catch (err) {
                alert('Verification failed.');
            } finally {
                btn.innerText = 'Verify & Login Now \u2192';
                btn.disabled = false;
            }
        }

        async function handleLogout() {
            if (confirm("Logout from Daily Catch Fish Shop?")) {
                await fetch('/api/v1/auth/logout', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                window.location.reload();
            }
        }

        function openCartDrawer() {
            const drawer = document.getElementById('cartDrawer');
            const backdrop = document.getElementById('cartDrawerBackdrop');
            drawer.classList.remove('translate-x-full');
            backdrop.classList.remove('hidden');
            loadCartDrawer();
        }

        function toggleCartDrawer() {
            const drawer = document.getElementById('cartDrawer');
            const backdrop = document.getElementById('cartDrawerBackdrop');
            if (drawer.classList.contains('translate-x-full')) {
                drawer.classList.remove('translate-x-full');
                backdrop.classList.remove('hidden');
                loadCartDrawer();
            } else {
                drawer.classList.add('translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        async function loadCartDrawer() {
            try {
                const res = await fetch('/api/v1/cart');
                const data = await res.json();
                if (data.status === 'success') {
                    document.getElementById('headerCartBadge').innerText = data.item_count;
                    document.getElementById('drawerSubtotal').innerText = '₹' + data.estimated_subtotal.toFixed(2);
                    document.getElementById('drawerTotal').innerText = '₹' + data.estimated_total.toFixed(2);

                    const container = document.getElementById('cartDrawerItems');
                    if (data.items.length === 0) {
                        container.innerHTML = `
                            <div class="text-center py-12 text-slate-400 space-y-3">
                                <i class="fa-solid fa-basket-shopping text-4xl text-slate-300"></i>
                                <p class="text-xs font-bold">Your basket is empty</p>
                            </div>
                        `;
                    } else {
                        container.innerHTML = data.items.map(item => `
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3.5 flex items-center justify-between gap-3">
                                <div class="space-y-1">
                                    <h4 class="font-extrabold text-xs text-brand-navy">${item.product_name}</h4>
                                    <p class="text-[10px] text-brand-blue font-bold">${item.cutting_style_name} • ${item.qty_kg} kg</p>
                                    <p class="text-xs font-black text-brand-navy">₹${item.estimated_item_total.toFixed(2)}</p>
                                </div>
                                <button onclick="removeFromCart('${item.cart_key}')" class="text-rose-500 hover:text-rose-700 text-xs p-1.5">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        `).join('');
                    }
                }
            } catch (e) { console.error(e); }
        }

        async function removeFromCart(cartKey) {
            await fetch('/api/v1/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cart_key: cartKey })
            });
            loadCartDrawer();
        }

        document.addEventListener('DOMContentLoaded', () => {
            checkCustomerAuth();
            loadCartDrawer();
            checkGlobalLocation(false);
        });
    </script>
    @stack('scripts')
</body>
</html>
