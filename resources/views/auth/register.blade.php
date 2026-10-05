<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription - {{ config('app.name', 'TicketFlow') }}</title>
    
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
                        'fade-in': 'fadeIn 0.6s cubic-bezier(0.23, 1, 0.32, 1)',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float-subtle': 'floatSubtle 8s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(24px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        floatSubtle: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-12px)' },
                        },
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Base & global transparency effect */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            /* Dégradé profond mais subtil pour laisser respirer le logo */
            background: radial-gradient(circle at 20% 30%, rgba(10, 20, 40, 0.88), rgba(2, 8, 20, 0.96));
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* ========= BACKGROUND LOGO : GRAND LOGO TRANSPARENT EN ARRIÈRE-PLAN (chafaf / transparent) ========= */

        /* Le logo géant / image de marque */
        .giant-logo {
            width: 100%;
            height: 100%;
            opacity: 0.1;        /* transparence élégante pour que le texte reste lisible (chafaf) */
            transition: opacity 0.4s ease;
            filter: drop-shadow(0 12px 28px rgba(0,0,0,0.25));
            animation: floatSubtle 14s infinite ease-in-out;
            object-fit: cover;
        }

        /* Ajustement pour grands écrans */
        @media (min-width: 1024px) {
            .giant-logo {
                width: 100%;
                height: 100%;
                opacity: 0.1;
            }
        }

        /* Glassmorphism moderne pour la carte de connexion (transparence + flou) */
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-radius: 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.35), 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid rgba(255, 255, 255, 0.45);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 30px 50px -15px rgba(0, 0, 0, 0.4);
        }

        /* Inputs stylisés avec fond légèrement transparent */
        .input-glass {
            background-color: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(203, 213, 225, 0.6);
            transition: all 0.2s;
        }
        .input-glass:focus {
            background-color: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 10px;
        }

        /* Shine effect sur bouton */
        .shine-effect {
            position: relative;
            overflow: hidden;
        }
        .shine-effect::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 200%;
            height: 200%;
            background: linear-gradient(115deg, rgba(255,255,255,0) 10%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 90%);
            transform: rotate(25deg);
            animation: shineMove 4s infinite;
        }
        @keyframes shineMove {
            0% { transform: translateX(-100%) rotate(25deg); }
            20% { transform: translateX(100%) rotate(25deg); }
            100% { transform: translateX(100%) rotate(25deg); }
        }

        /* Animation d'apparition douce */
        .animate-soft-pop {
            transition: all 0.2s ease;
        }
    </style>
</head>

