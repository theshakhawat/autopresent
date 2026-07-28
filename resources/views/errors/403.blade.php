@extends('layout.website')

@section('title', '403 - Forbidden')

@section('content')

@php
    $setting = \App\Models\RegistrationSetting::first();
@endphp

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

                <circle cx="12" cy="12" r="9"></circle>

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9.75 9.75a2.25 2.25 0 114.5 0c0 1.5-2.25 2.25-2.25 2.25" />

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 16h.01" />

            </svg>

        </div>

        {{-- Error Code --}}
        <h1
            class="mt-8 text-6xl sm:text-7xl font-extrabold bg-gradient-to-r from-violet-600 to-teal-500 bg-clip-text text-transparent">
            403
        </h1>

        <h2 class="mt-4 font-display text-3xl font-bold">
            Access Forbidden
        </h2>

        @if($exception->getMessage())

            <div class="mt-5 text-gray-600 dark:text-gray-400 max-w-xl mx-auto text-left space-y-4">

                <p class="text-center">
                    {{ $exception->getMessage() }}
                </p>

                <div class="rounded-xl bg-gray-100 dark:bg-gray-800 p-4 border border-gray-200 dark:border-gray-700">

                    <p>
                        <span class="font-semibold text-gray-900 dark:text-white">
                            Your IP Address:
                        </span>
                        <code class="font-mono">{{ request()->ip() }}</code>
                    </p>

                    @if(!empty($setting?->whitelist_ips))
                        <div class="mt-4">

                            <p class="font-semibold text-gray-900 dark:text-white">
                                Allowed Networks (CIDR):
                            </p>

                            <ul class="mt-2 space-y-1 list-disc list-inside">
                                @foreach(explode(',', $setting->whitelist_ips) as $network)
                                    <li>
                                        <code class="font-mono">{{ trim($network) }}</code>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    @endif

                </div>

            </div>

        @else

            <p class="mt-4 text-gray-600 dark:text-gray-400 max-w-xl mx-auto leading-relaxed">
                Sorry, you don't have permission to access this page. Please contact your administrator if you believe this is a mistake.
            </p>

        @endif

        {{-- Buttons --}}
        <div class="mt-10 flex flex-wrap justify-center gap-4">

            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-3 font-semibold text-gray-700 dark:text-gray-200 hover:border-violet-500 hover:text-violet-600 dark:hover:text-teal-400 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19l-7-7 7-7" />

                </svg>

                Go Back

            </a>

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