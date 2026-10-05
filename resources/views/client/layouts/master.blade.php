<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Billetterie') - Plateforme de Billetterie</title>
    
    <!-- Tailwind CSS -->
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
                        }
                    },
                    fontFamily: {
                        'inter': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(0)' },
                        },
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #94a3b8, #64748b);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #64748b, #475569);
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #0a0f1c 100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-link {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-link::before {
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
        
        .sidebar-link:hover::before {
            transform: scaleY(1);
        }
        
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(5px);
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(139, 92, 246, 0.15));
            color: white;
        }
        
        .sidebar-link.active::before {
            transform: scaleY(1);
        }
        
        /* Header Styles */
        .glass-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }
        
        /* Card Styles */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }
        
        /* Button Styles */
        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);
        }
        
        /* Notification Badge */
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }
        
        /* Avatar Initials */
        .avatar-initials {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .avatar-initials:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        }
        
        /* Alert Styles */
        .alert-slide {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec489a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
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
        
        /* Table Styles */
        .table-row {
            transition: all 0.2s ease;
        }
        
        .table-row:hover {
            background: linear-gradient(90deg, #fefce8, #fff7ed);
        }
        
        /* User Menu Styles */
        .user-menu-item {
            transition: all 0.2s ease;
        }
        
        .user-menu-item:hover {
            background: linear-gradient(90deg, #f3f4f6, #ffffff);
            padding-left: 1rem;
        }
        
        /* Divider */
        .menu-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
            margin: 0.5rem 0;
        }
    </style>
    
    @stack('styles')
</head>

<body class="antialiased">
    <div x-data="{ sidebarOpen: false, userMenuOpen: false }" class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar w-80 flex-shrink-0 fixed md:relative h-full z-30 transform transition-transform duration-300 ease-in-out md:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex flex-col h-full">
                <!-- Logo Area -->
                <div class="px-6 pt-8 pb-6 border-b border-white/10 relative">
                    @php
                        $appName = config('app.name', 'TicketFlow');
                        $logoUrl = \App\Models\Setting::getUrl('site_logo');
                    @endphp
                    <div class="flex items-center gap-3">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $appName }}" class="w-12 h-12 rounded-2xl object-contain shadow-lg" />
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                                <i class="fas fa-ticket-alt text-white text-xl"></i>
                            </div>
                        @endif
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                                {{ $appName }}
                            </h1>
                            <p class="text-[10px] text-indigo-300/80 tracking-wider mt-0.5 uppercase">PLATEFORME BILLETTERIE</p>
                        </div>
                    </div>
                </div>
                
                <!-- User Profile Card -->
                <div class="mx-5 mt-6 p-5 rounded-2xl bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-sm border border-white/20">
                    <div class="flex items-center gap-4">
                        <div class="relative group">
                            <div class="relative">
                            @if(Auth::user()->profile_image && Storage::disk('public')->exists(Auth::user()->profile_image))
                                <img src="{{ Storage::url(Auth::user()->profile_image) }}" alt="Profil" class="w-14 h-14 object-cover rounded-2xl shadow-lg" />
                            @else
                                <div class="w-14 h-14 rounded-2xl avatar-initials flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold text-xl">
                                        {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-slate-900 rounded-full animate-pulse"></div>
                        </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-base font-bold text-white">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                            <p class="text-xs text-indigo-200/80 mt-0.5 flex items-center gap-1">
                                <i class="fas fa-user text-[10px]"></i>
                                Client
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
                    <a href="{{ route('client.dashboard') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie w-5 text-lg"></i>
                        <span>Tableau de bord</span>
                        @if(request()->routeIs('client.dashboard'))
                            <span class="ml-auto w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                        @endif
                    </a>
                    
                    <a href="{{ route('client.evenements.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('client.evenements.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt w-5 text-lg"></i>
                        <span>Événements</span>
                    </a>
                    
                    <a href="{{ route('client.commandes.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('client.commandes.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart w-5 text-lg"></i>
                        <span>Mes commandes</span>
                    </a>
                    <a href="{{ route('client.panier.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('client.commandes.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart w-5 text-lg"></i>
                        <span>panier</span>
                    </a>
                    
                    <a href="{{ route('client.billets.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('client.billets.*') ? 'active' : '' }}">
                        <i class="fas fa-qrcode w-5 text-lg"></i>
                        <span>Mes billets</span>
                    </a>

                    <a href="{{ route('client.profil.edit') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('client.profil.*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle w-5 text-lg"></i>
                        <span>Mon profil</span>
                    </a>
                </nav>
                
                <!-- Footer -->
                <div class="p-5 border-t border-white/10">
                    <p class="text-center text-[10px] text-gray-500 tracking-wider">© {{ date('Y') }} TicketFlow</p>
                    <p class="text-center text-[8px] text-gray-600/60 mt-1">Version 2.0</p>
                </div>
            </div>
        </aside>
        
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
             x-transition.opacity></div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen overflow-hidden md:ml-80" style="margin:0;padding:0;min-height:100vh;background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);background-clip:border-box;">
            <!-- Top Header -->
            <header class="glass-header sticky top-0 z-10 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-bold gradient-text">@yield('page-title', 'Tableau de bord')</h2>
                        <p class="text-xs text-gray-500 mt-0.5">@yield('page-description', 'Bienvenue sur votre espace client')</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 notification-badge">0</span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-hard border border-gray-100 z-50 overflow-hidden">
                            <div class="p-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                                <h3 class="font-bold text-gray-800">Notifications</h3>
                            </div>
                            <div class="p-8 text-center">
                                <i class="fas fa-bell-slash text-4xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-500">Aucune notification</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu - Clickable Avatar -->
                    <div class="relative">
                        <!-- Avatar Button -->
                        <button @click="userMenuOpen = !userMenuOpen" 
                                class="flex items-center gap-3 focus:outline-none group">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-gray-800">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                                <p class="text-xs text-gray-500">Client</p>
                            </div>
                            @if(Auth::user()->profile_image && Storage::disk('public')->exists(Auth::user()->profile_image))
                                <img src="{{ Storage::url(Auth::user()->profile_image) }}" alt="Profil" class="w-10 h-10 rounded-xl object-cover shadow-md" />
                            @else
                                <div class="w-10 h-10 rounded-xl avatar-initials flex items-center justify-center shadow-md">
                                    <span class="text-white font-bold text-sm">
                                        {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="{'rotate-180': userMenuOpen}"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="userMenuOpen" 
                             @click.away="userMenuOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                            
                            <!-- Header -->
                            <div class="p-5 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    @if(Auth::user()->profile_image && Storage::disk('public')->exists(Auth::user()->profile_image))
                                    <img src="{{ Storage::url(Auth::user()->profile_image) }}" alt="Profil" class="w-14 h-14 rounded-2xl object-cover shadow-lg" />
                                @else
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                                        <span class="text-white font-bold text-xl">
                                            {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                        <p class="font-bold text-gray-800">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                                        <span class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-[10px] font-semibold">
                                            <i class="fas fa-check-circle text-[8px]"></i>
                                            Compte Client
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Menu Items -->
                            <div class="p-2">
                                <!-- Profil Link -->
                                <a href="{{ route('client.profil.edit') }}" 
                                   class="user-menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center">
                                        <i class="fas fa-user-circle text-blue-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Mon profil</p>
                                        <p class="text-xs text-gray-500">Gérer vos informations personnelles</p>
                                    </div>
                                </a>
                                
                                
                                
                                <!-- Divider -->
                                <div class="menu-divider"></div>
                                
                                <!-- Déconnexion Button -->
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" 
                                            class="user-menu-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-red-600 hover:bg-red-50 transition-all duration-200">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-r from-red-100 to-rose-100 flex items-center justify-center">
                                            <i class="fas fa-sign-out-alt text-red-600 text-lg"></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-semibold text-red-600">Déconnexion</p>
                                            <p class="text-xs text-gray-500">Quitter votre session</p>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <!-- Page Content -->
<main class="{{ trim($__env->yieldContent('main-class') ?: 'flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-50 min-h-screen') }}">
    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 p-5 rounded-xl shadow-sm alert-slide">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Succès !</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 p-5 rounded-xl shadow-sm alert-slide">
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
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 p-5 rounded-xl shadow-sm">
            <div class="font-semibold mb-2 flex items-center gap-2">
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
    
    <!-- Main Content -->
    <div class="animate-fade-in">
        @yield('content')
    </div>
</main>
        </div>
    </div>
    
    <script>
        // Auto-hide only alert banners after 5 seconds (avoid hiding non-alert gradient cards/buttons)
        setTimeout(() => {
            document.querySelectorAll('.alert-slide').forEach(alert => {
                if (!alert.closest('[x-show]')) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>