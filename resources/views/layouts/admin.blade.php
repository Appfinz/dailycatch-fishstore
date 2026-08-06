<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Daily Catch Fish Shop')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#081E3F',
                            blue: '#1E6DEB',
                            lightblue: '#D8ECF8',
                            bg: '#F8FAFC'
                        }
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F8FAFC; color: #081E3F; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex antialiased">

    <!-- Sidebar Navigation (Full-Fledged Operations Center) -->
    <aside class="w-64 bg-brand-navy text-white flex flex-col shrink-0">
        <div class="p-5 border-b border-white/10 flex items-center gap-3">
            <img src="{{ asset('images/logo.jpeg') }}" class="w-10 h-10 rounded-xl border border-brand-blue">
            <div>
                <h2 class="font-extrabold text-sm text-white leading-tight">Daily Catch</h2>
                <span class="text-[10px] text-sky-300 font-bold uppercase tracking-wider">Admin Control Hub</span>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 text-xs font-semibold overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-blue text-white font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-chart-line text-sm w-4"></i> Dashboard Overview
            </a>

            <a href="{{ route('admin.orders.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all justify-between {{ request()->routeIs('admin.orders.*') ? 'bg-brand-blue text-white font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-scale-balanced text-sm w-4"></i> Orders & Weighing
                </div>
                @php $pendingCount = \App\Models\Order::where('status', 'awaiting_fulfilment')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="bg-amber-400 text-slate-950 font-black px-2 py-0.5 rounded-full text-[10px]">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.products.*') ? 'bg-brand-blue text-white font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-fish text-sm w-4"></i> Daily Fish Stock & Rates
            </a>

            <a href="{{ route('admin.categories.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-brand-blue text-white font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-layer-group text-sm w-4"></i> Categories Manager
            </a>

            <a href="{{ route('admin.cutting-styles.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.cutting-styles.*') ? 'bg-brand-blue text-white font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-scissors text-sm w-4"></i> Cutting Styles & Fees
            </a>

            <a href="{{ route('admin.coupons.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.coupons.*') ? 'bg-brand-blue text-white font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-ticket text-sm w-4"></i> Coupons & Discounts
            </a>

            <a href="{{ route('admin.customers.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-brand-blue text-white font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-users text-sm w-4"></i> Customer Roster
            </a>

            <a href="{{ route('admin.settings.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-brand-blue text-white font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-gear text-sm w-4"></i> Store & Radius Config
            </a>

            <div class="pt-4 border-t border-white/10">
                <span class="text-[10px] font-bold text-slate-400 uppercase px-3 tracking-wider">Storefront</span>
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:bg-white/10 hover:text-white transition-all mt-1">
                    <i class="fa-solid fa-store text-sm text-sky-300 w-4"></i> View Customer App <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-auto"></i>
                </a>
            </div>
        </nav>

        <div class="p-4 border-t border-white/10 text-[11px] text-slate-300 flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-sky-300"></i>
            <span>East Tambaram Branch</span>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Admin Header Bar -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-500">Operating Branch:</span>
                <span class="bg-brand-navy text-sky-300 border border-brand-navy/30 px-3 py-1 rounded-full text-xs font-bold">
                    Chennai - East Tambaram (600059)
                </span>
            </div>

            <div class="flex items-center gap-3 text-xs">
                <span class="text-brand-navy font-extrabold"><i class="fa-solid fa-user-gear text-brand-blue mr-1"></i> Admin Operations Manager</span>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 p-6 sm:p-8 overflow-y-auto">
            
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-900 p-4 rounded-2xl text-xs font-bold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>

                    @if(session('whatsapp_url'))
                        <a href="{{ session('whatsapp_url') }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3.5 py-1.5 rounded-xl font-black text-xs flex items-center gap-1.5 shadow">
                            <i class="fa-brands fa-whatsapp text-sm"></i> Send Final Bill on WhatsApp
                        </a>
                    @endif
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
