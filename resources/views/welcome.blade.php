{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteName ?? config('app.name', 'BilletteriePro') }} - Découvrez vos prochains événements</title>
    <meta name="description" content="{{ $siteDescription ?? 'Plateforme de billetterie en ligne. Réservez vos places pour concerts, spectacles et événements.' }}">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        glow: {
                            '0%, 100%': { boxShadow: '0 0 5px rgba(139, 92, 246, 0.5)' },
                            '50%': { boxShadow: '0 0 20px rgba(139, 92, 246, 0.8)' },
                        },
                        slideUp: {
                            from: { transform: 'translateY(20px)', opacity: '0' },
                            to: { transform: 'translateY(0)', opacity: '1' },
                        },
                        scaleIn: {
                            from: { transform: 'scale(0.9)', opacity: '0' },
                            to: { transform: 'scale(1)', opacity: '1' },
                        },
                    },
                }
            }
        }
    </script>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        @keyframes fadeInUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
            50% { box-shadow: 0 10px 30px -3px rgba(139, 92, 246, 0.4); }
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        .animate-fade-up { animation: fadeInUp 0.5s ease-out forwards; }
        .animate-pulse-glow { animation: pulseGlow 3s infinite; }
        [x-cloak] { display: none !important; }
        
        .hero-gradient { 
            background: linear-gradient(135deg, #1e1b2e 0%, #2d1b4e 50%, #1a1a2e 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(139, 92, 246, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .hero-gradient::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            opacity: 0.5;
        }
        
        .card-hover { 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .card-hover:hover { 
            transform: translateY(-8px) scale(1.02); 
            box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.25); 
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .category-active {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }
        
        .category-pill {
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid #e2e8f0;
            background: white;
            color: #4a5568;
        }
        .category-pill:hover {
            border-color: #8b5cf6;
            color: #8b5cf6;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(139, 92, 246, 0.2);
        }
        
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .gradient-border {
            position: relative;
            background: white;
            border-radius: 1rem;
        }
        
        .gradient-border::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #8b5cf6, #3b82f6, #06b6d4);
            border-radius: 1rem;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gradient-border:hover::before {
            opacity: 1;
        }

        /* Logo styles */
        .logo-container {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(59, 130, 246, 0.2));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .logo-glow {
            animation: logoGlow 3s ease-in-out infinite;
        }

        @keyframes logoGlow {
            0%, 100% { filter: drop-shadow(0 0 5px rgba(139, 92, 246, 0.5)); }
            50% { filter: drop-shadow(0 0 20px rgba(139, 92, 246, 0.8)); }
        }

        /* Giant Logo Background */
        .giant-logo {
            width: 100%;
            height: 100%;
            opacity: 0.1;
            transition: opacity 0.4s ease;
            filter: drop-shadow(0 12px 28px rgba(0,0,0,0.25));
            animation: floatSubtle 14s infinite ease-in-out;
            object-fit: cover;
        }

        @media (min-width: 1024px) {
            .giant-logo {
                width: 100%;
                height: 100%;
                opacity: 0.1;
            }
        }

        @keyframes floatSubtle {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800" x-data="{ 
    mobileMenu: false,
    selectedCategory: 'all',
    showAll: false,
    showAvailable: false
}" x-cloak>

    {{-- ========== NAVIGATION ========== --}}
    <nav class="fixed w-full top-0 z-50 glass-effect shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                
                {{-- Logo Large avec transparence --}}
                <a href="#evenements" class="flex items-center space-x-4 group cursor-pointer">
                    <div class="relative">
                        <!-- Logo avec grand cercle transparent -->
                        <div class="w-16 h-16 rounded-2xl logo-container flex items-center justify-center logo-glow">
                            @if(!empty($siteLogoUrl))
                                <img src="{{ $siteLogoUrl }}" alt="{{ $siteName ?? config('app.name', 'TicketFlow') }}" 
                                     class="w-14 h-14 object-contain" 
                                     style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));"
                                     onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-ticket-alt text-3xl\' style=\'background: linear-gradient(135deg, #8b5cf6, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;\'></i>'">
                            @else
                                <div class="relative">
                                    <i class="fas fa-ticket-alt text-4xl" style="background: linear-gradient(135deg, #8b5cf6, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full blur-xl opacity-50 -z-10"></div>
                                </div>
                            @endif
                        </div>
                        <!-- Effet de brillance -->
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-purple-500/20 to-blue-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-black text-2xl tracking-tight block leading-tight" 
                              style="background: linear-gradient(135deg, #1e1b2e 0%, #2d1b4e 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ $siteName ?? config('app.name', 'TicketFlow') }}
                        </span>
                        <span class="text-xs text-gray-500 font-medium tracking-wide">{{ \App\Models\Setting::get('site_description', 'Votre billetterie moderne') }}</span>
                    </div>
                </a>

                {{-- Navigation Links Desktop --}}
                <div class="hidden md:flex items-center space-x-10">
                    <a href="#evenements" class="text-gray-700 hover:text-purple-600 font-semibold transition-all duration-300 py-2 border-b-2 border-transparent hover:border-purple-600 relative group">
                        Événements
                        <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-purple-600 to-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                    <a href="#comment-ca-marche" class="text-gray-700 hover:text-purple-600 font-semibold transition-all duration-300 py-2 border-b-2 border-transparent hover:border-purple-600 relative group">
                        Comment ça marche
                        <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-purple-600 to-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                    <a href="#contact" class="text-gray-700 hover:text-purple-600 font-semibold transition-all duration-300 py-2 border-b-2 border-transparent hover:border-purple-600 relative group">
                        Contact
                        <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-purple-600 to-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                </div>

                {{-- Actions Header --}}
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex px-5 py-2.5 text-sm font-semibold text-gray-700 hover:text-purple-700 hover:bg-purple-50 rounded-xl transition-all duration-200">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-flex px-6 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl text-sm font-semibold text-white hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        Inscription gratuite
                    </a>

                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-3 rounded-xl text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition-all duration-200">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenu"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenu" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden border-t border-gray-200 bg-white/95 backdrop-blur-lg shadow-xl">
            <div class="px-4 py-4 space-y-2">
                <a href="#evenements" @click="mobileMenu = false" class="flex items-center px-4 py-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg font-semibold transition-all duration-200">
                    <i class="fas fa-calendar-alt w-6"></i> Événements
                </a>
                <a href="#comment-ca-marche" @click="mobileMenu = false" class="flex items-center px-4 py-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg font-semibold transition-all duration-200">
                    <i class="fas fa-question-circle w-6"></i> Comment ça marche
                </a>
                <a href="#pour-organisateurs" @click="mobileMenu = false" class="flex items-center px-4 py-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg font-semibold transition-all duration-200">
                    <i class="fas fa-users w-6"></i> Organisateurs
                </a>
                <a href="#contact" @click="mobileMenu = false" class="flex items-center px-4 py-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg font-semibold transition-all duration-200">
                    <i class="fas fa-envelope w-6"></i> Contact
                </a>
                <hr class="my-2 border-gray-200">
                <a href="{{ route('login') }}" @click="mobileMenu = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-sign-in-alt w-6"></i> Connexion
                </a>
                <a href="{{ route('register') }}" @click="mobileMenu = false" class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg font-semibold">
                    <i class="fas fa-user-plus w-6"></i> Inscription gratuite
                </a>
            </div>
        </div>
    </nav>

    {{-- ========== HERO SECTION MODERNISÉE (Logo Transparent + Lien Events) ========== --}}
    <section class="hero-gradient text-white pt-32 pb-24 lg:pt-44 lg:pb-32 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
        </div>

        <!-- Giant Logo Background -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            @php
                $appLogoUrl = null;
                $appNameLogo = 'TicketFlow';
                
                if (class_exists('\\App\\Models\\Setting')) {
                    try {
                        $appLogoUrl = \App\Models\Setting::getUrl('site_logo');
                        $appNameLogo = \App\Models\Setting::get('site_name', 'TicketFlow');
                    } catch (\Exception $e) {
                        $appLogoUrl = null;
                    }
                }
            @endphp
            
            @if(!empty($appLogoUrl))
                <img src="{{ $appLogoUrl }}" alt="Background Logo {{ $appNameLogo }}" class="giant-logo" onerror="this.onerror=null; this.style.opacity='0.1'; this.src='https://placehold.co/900x450/1e2a4a/white?text=TicketFlow+Events'">
            @else
                <div class="giant-logo flex flex-col items-center justify-center text-white select-none">
                    <i class="fas fa-ticket-alt text-[20vw] md:text-[15vw] drop-shadow-2xl" style="text-shadow: 0 8px 24px rgba(0,0,0,0.25);"></i>
                    <span class="text-6xl md:text-8xl font-black tracking-wider mt-4 bg-gradient-to-r from-blue-200 to-indigo-100 bg-clip-text text-transparent" style="text-shadow: 0 2px 12px rgba(0,0,0,0.2);">TicketFlow</span>
                    <span class="text-2xl md:text-3xl font-medium mt-2 text-white/60">Événements & Billeterie</span>
                </div>
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto animate-fade-up">
                
                {{-- Logo Hero Large avec transparence ET lien vers événements --}}
                <a href="#evenements" class="inline-block group cursor-pointer">
                    <div class="relative">
                        <!-- Cercle transparent avec effet glassmorphism -->
                        <div class="w-40 h-40 rounded-3xl bg-white/10 backdrop-blur-md flex items-center justify-center logo-glow border border-white/30 transition-all duration-500 group-hover:scale-110 group-hover:bg-white/20">
                            @if(!empty($siteLogoUrl))
                                <img src="{{ $siteLogoUrl }}" alt="{{ $siteName ?? config('app.name', 'TicketFlow') }}" 
                                     class="w-32 h-32 object-contain"
                                     style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));">
                            @else
                                <i class="fas fa-ticket-alt text-7xl" style="background: linear-gradient(135deg, #fff, #e0e7ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            @endif
                        </div>
                        <!-- Effet de brillance au survol -->
                        <div class="absolute inset-0 rounded-3xl bg-gradient-to-r from-purple-500/20 to-blue-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <!-- Pulse ring -->
                        <div class="absolute inset-0 rounded-3xl border-2 border-purple-400/50 animate-ping"></div>
                    </div>
                    <p class="text-sm text-purple-200 mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <i class="fas fa-arrow-down mr-1"></i> Découvrir les événements
                    </p>
                </a>

                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black mb-6 leading-tight mt-8">
                    Découvrez et réservez vos 
                    <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">événements préférés</span>
                </h1>
                <p class="text-xl sm:text-2xl text-purple-100 mb-10 max-w-2xl mx-auto">
                    Concerts, spectacles, conférences, sport... Trouvez l'événement parfait et réservez vos places en quelques clics.
                </p>

                {{-- Boutons CTA --}}
                <div class="flex justify-center gap-4 flex-wrap">
                    <a href="#evenements" 
                       class="inline-flex items-center px-8 py-4 bg-white text-purple-600 font-bold rounded-xl hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                        Explorer les événements
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold rounded-xl hover:shadow-xl transition-all duration-200 transform hover:scale-105 border border-white/20">
                        Créer un compte
                        <i class="fas fa-user-plus ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== EVENTS SECTION ========== --}}
    <section id="evenements" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Section Header --}}
            <div class="text-center mb-12 animate-fade-up">
                <span class="text-purple-600 font-semibold text-sm uppercase tracking-wider">Découvrez</span>
                <h2 class="text-4xl font-black text-gray-900 mb-4 mt-2">Événements à venir</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Découvrez notre sélection d'événements publiés. Consultez les détails et réservez vos places !
                </p>
            </div>

            {{-- ========== CATEGORIES NAVIGATION MODERNISÉE ========== --}}
            <div class="mb-12 animate-fade-up" style="animation-delay: 0.2s">
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <button @click="selectedCategory = 'all'" 
                            :class="{ 'category-active': selectedCategory === 'all', 'category-pill': selectedCategory !== 'all' }"
                            class="px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Tous
                    </button>
                    <button @click="selectedCategory = 'sport'" 
                            :class="{ 'category-active': selectedCategory === 'sport', 'category-pill': selectedCategory !== 'sport' }"
                            class="px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300">
                        <i class="fas fa-futbol mr-2"></i>
                        Sport
                    </button>
                    <button @click="selectedCategory = 'concert'" 
                            :class="{ 'category-active': selectedCategory === 'concert', 'category-pill': selectedCategory !== 'concert' }"
                            class="px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300">
                        <i class="fas fa-music mr-2"></i>
                        Concert
                    </button>
                    <button @click="selectedCategory = 'theatre'" 
                            :class="{ 'category-active': selectedCategory === 'theatre', 'category-pill': selectedCategory !== 'theatre' }"
                            class="px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300">
                        <i class="fas fa-mask mr-2"></i>
                        Théâtre
                    </button>
                    <button @click="selectedCategory = 'festival'" 
                            :class="{ 'category-active': selectedCategory === 'festival', 'category-pill': selectedCategory !== 'festival' }"
                            class="px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300">
                        <i class="fas fa-tree mr-2"></i>
                        Festival
                    </button>
                    <button @click="selectedCategory = 'conference'" 
                            :class="{ 'category-active': selectedCategory === 'conference', 'category-pill': selectedCategory !== 'conference' }"
                            class="px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300">
                        <i class="fas fa-chalkboard-user mr-2"></i>
                        Conférence
                    </button>
                    <button @click="selectedCategory = 'exposition'" 
                            :class="{ 'category-active': selectedCategory === 'exposition', 'category-pill': selectedCategory !== 'exposition' }"
                            class="px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300">
                        <i class="fas fa-palette mr-2"></i>
                        Exposition
                    </button>
                </div>
            </div>

            {{-- Availability Filter --}}
            <div class="mb-8 flex justify-center">
                <button @click="showAvailable = !showAvailable" 
                        :class="showAvailable ? 'bg-green-100 border-green-500 text-green-700' : 'bg-gray-100 border-gray-300 text-gray-600'"
                        class="inline-flex items-center px-5 py-2.5 border-2 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-md">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span x-show="!showAvailable">Afficher tous</span>
                    <span x-show="showAvailable">Places disponibles</span>
                </button>
            </div>

            {{-- Events Grid --}}
            @if(isset($evenements) && $evenements->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($evenements as $index => $evenement)
                        <div class="gradient-border rounded-2xl overflow-hidden shadow-sm bg-white card-hover animate-fade-up" 
                             style="animation-delay: {{ $index * 0.1 }}s"
                             x-show="(selectedCategory === 'all' || selectedCategory === '{{ strtolower($evenement->type) }}') && (showAll || {{ $index }} < 8) && (!showAvailable || {{ $evenement->places_restantes }} > 0)"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100">
                            
                            {{-- ========== IMAGE SECTION ========== --}}
                            <div class="relative h-56 overflow-hidden bg-gradient-to-br from-purple-500 to-blue-600">
                                @if($evenement->affiche)
                                    <img src="{{ asset('storage/' . ltrim($evenement->affiche, '/')) }}"
                                         alt="{{ $evenement->titre }}"
                                         class="event-image w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-icon').style.display='flex'">
                                    <div class="fallback-icon absolute inset-0 items-center justify-center bg-gradient-to-br from-purple-500 to-blue-600 hidden">
                                        <div class="text-center">
                                            <i class="fas fa-image text-white text-4xl opacity-60"></i>
                                            <p class="text-white text-xs mt-2 opacity-50">Image indisponible</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-6xl opacity-80"></i>
                                    </div>
                                @endif
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                                
                                {{-- Date Badge --}}
                                <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm rounded-xl px-3 py-2 text-center shadow-lg z-10">
                                    <p class="text-xs font-bold text-gray-500 uppercase">{{ \Carbon\Carbon::parse($evenement->date)->isoFormat('MMM') }}</p>
                                    <p class="text-2xl font-black text-gray-900 leading-tight">{{ \Carbon\Carbon::parse($evenement->date)->format('d') }}</p>
                                </div>

                                {{-- Catégorie Badge --}}
                                <div class="absolute top-3 right-3 bg-gradient-to-r from-purple-600 to-blue-600 backdrop-blur-sm rounded-lg px-3 py-1.5 shadow-lg z-10">
                                    <p class="text-xs font-bold text-white flex items-center">
                                        @switch(strtolower($evenement->type ?? ''))
                                            @case('sport')
                                                <i class="fas fa-futbol mr-1"></i>
                                                @break
                                            @case('concert')
                                                <i class="fas fa-music mr-1"></i>
                                                @break
                                            @case('theatre')
                                                <i class="fas fa-mask mr-1"></i>
                                                @break
                                            @case('festival')
                                                <i class="fas fa-tree mr-1"></i>
                                                @break
                                            @case('conference')
                                                <i class="fas fa-chalkboard-user mr-1"></i>
                                                @break
                                            @case('exposition')
                                                <i class="fas fa-palette mr-1"></i>
                                                @break
                                            @default
                                                <i class="fas fa-tag mr-1"></i>
                                        @endswitch
                                        {{ $evenement->type ?? 'Événement' }}
                                    </p>
                                </div>

                                {{-- Availability Badge --}}
                                <div class="absolute bottom-3 right-3 {{ $evenement->places_restantes > 0 ? 'bg-green-500' : 'bg-red-500' }} backdrop-blur-sm rounded-lg px-3 py-1.5 shadow-lg z-10">
                                    <p class="text-xs font-bold text-white flex items-center">
                                        @if($evenement->places_restantes > 0)
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ $evenement->places_restantes }} places
                                        @else
                                            <i class="fas fa-ban mr-1"></i>
                                            Complet
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            {{-- ========== INFO SECTION ========== --}}
                            <div class="p-5 flex-1 flex flex-col">
                                <p class="text-xs font-bold text-purple-600 uppercase tracking-wide mb-2">
                                    {{ $evenement->user->prenom ?? $evenement->user->name ?? 'Organisateur' }}
                                </p>
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                    {{ $evenement->titre }}
                                </h3>
                                <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-1">
                                    {{ Str::limit($evenement->description ?? 'Découvrez cet événement exceptionnel.', 100) }}
                                </p>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt w-5 text-purple-400"></i>
                                        <span class="truncate">
                                            {{ $evenement->lieu->nom ?? 'Lieu à définir' }}
                                            @if(isset($evenement->lieu) && $evenement->lieu->ville)
                                                , {{ $evenement->lieu->ville }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock w-5 text-purple-400"></i>
                                        <span>{{ \Carbon\Carbon::parse($evenement->date)->format('H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- ========== FOOTER SECTION ========== --}}
                            <div class="px-5 pb-5 pt-3 border-t border-gray-100">
                                <div class="flex items-center justify-between">
                                    @php
                                        $prixMin = $evenement->prix_min ?? ($evenement->prix ?? 0);
                                    @endphp
                                    <div>
                                        @if($prixMin > 0)
                                            <p class="text-xs text-gray-500">À partir de</p>
                                            <span class="font-black text-xl text-purple-600">
                                                {{ number_format($prixMin, 0, ',', ' ') }} <span class="text-sm">DH</span>
                                            </span>
                                        @else
                                            <span class="font-black text-xl text-green-600">
                                                Gratuit
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('register') }}" 
                                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-sm font-bold rounded-xl hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-xl transform hover:scale-105">
                                        Réserver
                                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                    </a>
                                </div>
                                <p class="text-xs text-gray-400 mt-3 text-center">
                                    <i class="fas fa-lock mr-1"></i> Inscription gratuite pour réserver
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Toggle Show All Button --}}
                <div class="text-center mt-12 animate-fade-up" style="animation-delay: 0.8s">
                    <button @click="showAll = !showAll" class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold rounded-xl hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                        <span x-show="!showAll">Voir tous les événements</span>
                        <span x-show="showAll">Afficher moins</span>
                        <i class="fas" :class="showAll ? 'fa-chevron-up' : 'fa-arrow-right'" style="margin-left: 0.75rem;"></i>
                    </button>
                </div>
            @else
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-blue-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-times text-purple-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun événement pour le moment</h3>
                    <p class="text-gray-500 mb-6">De nouveaux événements seront bientôt ajoutés. Revenez bientôt !</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ========== COMMENT ÇA MARCHE MODERNISÉ ========== --}}
    <section id="comment-ca-marche" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-purple-600 font-semibold text-sm uppercase tracking-wider">Simple & Rapide</span>
                <h2 class="text-4xl font-black text-gray-900 mb-4 mt-2">Comment ça marche ?</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Réserver vos billets n'a jamais été aussi simple. Suivez ces 3 étapes !
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-purple-50 to-blue-50 transform hover:scale-105 transition-all duration-300">
                    <div class="w-24 h-24 bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-search text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">1. Parcourez</h3>
                    <p class="text-gray-600">Explorez notre catalogue d'événements et trouvez celui qui vous correspond</p>
                </div>
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-purple-50 to-blue-50 transform hover:scale-105 transition-all duration-300">
                    <div class="w-24 h-24 bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-ticket-alt text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">2. Réservez</h3>
                    <p class="text-gray-600">Choisissez vos places et procédez au paiement sécurisé</p>
                </div>
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-purple-50 to-blue-50 transform hover:scale-105 transition-all duration-300">
                    <div class="w-24 h-24 bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-qrcode text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">3. Profitez</h3>
                    <p class="text-gray-600">Recevez vos billets avec QR Code et présentez-les à l'entrée</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== FOOTER MODERNISÉ ========== --}}
    <footer id="contact" class="bg-gray-900 text-gray-300 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-ticket-alt text-white text-sm"></i>
                        </div>
                        <span class="text-white font-bold text-xl">{{ $siteName ?? config('app.name', 'TicketFlow') }}</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">La plateforme de billetterie qui simplifie l'achat et la vente de billets d'événements.</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Liens rapides</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#evenements" class="hover:text-purple-400 transition-colors">Événements</a></li>
                        <li><a href="#comment-ca-marche" class="hover:text-purple-400 transition-colors">Comment ça marche</a></li>
                        <li><a href="#pour-organisateurs" class="hover:text-purple-400 transition-colors">Devenir organisateur</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-purple-400 transition-colors">Centre d'aide</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition-colors">Contactez-nous</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition-colors">CGU</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Suivez-nous</h4>
                    <div class="flex space-x-3">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-gradient-to-r hover:from-purple-600 hover:to-blue-600 transition-all duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-gradient-to-r hover:from-purple-600 hover:to-blue-600 transition-all duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-gradient-to-r hover:from-purple-600 hover:to-blue-600 transition-all duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-gradient-to-r hover:from-purple-600 hover:to-blue-600 transition-all duration-200">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} {{ $siteName ?? config('app.name', 'TicketFlow') }}. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        // Smooth scroll pour les ancres
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>