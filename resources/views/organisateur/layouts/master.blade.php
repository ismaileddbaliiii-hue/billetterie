<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Organisateur') - TicketFlow</title>
    
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
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        
        .sidebar-link:hover::before,
        .sidebar-link.active::before {
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
        
        /* Header Styles */
        .glass-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
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
        
        /* Permission Badge */
        .permission-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .permission-active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .permission-inactive {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
        }
        
        /* Card Hover */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-publie { background: #d1fae5; color: #065f46; }
        .status-brouillon { background: #fef3c7; color: #92400e; }
        .status-termine { background: #e0e7ff; color: #3730a3; }
        .status-annule { background: #fee2e2; color: #991b1b; }
        
        /* Alert Animation */
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
        
        /* User Menu */
        .user-menu-item {
            transition: all 0.2s ease;
        }
        
        .user-menu-item:hover {
            background: linear-gradient(90deg, #f3f4f6, #ffffff);
            padding-left: 1rem;
        }
        
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
                <div class="px-6 pt-8 pb-6 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        @php
                            $appLogo = \App\Models\Setting::get('site_logo', config('app.logo'));
                            $logoUrl = null;
                            if ($appLogo) {
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($appLogo)) {
                                    $logoUrl = \Illuminate\Support\Facades\Storage::url($appLogo);
                                } elseif (file_exists(public_path('storage/' . $appLogo))) {
                                    $logoUrl = asset('storage/' . $appLogo);
                                } elseif (file_exists(public_path($appLogo))) {
                                    $logoUrl = asset($appLogo);
                                }
                            }
                        @endphp
                        <div class="w-12 h-12 rounded-2xl overflow-hidden bg-white/20 border border-white/30 flex items-center justify-center shadow-lg">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="TicketFlow Logo" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-ticket-alt text-white text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                                TicketFlow
                            </h1>
                            <p class="text-[10px] text-indigo-300/80 tracking-wider mt-0.5 uppercase">ESPACE ORGANISATEUR</p>
                        </div>
                    </div>
                </div>
                
                <!-- User Profile Card -->
                <div class="mx-5 mt-6 p-5 rounded-2xl bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-sm border border-white/20">
                    <div class="flex items-center gap-4">
                        <div class="relative group">
                            @php $profileImageUrl = Auth::user()->profile_image_url; @endphp
                            <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-white/30 shadow-lg">
                                @if($profileImageUrl)
                                    <img src="{{ $profileImageUrl }}" alt="{{ Auth::user()->prenom }}" class="w-full h-full object-cover">
                                @else
                                    <div class="avatar-initials w-full h-full flex items-center justify-center">
                                        <span class="text-white font-bold text-xl">
                                            {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-slate-900 rounded-full animate-pulse"></div>
                        </div>
                        <div class="flex-1">
                            <p class="text-base font-bold text-white">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                            <p class="text-xs text-indigo-200/80 mt-0.5 flex items-center gap-1">
                                <i class="fas fa-chalkboard-user text-[10px]"></i>
                                Organisateur
                            </p>
                            <p class="text-xs text-white/80">Organisateur Exemple</p>
                            <p class="text-xs text-white/80">OE • dsaf</p>
                            <p class="text-xs text-white/80">28/03/2026 23:49 - sfdg Contact</p>

                            <a href="{{ route('organisateur.profil.edit') }}" class="mt-2 inline-flex items-center gap-2 text-white text-xs font-semibold hover:text-blue-200">
                                <i class="fas fa-user-circle text-xs"></i>
                                Mon profil (hnaa)
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Permissions Summary -->
                <div class="mx-5 mt-4 p-3 rounded-xl bg-white/5 border border-white/10">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-2">Mes Permissions</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="permission-badge {{ Auth::user()->perm_creation_event ? 'permission-active' : 'permission-inactive' }}">
                            <i class="fas fa-plus-circle text-xs mr-1"></i> Créer
                        </span>
                        <span class="permission-badge {{ Auth::user()->perm_modification_event ? 'permission-active' : 'permission-inactive' }}">
                            <i class="fas fa-edit text-xs mr-1"></i> Modifier
                        </span>
                        <span class="permission-badge {{ Auth::user()->perm_suppression_event ? 'permission-active' : 'permission-inactive' }}">
                            <i class="fas fa-trash-alt text-xs mr-1"></i> Supprimer
                        </span>
                        <span class="permission-badge {{ Auth::user()->perm_view_stats ? 'permission-active' : 'permission-inactive' }}">
                            <i class="fas fa-chart-line text-xs mr-1"></i> Statistiques
                        </span>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
                    <a href="{{ route('organisateur.dashboard') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('organisateur.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie w-5 text-lg"></i>
                        <span>Tableau de bord</span>
                    </a>
                    
                    @if(Auth::user()->perm_creation_event)
                        <a href="{{ route('organisateur.evenements.create') }}" 
                           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                            <i class="fas fa-plus-circle w-5 text-lg text-emerald-400"></i>
                            <span>Créer un événement</span>
                        </a>
                    @endif
                    
                    <a href="{{ route('organisateur.evenements.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('organisateur.evenements.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt w-5 text-lg"></i>
                        <span>Mes événements</span>
                    </a>
                    
                    <a href="{{ route('organisateur.ventes.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('organisateur.ventes.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line w-5 text-lg"></i>
                        <span>Mes ventes</span>
                    </a>
                    
                    <a href="{{ route('organisateur.billets.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('organisateur.billets.*') ? 'active' : '' }}">
                        <i class="fas fa-qrcode w-5 text-lg"></i>
                        <span>Vérifier billets</span>
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
        <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
            <!-- Top Header -->
            <header class="glass-header sticky top-0 z-10 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-bold gradient-text">@yield('page-title', 'Tableau de bord')</h2>
                        <p class="text-xs text-gray-500 mt-0.5">@yield('page-description', 'Gérez vos événements et suivez vos ventes')</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- User Menu -->
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" 
                                class="flex items-center gap-3 focus:outline-none group">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-gray-800">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                                <p class="text-xs text-gray-500">Organisateur</p>
                            </div>
                            @php $menuProfileImage = Auth::user()->profile_image_url; @endphp
                            <div class="w-10 h-10 rounded-xl overflow-hidden shadow-md">
                                @if($menuProfileImage)
                                    <img src="{{ $menuProfileImage }}" alt="Photo de profil" class="w-full h-full object-cover">
                                @else
                                    <div class="avatar-initials w-full h-full flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600">
                                        <span class="text-white font-bold text-sm">
                                            {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="{'rotate-180': userMenuOpen}"></i>
                        </button>
                        
                        <div x-show="userMenuOpen" 
                             @click.away="userMenuOpen = false"
                             x-transition
                             class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                            
                            <div class="p-5 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    @php $menuProfileImageLarge = Auth::user()->profile_image_url; @endphp
                            <div class="w-14 h-14 rounded-2xl overflow-hidden shadow-lg">
                                @if($menuProfileImageLarge)
                                    <img src="{{ $menuProfileImageLarge }}" alt="Photo de profil" class="w-full h-full object-cover">
                                @else
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 w-full h-full flex items-center justify-center">
                                        <span class="text-white font-bold text-xl">
                                            {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                        <p class="font-bold text-gray-800">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                                        <span class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-[10px] font-semibold">
                                            <i class="fas fa-chalkboard-user text-[8px]"></i>
                                            Organisateur
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-2">
                                <a href="{{ route('organisateur.profil.edit') }}" 
                                   class="user-menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center">
                                        <i class="fas fa-user-circle text-blue-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Mon profil</p>
                                        <p class="text-xs text-gray-500">Gérer vos informations</p>
                                    </div>
                                </a>
                                
                                <div class="menu-divider"></div>
                                
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
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
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
                
                <!-- Main Content -->
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>