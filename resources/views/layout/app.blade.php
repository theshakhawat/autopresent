<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Space Grotesk', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f1efff',
                            100: '#e4e0ff',
                            200: '#c9c1ff',
                            300: '#a89bff',
                            400: '#8b7bff',
                            500: '#6d5dfc',
                            600: '#5a47e6',
                            700: '#4735b8',
                            800: '#372a8c',
                            900: '#241c5c'
                        },
                        teal: {
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488'
                        },
                        ink: {
                            50: '#f6f7f9',
                            100: '#eceef2',
                            200: '#dbe0e8',
                            300: '#aab2c0',
                            400: '#7c8698',
                            500: '#565f72',
                            600: '#3d4457',
                            700: '#262b38',
                            800: '#171a21',
                            850: '#12141a',
                            900: '#0f1115'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #a89bff44;
            border-radius: 8px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #6d5dfc55;
        }

        .bar {
            transition: height 0.7s cubic-bezier(.34, 1.56, .64, 1);
        }

        .fade-in {
            animation: fadeIn 0.5s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
    @stack('css')
</head>

<body class="font-body bg-ink-50 text-ink-700 dark:bg-ink-900 dark:text-ink-100 transition-colors duration-300">

    <div class="min-h-screen flex">

        <!-- Mobile overlay -->
        <div id="overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

        <!-- Sidebar -->
        @include('layout.sidebar')

        <!-- Main -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Topbar -->
            @include('layout.topbar')

            <!-- Content -->
            @yield('content')
        </div>
    </div>

    <script>
        // Theme toggle with localStorage persistence
        const root = document.documentElement;
        const themeToggle = document.getElementById('themeToggle');
        const storedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (storedTheme === 'dark' || (storedTheme === null && prefersDark)) {
            root.classList.add('dark');
        } else {
            root.classList.remove('dark');
        }

        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const isDark = root.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        }

        // Sidebar mobile toggle
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
        openBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Profile dropdown
        const profileBtn = document.getElementById('profileBtn');
        const profileMenu = document.getElementById('profileMenu');
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', () => profileMenu.classList.add('hidden'));

        // Nav active state
        // document.querySelectorAll('.nav-link').forEach(link => {
        //     link.addEventListener('click', (e) => {
        //         e.preventDefault();
        //         document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active',
        //             'bg-brand-500/10', 'text-brand-600', 'dark:text-brand-300', 'font-medium'));
        //         link.classList.add('active', 'bg-brand-500/10', 'text-brand-600', 'dark:text-brand-300',
        //             'font-medium');
        //         if (window.innerWidth < 1024) closeSidebar();
        //     });
        // });
    </script>

    @stack('js')

</body>

</html>
