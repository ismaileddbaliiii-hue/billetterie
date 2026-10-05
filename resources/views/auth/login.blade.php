<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>TicketFlow | Connexion Transparente</title>
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Config with Extended Colors/Animations -->
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
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
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



    <!-- Contenu principal: formulaire de connexion transparent au-dessus du logo -->
    <div class="relative z-10 min-h-screen flex items-center justify-center p-4 md:p-6">
        <div class="absolute inset-0 z-0 pointer-events-none">
            @php
                // Récupération du logo personnalisé depuis les settings (comme dans l'écosystème Laravel original)
                // On tente de charger le logo de l'application pour le mettre en arrière-plan transparent (chafaf)
                // Si des classes helpers existent: \App\Models\Setting::getUrl('site_logo') et \App\Models\Setting::get('site_name', 'TicketFlow')
                // On gère aussi le fallback avec un design TicketFlow élégant.
                $appLogoUrl = null;
                $appNameLogo = 'TicketFlow';
                
                // Vérification d'existence des classes et méthodes (pour environnement Laravel réel)
                if (class_exists('\\App\\Models\\Setting')) {
                    try {
                        $appLogoUrl = \App\Models\Setting::getUrl('site_logo');
                        $appNameLogo = \App\Models\Setting::get('site_name', 'TicketFlow');
                    } catch (\Exception $e) {
                        // fallback silencieux
                        $appLogoUrl = null;
                    }
                }
                
                // Alternative: On peut aussi vérifier une variable de config ou logo par défaut
                // Dans le contexte statique (démo) on garde la possibilité d'avoir un logo custom via une image placeholder ou SVG.
            @endphp
            
            @if(!empty($appLogoUrl))
                <!-- Logo personnalisé de l'application en arrière-plan transparent -->
                <img src="{{ $appLogoUrl }}" alt="Background Logo {{ $appNameLogo }}" class="giant-logo" onerror="this.onerror=null; this.style.opacity='0.15'; this.src='https://placehold.co/900x450/1e2a4a/white?text=TicketFlow+Events'">
            @else
                <!-- Logo par défaut avec style raffiné, très lisible en transparence (chafaf) -->
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
                            // Récupération du logo personnalisé depuis les settings (comme dans l'écosystème Laravel original)
                            // On tente de charger le logo de l'application pour le mettre en arrière-plan transparent (chafaf)
                            // Si des classes helpers existent: \App\Models\Setting::getUrl('site_logo') et \App\Models\Setting::get('site_name', 'TicketFlow')
                            // On gère aussi le fallback avec un design TicketFlow élégant.
                            $appLogoUrl = null;
                            $appNameLogo = 'TicketFlow';
                            
                            // Vérification d'existence des classes et méthodes (pour environnement Laravel réel)
                            if (class_exists('\\App\\Models\\Setting')) {
                                try {
                                    $appLogoUrl = \App\Models\Setting::getUrl('site_logo');
                                    $appNameLogo = \App\Models\Setting::get('site_name', 'TicketFlow');
                                } catch (\Exception $e) {
                                    // fallback silencieux
                                    $appLogoUrl = null;
                                }
                            }
                            
                            // Alternative: On peut aussi vérifier une variable de config ou logo par défaut
                            // Dans le contexte statique (démo) on garde la possibilité d'avoir un logo custom via une image placeholder ou SVG.
                        @endphp
                        
                        @if(!empty($appLogoUrl))
                            <!-- Logo personnalisé de l'application en arrière-plan transparent -->
                            <img src="{{ $appLogoUrl }}" alt="Background Logo {{ $appNameLogo }}" class="giant-logo" onerror="this.onerror=null; this.style.opacity='0.15'; this.src='https://placehold.co/900x450/1e2a4a/white?text=TicketFlow+Events'">
                        @else
                            <!-- Logo par défaut avec style raffiné, très lisible en transparence (chafaf) -->
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
                            <h3 class="text-3xl lg:text-4xl font-extrabold leading-tight">Connexion<br>sécurisée</h3>
                            <div class="w-16 h-1 bg-blue-300 rounded-full mt-3"></div>
                        </div>
                        <p class="text-blue-50 text-base leading-relaxed mb-8">
                            Accédez à votre tableau de bord, gérez vos événements et suivez vos ventes en temps réel avec TicketFlow.
                        </p>

                        <!-- Avantages -->
                        <div class="space-y-4 flex-1">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 bg-white/25 rounded-lg flex items-center justify-center"><i class="fas fa-bolt text-xs"></i></div>
                                <span class="text-sm font-medium">Interface rapide & intuitive</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 bg-white/25 rounded-lg flex items-center justify-center"><i class="fas fa-chart-simple text-xs"></i></div>
                                <span class="text-sm font-medium">Statistiques en temps réel</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 bg-white/25 rounded-lg flex items-center justify-center"><i class="fas fa-shield-heart text-xs"></i></div>
                                <span class="text-sm font-medium">Paiements sécurisés & QR codes</span>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/20 text-center text-blue-100 text-xs">
                            © {{ date('Y') }} TicketFlow — Solutions événementielles
                        </div>
                    </div>
                </div>

                <!-- PARTIE DROITE : FORMULAIRE DE CONNEXION (style verre transparent) -->
                <div class="w-full md:w-1/2 p-8 lg:p-10 bg-white/70 backdrop-blur-md">
                    <div class="max-w-sm mx-auto">
                        <!-- icône décorative -->
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg">
                                <i class="fas fa-user-astronaut text-white text-2xl"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-gray-800 mt-3">Bienvenue</h4>
                            <p class="text-gray-500 text-sm">Connectez-vous à votre espace</p>
                        </div>

                        <!-- Session status messages (compatible Laravel) -->
                        @if(session('status'))
                            <div class="mb-5 p-3 bg-emerald-100/80 border-l-4 border-emerald-500 rounded-xl text-sm text-emerald-800 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="far fa-envelope text-blue-500 mr-1"></i> Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                           class="input-glass w-full pl-10 pr-3 py-3 rounded-xl border focus:ring-2 focus:ring-blue-400 outline-none transition"
                                           placeholder="exemple@ticketflow.ma">
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
                                    <input id="password-field" type="password" name="password" required
                                           class="input-glass w-full pl-10 pr-12 py-3 rounded-xl border focus:ring-2 focus:ring-blue-400 outline-none transition"
                                           placeholder="··········">
                                    <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-blue-600">
                                        <i id="toggleIcon" class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Remember & Forgot -->
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-400">
                                    <span>Se souvenir</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-blue-700 hover:underline font-medium">Mot de passe oublié ?</a>
                                @endif
                            </div>

                            <!-- Bouton Connexion -->
                            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.01] shine-effect">
                                <i class="fas fa-arrow-right-to-bracket mr-2"></i> Se connecter
                            </button>

                            <!-- Lien Inscription -->
                            <a href="{{ route('register') }}" class="block w-full text-center mt-2 py-3 border border-blue-500/60 text-blue-700 font-semibold rounded-xl hover:bg-blue-50 transition-colors">
                                <i class="fas fa-user-plus mr-2"></i> Créer un compte
                            </a>
                            <a href="/" class="block w-full text-center mt-2 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                <i class="fas fa-home mr-2"></i> Retour à l'accueil
                            </a>

                            <!-- Démo credentials (transparence adaptée) -->
                            <div class="mt-6 pt-4 border-t border-gray-300/50">
                                <p class="text-center text-xs text-gray-500 mb-2"><i class="fas fa-flask mr-1"></i> Comptes de démonstration</p>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                                    <div class="demo-card bg-white/70 backdrop-blur-sm rounded-xl p-2 text-center cursor-pointer transition hover:bg-white/90 shadow-sm" data-email="admin@ticketflow.ma" data-pwd="password">
                                        <p class="font-bold text-gray-700">Admin</p>
                                        <p class="text-gray-600 truncate">admin@ticketflow.ma</p>
                                    </div>
                                    <div class="demo-card bg-white/70 backdrop-blur-sm rounded-xl p-2 text-center cursor-pointer transition hover:bg-white/90 shadow-sm" data-email="org@ticketflow.ma" data-pwd="password">
                                        <p class="font-bold text-gray-700">Organisateur</p>
                                        <p class="text-gray-600 truncate">org@ticketflow.ma</p>
                                    </div>
                                    <div class="demo-card bg-white/70 backdrop-blur-sm rounded-xl p-2 text-center cursor-pointer transition hover:bg-white/90 shadow-sm" data-email="client@ticketflow.ma" data-pwd="password">
                                        <p class="font-bold text-gray-700">Client</p>
                                        <p class="text-gray-600 truncate">client@ticketflow.ma</p>
                                    </div>
                                </div>
                                <p class="text-[11px] text-center text-gray-400 mt-2">Cliquez sur un rôle pour remplir automatiquement</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility() {
            const pwdField = document.getElementById('password-field');
            const toggleIcon = document.getElementById('toggleIcon');
            if (pwdField.type === 'password') {
                pwdField.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                pwdField.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }

        // Auto-remplissage pour les comptes démo avec animation légère
        function fillDemoCredentials(email, password) {
            const emailInput = document.querySelector('input[name="email"]');
            const passwordInput = document.querySelector('input[name="password"]');
            if (emailInput && passwordInput) {
                emailInput.value = email;
                passwordInput.value = password;
                // Ajout d'un petit effet visuel
                emailInput.style.transition = 'all 0.2s';
                passwordInput.style.transition = 'all 0.2s';
                emailInput.style.backgroundColor = '#eef2ff';
                passwordInput.style.backgroundColor = '#eef2ff';
                setTimeout(() => {
                    emailInput.style.backgroundColor = '';
                    passwordInput.style.backgroundColor = '';
                }, 600);
            }
        }

        // Attacher les events aux cartes de démo
        document.addEventListener('DOMContentLoaded', () => {
            const demoCards = document.querySelectorAll('.demo-card');
            demoCards.forEach(card => {
                card.addEventListener('click', (e) => {
                    const email = card.getAttribute('data-email');
                    const pwd = card.getAttribute('data-pwd');
                    if (email && pwd) {
                        fillDemoCredentials(email, pwd);
                    }
                });
            });

            // Gestion dynamique de l'opacité du grand logo en arrière-plan lors du scroll pour un effet subtil
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

    <!-- Note: Ce template intègre parfaitement la récupération du logo d'application via le modèle Setting Laravel.
         Si vous utilisez ce fichier dans un environnement Laravel, assurez-vous que les routes ('login', 'register', 'password.request')
         et le helper session/error fonctionnent. Le fond transparent géant affiche le logo de l'application (ou un fallback élégant)
         avec une opacité réglée pour rester "chafaf" (transparent) tout en garantissant la lisibilité du formulaire.
         Tous les composants respectent la demande: logo de l'application en arrière-plan, fond transparent, design premium.
    -->
</body>
</html>