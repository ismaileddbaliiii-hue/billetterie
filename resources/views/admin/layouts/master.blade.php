{{-- resources/views/admin/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Administration') - {{ config('app.name') }}</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        dark: {
                            100: '#1e293b',
                            200: '#0f172a',
                            300: '#0a0f1c',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(100%)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'hard': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02)',
                        'glow': '0 0 20px rgba(59, 130, 246, 0.15)',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            overflow: hidden;
        }
        
        .layout-shell {
            height: 100vh;
            display: flex;
            overflow: hidden;
            position: relative;
        }

        /* Sidebar Styles */
        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100vh;
            overflow-y: auto;
            background: linear-gradient(180deg, #0f172a 0%, #0a0f1c 100%);
            position: relative;
            z-index: 40;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow: hidden;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        main {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            scroll-behavior: smooth;
            position: relative;
        }
        
        main::-webkit-scrollbar {
            width: 8px;
        }
        
        main::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px;
        }
        
        main::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #94a3b8, #64748b);
            border-radius: 10px;
        }
        
        main::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #64748b, #475569);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                height: 100vh;
                box-shadow: 2px 0 25px rgba(0, 0, 0, 0.15);
            }
            .sidebar.open {
                transform: translateX(0);
            }
        }
        
        /* Badge Styles */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            transition: all 0.2s;
        }
        .badge-success { 
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            color: #059669; 
            border: 1px solid #a7f3d0;
        }
        .badge-warning { 
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            color: #d97706; 
            border: 1px solid #fde68a;
        }
        .badge-danger { 
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            color: #dc2626; 
            border: 1px solid #fecaca;
        }
        .badge-info { 
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #2563eb; 
            border: 1px solid #bfdbfe;
        }
        
        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 0.625rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #334155;
            position: relative;
            overflow: hidden;
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }
        .btn-primary:hover::before {
            left: 100%;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-secondary {
            background: white;
            color: #1e293b;
            padding: 0.625rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px -5px rgba(0, 0, 0, 0.08);
        }
        
        /* Stat Card */
        .stat-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #eef2ff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec489a);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }
        .stat-card:hover::before {
            transform: translateX(0);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.1);
            border-color: #e0e7ff;
        }
        
        /* Search Input */
        .search-wrapper {
            position: relative;
        }
        .search-input {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 40px;
            padding: 0.6rem 1rem 0.6rem 2.75rem;
            font-size: 0.85rem;
            transition: all 0.3s;
            width: 260px;
        }
        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            width: 320px;
        }
        
        /* Navigation Links */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            color: #94a3b8;
            position: relative;
            overflow: hidden;
        }
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            transform: scaleY(0);
            transition: transform 0.2s;
        }
        .nav-link:hover::before {
            transform: scaleY(1);
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: white;
            transform: translateX(5px);
        }
        .nav-link.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(139, 92, 246, 0.15));
            color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .nav-link.active::before {
            transform: scaleY(1);
        }
        .nav-link i {
            width: 1.5rem;
            font-size: 1.1rem;
        }
        
        /* Header Glass Effect */
        .glass-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            position: sticky;
            top: 0;
            z-index: 30;
        }
        
        /* Avatar Initials */
        .avatar-initials {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            transition: all 0.3s;
        }
        .avatar-initials:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        /* Table Styles */
        .data-table th {
            background: #f8fafc;
            font-weight: 700;
            color: #1e293b;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 1rem 1rem;
        }
        .table-row {
            transition: all 0.2s;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-row:hover {
            background: linear-gradient(90deg, #fefce8, #fff7ed);
            transform: scale(1.01);
        }
        
        /* Animation Classes */
        .fade-in {
            animation: fadeInUp 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Status Dots */
        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }
        .status-active { 
            background: #10b981; 
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }
        .status-inactive { 
            background: #ef4444; 
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
        
        /* Revenue Text */
        .revenue-positive {
            color: #059669;
            font-weight: 700;
            background: linear-gradient(135deg, #059669, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Custom Scrollbar for Sidebar */
        .custom-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        
        /* Loading Spinner */
        .spinner {
            border: 3px solid rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            border-top: 3px solid #3b82f6;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Card Hover Effect */
        .hover-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec489a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    
    @stack('styles')
</head>

<body class="antialiased">
    <div class="layout-shell">
        
        <!-- ==================== SIDEBAR MODERN ==================== -->
        <aside id="sidebar" class="sidebar w-80 flex-shrink-0 shadow-2xl custom-scroll">
            <!-- Logo Area with Gradient -->
            <div class="px-6 pt-8 pb-6 border-b border-white/10 relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-purple-600/10 rounded-full blur-3xl"></div>
                <div class="flex items-center gap-3 relative z-10">
                    @php
                        $appName = config('app.name', 'TicketFlow');
                        $logoUrl = \App\Models\Setting::getUrl('site_logo');
                    @endphp
                    
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $appName }}" class="w-12 h-12 rounded-2xl object-contain border-2 border-white/30 shadow-xl hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center shadow-xl hover:scale-105 transition-transform duration-300">
                            <i class="fas fa-ticket-alt text-white text-xl"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">{{ $appName }}</h1>
                        <p class="text-[10px] text-indigo-300/80 tracking-wider mt-0.5 uppercase">ADMINISTRATION HUB</p>
                    </div>
                </div>
            </div>
            
            <!-- User Profile Card -->
            <div class="mx-5 mt-6 p-5 rounded-2xl bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-sm border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        @php
                            $sidebarProfileSrc = Auth::user()->profile_image_url ?? null;
                        @endphp
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center overflow-hidden border-2 border-white/30 avatar-initials group-hover:scale-110 transition-transform duration-300">
                            @if($sidebarProfileSrc)
                                <img src="{{ $sidebarProfileSrc }}" alt="{{ Auth::user()->prenom ?? 'Admin' }} {{ Auth::user()->nom ?? 'User' }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-white font-bold text-xl">
                                    {{ strtoupper(substr(Auth::user()->prenom ?? 'A', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'A', 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-slate-900 rounded-full animate-pulse"></div>
                    </div>
                    <div class="flex-1">
                        <p class="text-base font-bold text-white">{{ Auth::user()->prenom ?? 'Admin' }} {{ Auth::user()->nom ?? 'User' }}</p>
                        <p class="text-xs text-indigo-200/80 mt-0.5 flex items-center gap-1">
                            <i class="fas fa-shield-alt text-[10px]"></i>
                            Super Administrateur
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation with Icons -->
            <nav class="px-3 py-6 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i><span>Tableau de bord</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="ml-auto w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                    @endif
                </a>
                
                <div x-data="{ open: {{ request()->routeIs('admin.organisateurs*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="nav-link w-full justify-between group">
                        <div class="flex items-center gap-3"><i class="fas fa-users"></i><span>Organisateurs</span></div>
                        <i class="fas fa-chevron-down text-[10px] transition-all duration-300" :class="{'rotate-180': open, 'group-hover:translate-y-0.5': !open}"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="ml-11 space-y-1 mt-2">
                        <a href="{{ route('admin.organisateurs.index') }}" class="flex items-center gap-3 px-3 py-2 text-xs text-gray-400 hover:text-white rounded-lg transition-all duration-200 hover:translate-x-1">
                            <i class="fas fa-list w-4"></i>Liste des organisateurs
                        </a>
                        <a href="{{ route('admin.organisateurs.create') }}" class="flex items-center gap-3 px-3 py-2 text-xs text-gray-400 hover:text-white rounded-lg transition-all duration-200 hover:translate-x-1">
                            <i class="fas fa-plus w-4"></i>Ajouter un organisateur
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('admin.evenements.index') }}" class="nav-link {{ request()->routeIs('admin.evenements*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-week"></i><span>Événements</span>
                </a>
                
                <a href="{{ route('admin.commandes.index') }}" class="nav-link {{ request()->routeIs('admin.commandes*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i><span>Commandes</span>
                </a>
                
                <a href="{{ route('admin.lieux.index') }}" class="nav-link {{ request()->routeIs('admin.lieux*') ? 'active' : '' }}">
                    <i class="fas fa-map-pin"></i><span>Lieux</span>
                </a>
                
                <a href="{{ route('admin.clients.index') }}" class="nav-link {{ request()->routeIs('admin.clients*') ? 'active' : '' }}">
                    <i class="fas fa-users-viewfinder"></i><span>Clients</span>
                </a>
                
                <a href="{{ route('admin.paiements.index') }}" class="nav-link {{ request()->routeIs('admin.paiements*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i><span>Paiements</span>
                </a>
                
                
                                <a href="{{ route('admin.billets.index') }}" class="nav-link {{ request()->routeIs('admin.billets*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i><span>Billets</span>
                </a>
                                <div class="pt-6 mt-4">
                    <p class="px-4 py-2 text-[10px] font-bold text-indigo-300/60 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-cog text-[10px]"></i>
                        Administration
                    </p>
                </div>
                
               
                
                <a href="{{ route('admin.logs.index') }}" class="nav-link {{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i><span>Logs activité</span>
                </a>
                
                <a href="{{ route('admin.parametres.index') }}" class="nav-link {{ request()->routeIs('admin.parametres*') ? 'active' : '' }}">
                    <i class="fas fa-sliders-h"></i><span>Paramètres</span>
                </a>
            </nav>
            
            <!-- Sidebar Footer with Gradient -->
            <div class="absolute bottom-0 w-full p-5 border-t border-white/10 bg-gradient-to-t from-black/20 to-transparent">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-gray-400 hover:text-white w-full px-3 py-2.5 rounded-xl hover:bg-white/10 transition-all duration-300 text-sm group">
                        <i class="fas fa-sign-out-alt group-hover:rotate-180 transition-transform duration-300"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
                <div class="text-center mt-4">
                    <p class="text-[9px] text-gray-500/80 tracking-wider">© {{ date('Y') }} {{ config('app.name') }}</p>
                    <p class="text-[8px] text-gray-600/60 mt-1">Version 2.0 | Build {{ date('Ymd') }}</p>
                </div>
            </div>
        </aside>
        
        <!-- ==================== MAIN CONTENT ==================== -->
        <div class="main-wrapper">
            <!-- Header Premium with Glassmorphism -->
            <header class="glass-header px-8 py-5 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-slate-600 hover:text-slate-900 p-2.5 rounded-xl hover:bg-slate-100 transition-all duration-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h2 class="text-2xl font-bold gradient-text">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                            <i class="fas fa-chart-line text-[10px]"></i>
                            @yield('page-description', 'Données en temps réel')
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-5">
                    <!-- Search Bar -->
                    <div class="search-wrapper hidden lg:block">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="globalSearchInput" placeholder="Rechercher..." class="search-input">
                    </div>
                   
                    <!-- Notifications -->
                    @php
                        $adminUser = Auth::user();
                        $unreadCount = 0;
                        $notifications = collect();
                        if ($adminUser && \Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                            try {
                                $unreadCount = \App\Models\Notification::where('user_id', $adminUser->id)->where('is_read', false)->count();
                                $notifications = \App\Models\Notification::where('user_id', $adminUser->id)->orderBy('created_at', 'desc')->limit(5)->get();
                            } catch (Exception $e) {
                                $unreadCount = 0;
                                $notifications = collect();
                            }
                        }
                    @endphp
                    <div class="relative">
                        <button id="notificationsButton" onclick="toggleNotifications()" class="relative text-slate-500 hover:text-slate-800 p-2.5 rounded-xl hover:bg-slate-100 transition-all duration-200">
                            <i class="far fa-bell text-xl"></i>
                            <span id="notificationBadge" class="absolute -top-1 -right-1 bg-gradient-to-r from-rose-500 to-red-600 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-md">{{ $unreadCount > 0 ? $unreadCount : '' }}</span>
                        </button>
                        <div id="notificationsMenu" class="hidden absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-hard border border-slate-100 overflow-hidden z-50 animate-fade-in">
                            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-bell text-indigo-500"></i>
                                    Notifications
                                </h3>
                                @if($unreadCount > 0)
                                    <button id="markAllRead" class="text-indigo-600 text-xs font-semibold hover:underline transition-all">
                                        Tout marquer comme lu
                                    </button>
                                @endif
                            </div>
                            <div id="notificationsList" class="max-h-96 overflow-y-auto custom-scroll">
                                @if($notifications->isEmpty())
                                    <div class="p-12 text-center">
                                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-bell-slash text-3xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">Aucune notification</p>
                                        <p class="text-xs text-slate-400 mt-1">Vous serez notifié des nouvelles activités</p>
                                    </div>
                                @else
                                    @foreach($notifications as $notification)
                                        <a href="{{ $notification->route ?: 'javascript:void(0)' }}" class="group block border-b border-slate-100 p-4 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-transparent transition-all duration-200">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-2 h-2 mt-2 rounded-full {{ $notification->is_read ? 'bg-slate-300' : 'bg-indigo-500 animate-pulse' }}"></div>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $notification->title ?? 'Nouvelle notification' }}</p>
                                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ Str::limit($notification->message, 100) }}</p>
                                                    <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1">
                                                        <i class="far fa-clock"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                @if(!$notification->is_read)
                                                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                            <div class="p-3 border-t border-slate-100 bg-slate-50 text-center">
                                <a href="{{ route('admin.notifications.index') }}" class="text-xs text-indigo-600 font-semibold hover:underline">Voir toutes les notifications</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu with Avatar -->
                    <div class="relative">
                        <button onclick="toggleUserMenu()" class="flex items-center gap-3 focus:outline-none group p-1.5 rounded-xl hover:bg-slate-100 transition-all duration-200">
                            @php
                                $profileSrc = Auth::user()->profile_image_url ?? null;
                            @endphp
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md avatar-initials group-hover:scale-105 transition-transform duration-200">
                                @if($profileSrc)
                                    <img src="{{ $profileSrc }}" alt="{{ Auth::user()->prenom }} {{ Auth::user()->nom }}" class="w-full h-full object-cover rounded-xl">
                                @else
                                    <span class="text-sm font-bold text-white">
                                        {{ strtoupper(substr(Auth::user()->prenom ?? 'A', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'A', 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-bold text-slate-700">{{ Auth::user()->prenom ?? 'Admin' }} {{ Auth::user()->nom ?? 'User' }}</p>
                                <p class="text-[10px] text-slate-400 flex items-center gap-1">
                                    <i class="fas fa-shield-alt text-[8px]"></i>
                                    Administrateur
                                </p>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-slate-400 hidden md:inline transition-transform duration-200 group-hover:translate-y-0.5"></i>
                        </button>
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-hard border border-slate-100 z-50 overflow-hidden animate-fade-in">
                            <div class="p-4 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-md">
                                        @if($profileSrc)
                                            <img src="{{ $profileSrc }}" class="w-full h-full object-cover rounded-xl">
                                        @else
                                            <span class="text-white font-bold">
                                                {{ strtoupper(substr(Auth::user()->prenom ?? 'A', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'A', 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ Auth::user()->prenom ?? 'Admin' }} {{ Auth::user()->nom ?? 'User' }}</p>
                                        <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('admin.parametres.index', ['tab' => 'profile']) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition-all duration-200">
                                    <i class="far fa-user w-4"></i>Mon profil
                                </a>
                                <a href="{{ route('admin.parametres.index', ['tab' => 'profile']) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition-all duration-200">
                                    <i class="fas fa-lock w-4"></i>Changer mot de passe
                                </a>
                                <hr class="my-2 border-slate-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                                        <i class="fas fa-sign-out-alt"></i>Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content with Animation -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                <!-- Mobile Title -->
                <div class="md:hidden mb-6">
                    <h1 class="text-2xl font-bold gradient-text">@yield('page-title')</h1>
                    @hasSection('page-description')
                        <p class="text-slate-500 text-sm mt-1">@yield('page-description')</p>
                    @endif
                </div>
                
                <!-- Alerts with Animation -->
                @if(session('success'))
                    <div class="mb-6 alert-message bg-gradient-to-r from-emerald-50 to-emerald-100/50 border-l-4 border-emerald-500 text-emerald-800 p-5 rounded-2xl shadow-sm fade-in">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check-circle text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">Succès !</p>
                                    <p class="text-sm">{{ session('success') }}</p>
                                </div>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 alert-message bg-gradient-to-r from-red-50 to-red-100/50 border-l-4 border-red-500 text-red-800 p-5 rounded-2xl shadow-sm fade-in">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation-circle text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">Erreur !</p>
                                    <p class="text-sm">{{ session('error') }}</p>
                                </div>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mb-6 alert-message bg-gradient-to-r from-red-50 to-red-100/50 border-l-4 border-red-500 text-red-800 p-5 rounded-2xl shadow-sm fade-in">
                        <div class="font-semibold mb-3 flex items-center gap-2">
                            <i class="fas fa-times-circle"></i>
                            <span>Des erreurs ont été détectées :</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Page Actions -->
                @hasSection('page-actions')
                    <div class="mb-6 flex justify-end gap-3">
                        @yield('page-actions')
                    </div>
                @endif
                
                <!-- Content with Fade In -->
                <div class="fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- Alpine.js for collapsible menus -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }
        
        // Toggle User Menu
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }
        
        // Toggle Notifications
        function toggleNotifications() {
            const menu = document.getElementById('notificationsMenu');
            menu.classList.toggle('hidden');
        }
        
        // Close menus when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const userBtn = event.target.closest('[onclick="toggleUserMenu()"]');
            if (!userBtn && userMenu && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
            
            const notifMenu = document.getElementById('notificationsMenu');
            const notifBtn = event.target.closest('[onclick="toggleNotifications()"]');
            if (!notifBtn && notifMenu && !notifMenu.contains(event.target)) {
                notifMenu.classList.add('hidden');
            }
        });
        
        // Mark all notifications as read
        const markAllReadButton = document.getElementById('markAllRead');
        if (markAllReadButton) {
            markAllReadButton.addEventListener('click', function() {
                fetch('{{ route("admin.notifications.mark-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                }).then(response => {
                    if (response.ok) {
                        const badge = document.getElementById('notificationBadge');
                        if (badge) {
                            badge.textContent = '';
                        }
                        markAllReadButton.classList.add('hidden');
                        document.querySelectorAll('#notificationsList a').forEach(item => {
                            const dot = item.querySelector('div.w-2.h-2');
                            if (dot) {
                                dot.classList.remove('bg-indigo-500', 'animate-pulse');
                                dot.classList.add('bg-slate-300');
                            }
                        });
                    }
                });
            });
        }
        
        // Global search functionality
        const searchInput = document.getElementById('globalSearchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                const searchTerm = this.value.toLowerCase();
                window.dispatchEvent(new CustomEvent('admin-search', { detail: searchTerm }));
            });
        }
        
        // Auto-hide only alert banners after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert-message').forEach(alert => {
                if (!alert.closest('#userMenu') && !alert.closest('#notificationsMenu')) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
        
        // Add animation on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fade-in').forEach(el => {
                el.classList.add('animate-fade-in');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>