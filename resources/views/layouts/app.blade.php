<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Daily Catch Fish Shop - Fresh Fish Delivered Today')</title>
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Leaflet CSS for Map Picker -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#081E3F',       /* Dark Navy in Reference */
                            deepnavy: '#06152B',   /* Footer Navy */
                            blue: '#1E6DEB',       /* Royal Blue Buttons */
                            hoverblue: '#1557C0',
                            lightblue: '#EBF3FE',  /* Light Blue Card Fill */
                            sky: '#D8ECF8',        /* Hero Sky Blue Gradient */
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #F8FAFC;
            color: #0F172A;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            overflow-x: hidden;
            -webkit-tap-highlight-color: transparent;
        }

        .btn-brand-blue {
            background-color: #1E6DEB;
            color: #FFFFFF;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-brand-blue:hover {
            background-color: #1557C0;
            box-shadow: 0 4px 12px rgba(30, 109, 235, 0.3);
        }

        .card-shadow {
            box-shadow: 0 2px 12px rgba(8, 30, 63, 0.05);
            transition: all 0.25s ease;
        }
        .card-shadow:hover {
            box-shadow: 0 8px 24px rgba(8, 30, 63, 0.1);
            transform: translateY(-2px);
        }

        /* Hide scrollbars for smooth touch swipe */
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

    <!-- GLOBAL UNSERVICEABLE LOCATION BANNER (Displays if user location > 3KM) -->
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

    <!-- 1. TOP UTILITY BAR (Desktop & Tablet) -->
    <div class="bg-brand-navy text-white text-[11px] font-medium py-2 px-4 sm:px-6 lg:px-8 border-b border-white/10">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-3 sm:gap-6 overflow-x-auto no-scrollbar whitespace-nowrap">
                <span><i class="fa-solid fa-truck-fast text-sky-300 mr-1"></i> Same Day Delivery</span>
                <span class="text-white/30">|</span>
                <span><i class="fa-solid fa-box text-sky-300 mr-1"></i> Delivered in Ice Box</span>
                <span class="hidden sm:inline text-white/30">|</span>
                <span class="hidden sm:inline"><i class="fa-solid fa-star text-amber-400 mr-1"></i> 100% Fresh Guarantee</span>
            </div>
            <div class="hidden md:flex items-center gap-4">
                <span>Support: <a href="tel:+918778199218" class="font-bold hover:underline"><i class="fa-solid fa-phone text-sky-300 mr-1"></i> +91 8778199218</a></span>
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
            <div class="flex items-center gap-3 sm:gap-6 shrink-0">
                
                <!-- Deliver To Pin (Desktop) -->
                <div class="hidden lg:flex items-center gap-2 text-xs border-r border-slate-200 pr-6">
                    <div class="w-8 h-8 rounded-full bg-brand-lightblue text-brand-blue flex items-center justify-center">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-semibold block">Deliver to</span>
                        <span id="headerLocationText" onclick="checkGlobalLocation(true)" class="font-bold text-slate-800 text-xs flex items-center gap-1 cursor-pointer hover:text-brand-blue" title="Click to detect your current location">
                            East Tambaram, Chennai <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </span>
                    </div>
                </div>

                <!-- Customer OTP Auth Account Trigger -->
                <div id="headerAuthContainer">
                    <button onclick="openOtpModal()" class="flex flex-col items-center text-slate-800 hover:text-brand-blue transition-colors">
                        <i class="fa-solid fa-user-circle text-lg sm:text-xl text-brand-navy"></i>
                        <span class="text-[10px] sm:text-[11px] font-extrabold mt-0.5">Login</span>
                    </button>
                </div>

                <!-- Cart Button -->
                <button onclick="toggleCartDrawer()" class="flex flex-col items-center text-slate-800 hover:text-brand-blue relative group">
                    <div class="relative">
                        <i class="fa-solid fa-basket-shopping text-lg sm:text-xl text-brand-navy group-hover:text-brand-blue"></i>
                        <span id="headerCartBadge" class="absolute -top-2 -right-2.5 bg-brand-blue text-white text-[10px] font-black w-4 sm:w-5 h-4 sm:h-5 rounded-full flex items-center justify-center border-2 border-white shadow">0</span>
                    </div>
                    <span class="text-[10px] sm:text-[11px] font-extrabold mt-0.5">Cart</span>
                </button>

                <!-- Admin Link -->
                <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-brand-navy p-2 sm:p-2.5 rounded-full text-xs font-bold transition-all" title="Admin Dashboard">
                    <i class="fa-solid fa-user-shield text-xs sm:text-sm"></i>
                </a>
            </div>

        </div>

        <!-- MOBILE SEARCH BAR (Visible only on mobile screens) -->
        <div class="px-4 pb-2.5 pt-1 md:hidden bg-white border-b border-slate-100">
            <form action="{{ route('catalog') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search for fish, prawns, crabs..." 
                       class="w-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-800 rounded-full pl-4 pr-10 py-2.5 focus:outline-none focus:bg-white focus:border-brand-blue placeholder:text-slate-400">
                <button type="submit" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-blue">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>
        </div>

        <!-- SUB-HEADER NAVIGATION (Horizontal touch-swipe scroll) -->
        <div class="border-t border-slate-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-5 sm:gap-8 overflow-x-auto py-2.5 no-scrollbar text-xs font-bold text-slate-700">
                <a href="{{ route('catalog', ['category' => 'sea-fish']) }}" class="hover:text-brand-blue whitespace-nowrap shrink-0 {{ request('category') === 'sea-fish' ? 'text-brand-blue font-extrabold' : '' }}">Fish</a>
                <a href="{{ route('catalog', ['category' => 'prawns']) }}" class="hover:text-brand-blue whitespace-nowrap shrink-0 {{ request('category') === 'prawns' ? 'text-brand-blue font-extrabold' : '' }}">Prawn</a>
                <a href="{{ route('catalog', ['category' => 'crab-squid']) }}" class="hover:text-brand-blue whitespace-nowrap shrink-0 {{ request('category') === 'crab-squid' ? 'text-brand-blue font-extrabold' : '' }}">Crab</a>
                <a href="{{ route('catalog') }}" class="text-slate-400 whitespace-nowrap shrink-0 cursor-not-allowed">Chicken <span class="text-[9px] bg-slate-100 text-slate-500 px-1 py-0.5 rounded font-semibold">Soon</span></a>
                <a href="{{ route('catalog') }}" class="text-slate-400 whitespace-nowrap shrink-0 cursor-not-allowed">Mutton <span class="text-[9px] bg-slate-100 text-slate-500 px-1 py-0.5 rounded font-semibold">Soon</span></a>
                <a href="{{ route('catalog') }}" class="hover:text-brand-blue whitespace-nowrap shrink-0">Other Seafood</a>
                <a href="{{ route('combos') }}" class="hover:text-brand-blue whitespace-nowrap shrink-0 text-amber-600 font-extrabold">Offers & Combos</a>
                <a href="{{ route('recipes') }}" class="hover:text-brand-blue whitespace-nowrap shrink-0">Recipes & Tips</a>
                <a href="{{ route('locations') }}" class="hover:text-brand-blue whitespace-nowrap shrink-0">Store Location</a>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- MOBILE OTP LOGIN MODAL -->
    <div id="otpModalBackdrop" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-5 sm:p-8 shadow-2xl border border-slate-200 space-y-5 relative">
            <button onclick="closeOtpModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 text-lg p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-brand-lightblue text-brand-blue flex items-center justify-center font-bold text-xl mx-auto">
                    <i class="fa-solid fa-mobile-screen"></i>
                </div>
                <h3 class="text-xl font-black text-brand-navy font-display">Customer Mobile Login</h3>
                <p class="text-xs text-slate-500 font-medium">Enter your 10-digit mobile number to receive OTP</p>
            </div>

            <!-- Step 1: Phone Number Input -->
            <form id="otpStep1Form" onsubmit="handleSendOtp(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-extrabold text-brand-navy mb-1 uppercase tracking-wider">Mobile Number</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-extrabold text-xs text-slate-500">+91</span>
                        <input type="tel" id="mobileInput" maxlength="10" required placeholder="9876543210" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 text-xs font-bold text-brand-navy focus:outline-none focus:border-brand-blue">
                    </div>
                </div>

                <button type="submit" id="sendOtpBtn" class="w-full btn-brand-blue py-3.5 rounded-xl text-xs font-black uppercase tracking-wider shadow-lg">
                    Send Verification Code (OTP) &rarr;
                </button>
            </form>

            <!-- Step 2: OTP Entry Input (Hidden Initially) -->
            <form id="otpStep2Form" onsubmit="handleVerifyOtp(event)" class="space-y-4 hidden">
                <div class="text-center bg-blue-50 p-3 rounded-2xl border border-blue-100">
                    <span class="text-xs text-slate-600 font-semibold">Demo OTP Code: <strong class="text-brand-blue font-mono font-bold">1234</strong></span>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-brand-navy mb-1 uppercase tracking-wider">Enter 4-Digit OTP</label>
                    <input type="text" id="otpInput" maxlength="4" required placeholder="1234" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-center text-lg font-mono font-black tracking-widest text-brand-navy focus:outline-none focus:border-brand-blue">
                </div>

                <button type="submit" id="verifyOtpBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 rounded-xl text-xs font-black uppercase tracking-wider shadow-lg">
                    Verify & Login Now &rarr;
                </button>
            </form>
        </div>
    </div>

    <!-- CART SLIDE-OVER DRAWER -->
    <div id="cartDrawerBackdrop" class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs z-50 hidden transition-opacity" onclick="toggleCartDrawer()"></div>
    <div id="cartDrawer" class="fixed top-0 right-0 bottom-0 w-full max-w-md bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-brand-navy text-white">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-basket-shopping text-sky-300"></i>
                <h3 class="font-extrabold text-base font-display">Your Seafood Basket</h3>
            </div>
            <button onclick="toggleCartDrawer()" class="text-slate-300 hover:text-white text-lg p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div id="cartDrawerItems" class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4"></div>

        <div class="p-4 sm:p-5 border-t border-slate-200 bg-slate-50 space-y-3">
            <div class="flex justify-between text-xs font-medium text-slate-600">
                <span>Estimated Subtotal</span>
                <span id="drawerSubtotal" class="font-bold text-slate-900">₹0.00</span>
            </div>
            <div class="flex justify-between text-xs font-medium text-slate-600">
                <span>Delivery Fee</span>
                <span class="font-bold text-emerald-600">₹35.00</span>
            </div>
            <div class="flex justify-between text-base font-black text-brand-navy pt-2 border-t border-slate-200">
                <span>Estimated Total</span>
                <span id="drawerTotal" class="text-xl text-brand-blue">₹0.00</span>
            </div>

            <a href="{{ route('checkout') }}" class="w-full btn-brand-blue py-3.5 rounded-xl font-extrabold text-xs uppercase tracking-wider text-center block shadow-lg">
                Proceed to Checkout (COD) &rarr;
            </a>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-brand-deepnavy text-slate-400 text-xs mt-auto border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpeg') }}" class="h-10 w-10 rounded-full border border-slate-700">
                    <span class="text-lg font-black text-white font-display">Daily Catch FISH SHOP</span>
                </div>
                <p class="text-slate-400 text-[11px] leading-relaxed">
                    Fresh seafood delivered to your doorstep. Handpicked. Hygienically cut. Delivered in ice box.
                </p>
            </div>

            <div>
                <h4 class="font-extrabold text-white text-xs uppercase tracking-wider mb-3">SHOP</h4>
                <ul class="space-y-2 text-[11px]">
                    <li><a href="{{ route('catalog', ['category' => 'sea-fish']) }}" class="hover:text-white">Fish</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'prawns']) }}" class="hover:text-white">Prawn</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'crab-squid']) }}" class="hover:text-white">Crab & Squid</a></li>
                    <li><a href="{{ route('combos') }}" class="hover:text-white">Offers & Combos</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-extrabold text-white text-xs uppercase tracking-wider mb-3">HELP & INFO</h4>
                <ul class="space-y-2 text-[11px]">
                    <li><a href="{{ route('locations') }}" class="hover:text-white">Track Your Order</a></li>
                    <li><a href="{{ route('locations') }}" class="hover:text-white">Store Location</a></li>
                    <li><a href="{{ route('recipes') }}" class="hover:text-white">Recipes & Cooking Tips</a></li>
                    <li><a href="{{ route('locations') }}" class="hover:text-white">Delivery Policy (3KM Radius)</a></li>
                </ul>
            </div>

            <div class="space-y-2">
                <h4 class="font-extrabold text-white text-xs uppercase tracking-wider mb-3">CONTACT US</h4>
                <p class="text-[11px] text-slate-300"><i class="fa-solid fa-phone text-brand-blue mr-2"></i> +91 8778199218</p>
                <p class="text-[11px] text-slate-300"><i class="fa-solid fa-location-dot text-brand-blue mr-2"></i> East Tambaram, Chennai - 600059</p>
            </div>
        </div>

        <div class="border-t border-slate-800 py-4 text-center text-[10px] text-slate-500">
            © {{ date('Y') }} Daily Catch Fish Shop. All rights reserved. Express 3KM Fresh Seafood Delivery.
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        let currentCustomer = null;

        function updateGlobalLocationUI(isWithin, distanceKm) {
            sessionStorage.setItem('dc_user_dist_km', distanceKm);
            sessionStorage.setItem('dc_user_within_radius', isWithin ? 'true' : 'false');

            const banner = document.getElementById('globalUnserviceableBanner');
            const distText = document.getElementById('globalDistText');
            const headerLoc = document.getElementById('headerLocationText');

            if (!isWithin) {
                if (banner) {
                    banner.classList.remove('hidden');
                    if (distText) distText.innerText = distanceKm + ' KM';
                }
                if (headerLoc) {
                    headerLoc.innerHTML = `<span class="text-rose-600 font-extrabold flex items-center gap-1">⚠️ Outside 3KM (${distanceKm} KM)</span>`;
                }
            } else {
                if (banner) banner.classList.add('hidden');
                if (headerLoc) {
                    headerLoc.innerHTML = `East Tambaram, Chennai <i class="fa-solid fa-chevron-down text-[10px]"></i>`;
                }
            }
        }

        // Smart Location Check (Checks automatically on first visit & caches in sessionStorage)
        function checkGlobalLocation(forcePrompt = false) {
            const cachedDist = sessionStorage.getItem('dc_user_dist_km');
            const cachedWithin = sessionStorage.getItem('dc_user_within_radius');

            // 1. If already determined in this session, reuse cached status immediately
            if (!forcePrompt && cachedDist !== null && cachedWithin !== null) {
                if (cachedDist !== 'denied') {
                    updateGlobalLocationUI(cachedWithin === 'true', parseFloat(cachedDist));
                }
                return;
            }

            // 2. On first visit OR when user clicks header location pin:
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    let lat = pos.coords.latitude;
                    let lng = pos.coords.longitude;

                    fetch('/api/v1/validate-location', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ latitude: lat, longitude: lng })
                    }).then(res => res.json()).then(data => {
                        updateGlobalLocationUI(data.is_within_radius, data.distance_km);
                    });
                }, err => {
                    sessionStorage.setItem('dc_user_dist_km', 'denied');
                    sessionStorage.setItem('dc_user_within_radius', 'false');
                    if (forcePrompt) {
                        alert("Location permission denied or unavailable. Please use the location search box on homepage or checkout.");
                    }
                });
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

            btn.innerText = 'Sending OTP...';
            btn.disabled = true;

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

            try {
                const res = await fetch('/api/v1/auth/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone: phone, otp: otp })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    closeOtpModal();
                    checkCustomerAuth();
                    if (window.location.pathname === '/checkout') {
                        window.location.reload();
                    }
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
