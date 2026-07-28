@extends('layout.website')

@section('title', '500 - Server Error')

@section('content')
<main class="relative overflow-hidden min-h-[calc(100vh-80px)] flex items-center justify-center px-6 py-16">

    {{-- Background --}}
    <div
        class="absolute inset-0 -z-10 bg-gradient-to-b from-violet-50 via-white to-white dark:from-violet-950/30 dark:via-gray-950 dark:to-gray-950">
    </div>

    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-violet-500/10 blur-3xl"></div>
    <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-teal-500/10 blur-3xl"></div>

    <div
        class="relative w-full max-w-2xl rounded-3xl border border-gray-200 dark:border-gray-800 bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl shadow-soft p-8 sm:p-12 text-center">

        {{-- Icon --}}
        <div
            class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-violet-600 to-teal-500 shadow-lg">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-12 w-12 text-white"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v4" />

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 17h.01" />

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />

            </svg>

        </div>

        {{-- Error Code --}}
        <h1
            class="mt-8 text-6xl sm:text-7xl font-extrabold bg-gradient-to-r from-violet-600 to-teal-500 bg-clip-text text-transparent">
            500
        </h1>

        <h2 class="mt-4 font-display text-3xl font-bold">
            Internal Server Error
        </h2>

        <p class="mt-4 text-gray-600 dark:text-gray-400 max-w-xl mx-auto leading-relaxed">
            Something went wrong on our server while processing your request.
            Please try again in a few moments. If the problem continues, contact the system administrator.
        </p>

        <div
            class="mt-8 inline-flex items-center gap-2 rounded-xl border border-amber-200 dark:border-amber-900 bg-amber-50 dark:bg-amber-950/20 px-4 py-3 text-sm text-amber-700 dark:text-amber-400">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 8v4m0 4h.01" />

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M10.29 3.86L1.82 18A2 2 0 003.53 21h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />

            </svg>

            Error Code: 500
        </div>

        {{-- Buttons --}}
        <div class="mt-10 flex flex-wrap justify-center gap-4">

            <button onclick="window.location.reload()"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-3 font-semibold text-gray-700 dark:text-gray-200 hover:border-violet-500 hover:text-violet-600 dark:hover:text-teal-400 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 4v6h6" />

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M20 20v-6h-6" />

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M20 9A8 8 0 006.34 5.34L4 10m16 4l-2.34 4.66A8 8 0 014 15" />

                </svg>

                Try Again
            </button>

            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-teal-500 px-6 py-3 font-semibold text-white shadow-soft hover:opacity-90 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 10.5L12 3l9 7.5" />

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 9.5V21h14V9.5" />

                </svg>

                Back to Home
            </a>

        </div>

    </div>

</main>
@endsection