<body class="antialiased relative">
    <!-- Contenu principal: formulaire d'inscription transparent au-dessus du logo -->
    <div class="relative z-10 min-h-screen flex items-center justify-center p-4 md:p-6">
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
                <img src="{{ $appLogoUrl }}" alt="Background Logo {{ $appNameLogo }}" class="giant-logo" onerror="this.onerror=null; this.style.opacity='0.15'; this.src='https://placehold.co/900x450/1e2a4a/white?text=TicketFlow+Events'">
            @else
                <div class="giant-logo flex flex-col items-center justify-center text-white select-none">
                    <i class="fas fa-ticket-alt text-[14vw] md:text-[11vw] drop-shadow-2xl" style="text-shadow: 0 8px 24px rgba(0,0,0,0.25);"></i>
                    <span class="text-5xl md:text-7xl font-black tracking-wider mt-4 bg-gradient-to-r from-blue-200 to-indigo-100 bg-clip-text text-transparent" style="text-shadow: 0 2px 12px rgba(0,0,0,0.2);">TicketFlow</span>
                    <span class="text-xl md:text-2xl font-medium mt-2 text-white/60">Événements & Billeterie</span>
                </div>
            @endif
        </div>
        <div class="w-full max-w-5xl animate-fade-in">
            <!-- Carte principale avec glass + transparence -->
            <div class="glass-card overflow-hidden flex flex-col md:flex-row shadow-2xl">
                
                <!-- PARTIE GAUCHE : Branding et fonctionnalités (verre avec dégradé) -->
                <div class="w-full md:w-1/2 bg-gradient-to-br from-blue-700/85 to-indigo-800/85 backdrop-blur-sm p-8 lg:p-10 text-white relative">
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
                            <img src="{{ $appLogoUrl }}" alt="Background Logo {{ $appNameLogo }}" class="giant-logo" onerror="this.onerror=null; this.style.opacity='0.15'; this.src='https://placehold.co/900x450/1e2a4a/white?text=TicketFlow+Events'">
                        @else
                            <div class="giant-logo flex flex-col items-center justify-center text-white select-none">
                                <i class="fas fa-ticket-alt text-[14vw] md:text-[11vw] drop-shadow-2xl" style="text-shadow: 0 8px 24px rgba(0,0,0,0.25);"></i>
                                <span class="text-5xl md:text-7xl font-black tracking-wider mt-4 bg-gradient-to-r from-blue-200 to-indigo-100 bg-clip-text text-transparent" style="text-shadow: 0 2px 12px rgba(0,0,0,0.2);">TicketFlow</span>
                                <span class="text-xl md:text-2xl font-medium mt-2 text-white/60">Événements & Billeterie</span>
                            </div>
                        @endif
                    </div>
                    <!-- Éléments décoratifs -->
                    <div class="absolute top-0 right-0 w-56 h-56 bg-white/10 rounded-full -mt-20 -mr-20 blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full -mb-16 -ml-16"></div>
                    
                    <div class="relative z-10 flex flex-col h-full">
                        <!-- Logo mini + nom app -->
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm overflow-hidden shadow-md">
                                @if(!empty($appLogoUrl))
                                    <img src="{{ $appLogoUrl }}" alt="{{ $appNameLogo }}" class="w-full h-full object-contain" />
                                @else
                                    <i class="fas fa-ticket-alt text-white text-2xl"></i>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-xl font-bold tracking-tight">{{ $appNameLogo ?? 'TicketFlow' }}</h2>
                                <p class="text-blue-100 text-[11px]">plateforme événementielle</p>
                            </div>
                        </div>

                        <!-- Titre accrocheur -->
                        <div class="mb-6">
                            <h3 class="text-3xl lg:text-4xl font-extrabold leading-tight">Créez votre<br>compte</h3>
                            <div class="w-16 h-1 bg-blue-300 rounded-full mt-3"></div>
                        </div>
                        <p class="text-blue-50 text-base leading-relaxed mb-8">
                            Rejoignez la plateforme qui simplifie l'achat et la gestion de vos billets d'événements.
                        </p>

                        <!-- Avantages -->
                        <div class="space-y-4 flex-1">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 bg-white/25 rounded-lg flex items-center justify-center"><i class="fas fa-ticket-alt text-xs"></i></div>
                                <span class="text-sm font-medium">Achetez vos billets en quelques clics</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 bg-white/25 rounded-lg flex items-center justify-center"><i class="fas fa-qrcode text-xs"></i></div>
                                <span class="text-sm font-medium">Billets avec QR Code sécurisé</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 bg-white/25 rounded-lg flex items-center justify-center"><i class="fas fa-history text-xs"></i></div>
                                <span class="text-sm font-medium">Historique complet de vos achats</span>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/20 text-center text-blue-100 text-xs">
                            © {{ date('Y') }} TicketFlow — Solutions événementielles
                        </div>
                    </div>
                </div>

                <!-- PARTIE DROITE : FORMULAIRE D'INSCRIPTION (style verre transparent) -->
                <div class="w-full md:w-1/2 p-8 lg:p-10 bg-white/70 backdrop-blur-md">
                    <div class="max-w-sm mx-auto">
                        <!-- icône décorative -->
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg">
                                <i class="fas fa-user-plus text-white text-2xl"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-gray-800 mt-3">Inscription</h4>
                            <p class="text-gray-500 text-sm">Créez votre compte en quelques secondes</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="space-y-5">
                            @csrf

                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-user text-blue-500 mr-1"></i> Nom complet</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                           class="input-glass w-full pl-10 pr-3 py-3 rounded-xl border focus:ring-2 focus:ring-blue-400 outline-none transition"
                                           placeholder="Jean Dupont">
                                </div>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="far fa-envelope text-blue-500 mr-1"></i> Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                           class="input-glass w-full pl-10 pr-3 py-3 rounded-xl border focus:ring-2 focus:ring-blue-400 outline-none transition"
                                           placeholder="exemple@email.com">
                                </div>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-lock text-blue-500 mr-1"></i> Mot de passe</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <input id="password" type="password" name="password" required autocomplete="new-password"
                                           class="input-glass w-full pl-10 pr-12 py-3 rounded-xl border focus:ring-2 focus:ring-blue-400 outline-none transition"
                                           placeholder="··········">
                                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-blue-600">
                                        <i id="passwordIcon" class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-check-circle text-blue-500 mr-1"></i> Confirmer le mot de passe</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                           class="input-glass w-full pl-10 pr-12 py-3 rounded-xl border focus:ring-2 focus:ring-blue-400 outline-none transition"
                                           placeholder="··········">
                                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-blue-600">
                                        <i id="confirmPasswordIcon" class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Terms -->
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="terms" name="terms" required class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="terms" class="text-sm text-gray-600">
                                    J'accepte les <a href="#" class="text-blue-600 hover:underline">conditions générales</a> et la <a href="#" class="text-blue-600 hover:underline">politique de confidentialité</a>
                                </label>
                            </div>
                            @error('terms') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                            <!-- Hidden fields -->
                            <input type="hidden" name="prenom" value="{{ old('prenom', explode(' ', old('name'))[0] ?? '') }}">
                            <input type="hidden" name="nom" value="{{ old('nom', explode(' ', old('name'))[1] ?? '') }}">
                            <input type="hidden" name="role" value="client">

                            <!-- Bouton Inscription -->
                            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.01] shine-effect">
                                <i class="fas fa-user-plus mr-2"></i> S'inscrire
                            </button>

                            <!-- Lien Connexion -->
                            <a href="{{ route('login') }}" class="block w-full text-center mt-2 py-3 border border-blue-500/60 text-blue-700 font-semibold rounded-xl hover:bg-blue-50 transition-colors">
                                <i class="fas fa-arrow-right-to-bracket mr-2"></i> Déjà un compte ?
                            </a>

                            <!-- Lien Dashboard -->
                            <a href="/" class="block w-full text-center mt-2 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                <i class="fas fa-home mr-2"></i> Retour à l'accueil
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId === 'password' ? 'passwordIcon' : 'confirmPasswordIcon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Auto-fill prenom and nom based on name input
        const nameInput = document.getElementById('name');
        const prenomInput = document.querySelector('input[name="prenom"]');
        const nomInput = document.querySelector('input[name="nom"]');
        
        if (nameInput && prenomInput && nomInput) {
            nameInput.addEventListener('input', function() {
                const nameParts = this.value.trim().split(' ');
                if (nameParts.length >= 1) {
                    prenomInput.value = nameParts[0];
                    if (nameParts.length >= 2) {
                        nomInput.value = nameParts.slice(1).join(' ');
                    } else {
                        nomInput.value = '';
                    }
                }
            });
        }

        // Gestion dynamique de l'opacité du grand logo en arrière-plan lors du scroll pour un effet subtil
        document.addEventListener('DOMContentLoaded', () => {
            const bgLogoElement = document.querySelector('.giant-logo');
            if (bgLogoElement) {
                window.addEventListener('scroll', () => {
                    const scrollY = window.scrollY;
                    let newOpacity = 0.1 + (scrollY * 0.0004);
                    if (newOpacity > 0.26) newOpacity = 0.26;
                    if (newOpacity < 0.05) newOpacity = 0.05;
                    bgLogoElement.style.opacity = newOpacity;
                });
            }
        });
    </script>
</body>
</html>