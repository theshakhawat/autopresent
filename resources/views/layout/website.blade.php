
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <script>
        // Set theme before paint to avoid a flash of the wrong mode
        (function() {
            const stored = localStorage.getItem('autopresent-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AutoPresent — Smart Face Attendance')</title>
    <meta name="description"
        content="AutoPresent verifies student attendance with on-device face recognition, gives teachers a live view of every session, and gives admins one dashboard for the whole batch.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    {{-- If Tailwind is already compiled via Vite for the admin panel, replace this block with: @vite(['resources/css/app.css','resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            purple: '#7C3AED',
                            teal: '#14B8A6',
                        },
                    },
                    boxShadow: {
                        soft: '0 2px 8px -2px rgba(17, 24, 39, 0.06), 0 8px 24px -6px rgba(17, 24, 39, 0.08)',
                        'soft-dark': '0 2px 8px -2px rgba(0, 0, 0, 0.3), 0 8px 24px -6px rgba(0, 0, 0, 0.4)',
                    },
                },
            },
        };
    </script>

    <style>
        @keyframes scanline {

            0%,
            100% {
                transform: translateY(0);
                opacity: .9;
            }

            50% {
                transform: translateY(148px);
                opacity: .4;
            }
        }

        .motion-safe-scanline {
            animation: scanline 2.6s ease-in-out infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .motion-safe-scanline {
                animation: none;
                color: #c20a0a;
            }
        }
    </style>

    @stack('css')
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors">

    {{-- ============ NAV ============ --}}
       <header
        class="sticky top-0 z-50 border-b border-gray-200/70 dark:border-gray-800/70 bg-white/80 dark:bg-gray-950/80 backdrop-blur-md">
        <nav class="mx-auto max-w-7xl px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0">
                <span
                    class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-violet-600 to-teal-500 shadow-soft">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        class="h-5 w-5 text-white">
                        <path
                            d="M4 8V6a2 2 0 0 1 2-2h2M20 8V6a2 2 0 0 0-2-2h-2M4 16v2a2 2 0 0 0 2 2h2M20 16v2a2 2 0 0 1-2 2h-2"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="12" r="2.6" stroke="currentColor" stroke-width="1.8" />
                    </svg>
                </span>
                <span class="font-display font-bold text-lg tracking-tight">AutoPresent</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600 dark:text-gray-300">
                <a href="{{ route('sessions.index') }}"
                    class="hover:text-violet-600 dark:hover:text-teal-400 transition-colors">Sessions</a>
                <a href="{{ route('attendance-history') }}" class="hover:text-violet-600 dark:hover:text-teal-400 transition-colors">History</a>
                </div>

            <div class="flex items-center gap-3">
                <button id="theme-toggle" type="button" aria-label="Toggle dark mode"
                    class="grid h-9 w-9 place-items-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-teal-400 hover:border-violet-300 dark:hover:border-teal-700 transition-colors">
                    <i class="fa-solid fa-moon"></i>
                </button>

            </div>
        </nav>
    </header>

    @yield('content')

    {{-- ============ FOOTER ============ --}}
    <footer class="border-t border-gray-200 dark:border-gray-800">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 py-14 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div>
                <div class="flex items-center gap-2">
                    <span
                        class="grid h-8 w-8 place-items-center rounded-lg bg-gradient-to-br from-violet-600 to-teal-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            class="h-4 w-4 text-white">
                            <circle cx="12" cy="12" r="2.6" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                    </span>
                    <span class="font-display font-bold">AutoPresent</span>
                </div>
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 max-w-xs">Smart face attendance for modern
                    classrooms — verified check-ins, live sessions, and one dashboard per role.</p>
            </div>

            <div>
                <p class="text-sm font-semibold">Product</p>
                <ul class="mt-3 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('home') }}#features" class="hover:text-violet-600 dark:hover:text-teal-400">Features</a></li>
                    <li><a href="{{ route('home') }}#how-it-works" class="hover:text-violet-600 dark:hover:text-teal-400">How it
                            works</a></li>
                    <li><a href="{{ route('home') }}#live-session" class="hover:text-violet-600 dark:hover:text-teal-400">Live
                            session</a></li>
                </ul>
            </div>

            <div>
                <p class="text-sm font-semibold">Account</p>
                <ul class="mt-3 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('student-register') }}"
                            class="hover:text-violet-600 dark:hover:text-teal-400">Register</a></li>
                    @auth
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-violet-600 dark:hover:text-teal-400">Admin Dashboard</a></li>
                    @else
                    <li><a href="{{ route('login') }}" class="hover:text-violet-600 dark:hover:text-teal-400">Log
                            in</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <p class="text-sm font-semibold">Support</p>
                <ul class="mt-3 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('login') }}" class="hover:text-violet-600 dark:hover:text-teal-400">Contact your
                            admin</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-violet-600 dark:hover:text-teal-400">Help center</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-800 py-6">
            <p class="text-center text-xs text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} AutoPresent.
                Built for modern classrooms.</p>
                <small class="text-xs text-center block text-gray-400">Your Public IP: {{request()->ip()}}</small>
        </div>
    </footer>

    @include('errors.alert')
    <script>
        const themeToggle = document.getElementById('theme-toggle');
        themeToggle?.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('autopresent-theme', isDark ? 'dark' : 'light');
        });

        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileBtn?.addEventListener('click', () => mobileMenu?.classList.toggle('hidden'));
    </script>

    @stack('js')

</body>

</html>
