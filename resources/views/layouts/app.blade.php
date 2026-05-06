<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($pageTitle) ? $pageTitle . ' — ' : '' }}InvoiceFlash</title>
    <meta name="description" content="Create professional invoices in 10 seconds. Smart invoice & receipt generator for freelancers.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-display { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-900 h-full" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-full">
        <!-- Sidebar Overlay (mobile) -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/60 z-40 lg:hidden backdrop-blur-sm">
        </div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="sidebar transition-transform duration-300 lg:translate-x-0">
            <!-- Logo -->
            <div class="p-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-brand rounded-xl flex items-center justify-center shadow-lg shadow-brand/20 group-hover:scale-110 transition-transform">
                        <i data-lucide="zap" class="w-6 h-6 text-white fill-white/20"></i>
                    </div>
                    <span class="text-xl font-black tracking-tight text-white font-display">Invoice<span class="text-brand">Flash</span></span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-2 space-y-1.5 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-grid"></i>
                    Dashboard
                </a>

                <div class="px-4 pt-6 pb-2 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Billing</div>
                <a href="{{ route('invoices.create') }}" class="sidebar-link {{ request()->routeIs('invoices.create') ? 'active' : '' }}">
                    <i data-lucide="plus-circle"></i>
                    New Invoice
                </a>
                <a href="{{ route('invoices.index') }}" class="sidebar-link {{ request()->routeIs('invoices.index', 'invoices.show', 'invoices.edit') ? 'active' : '' }}">
                    <i data-lucide="history"></i>
                    Invoice History
                </a>
                <a href="{{ route('receipts.create') }}" class="sidebar-link {{ request()->routeIs('receipts.*') ? 'active' : '' }}">
                    <i data-lucide="receipt"></i>
                    Receipts
                </a>

                <div class="px-4 pt-6 pb-2 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Management</div>
                <a href="{{ route('clients.index') }}" class="sidebar-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <i data-lucide="users"></i>
                    Clients
                </a>
                <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i data-lucide="settings"></i>
                    Settings
                </a>
            </nav>

            <!-- User Profile -->
            <div class="p-6 border-t border-white/5 bg-black/20">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 truncate">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 border border-white/10 flex items-center justify-center text-sm font-black text-white shrink-0">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-slate-500 hover:text-white transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col">
            <!-- Top Header -->
            <header class="glass border-b border-slate-200 sticky top-0 z-30">
                <div class="flex items-center justify-between px-8 h-20">
                    <div class="flex items-center gap-6">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-500">
                            <i data-lucide="menu" class="w-6 h-6"></i>
                        </button>
                        <h1 class="text-lg font-black text-slate-900 tracking-tight font-display uppercase">@yield('header', $header ?? 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-full border border-slate-200">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">System Live</span>
                        </div>
                        <a href="{{ route('invoices.create') }}" class="btn-primary btn-sm px-4">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span class="hidden sm:inline ml-1">New</span>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-8">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                         class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl text-sm flex items-center justify-between shadow-sm animate-fade-in">
                        <div class="flex items-center gap-4">
                            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                            <span class="font-bold">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false"><i data-lucide="x" class="w-4 h-4"></i></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-8 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl text-sm flex items-center justify-between shadow-sm animate-fade-in">
                        <div class="flex items-center gap-4">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                            <span class="font-bold">{{ session('error') }}</span>
                        </div>
                        <button><i data-lucide="x" class="w-4 h-4"></i></button>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>

    @stack('scripts')
    <script>lucide.createIcons();</script>
</body>
</html>
