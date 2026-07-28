@extends('layout.website')
@section('content')
@php
    $setting = App\Models\RegistrationSetting::first();
@endphp
    <main>
        <section class="mx-auto max-w-7xl px-6 lg:px-8 py-10 lg:py-24">
            <div class="max-w-2xl mx-auto text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-violet-200 dark:border-violet-800 bg-violet-50 dark:bg-violet-950/40 px-3 py-1 text-xs font-semibold text-violet-700 dark:text-violet-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-teal-500"></span>
                    AutoPresent
                </span>
                <h1 class="mt-5 font-display text-3xl sm:text-4xl font-extrabold tracking-tight">
                    What would you like to do?
                </h1>
            </div>

            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- 1. Register (shows only when registration is active) --}}
                @if (isset($setting) && $setting->status === 'active')
                    <a href="{{ route('student-register') }}"
                        class="group relative flex flex-col rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-soft hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <div class="flex items-start justify-between">
                            <span class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-violet-50 to-teal-50 dark:from-violet-950/50 dark:to-teal-950/50 text-violet-600 dark:text-teal-400 text-lg">
                                <i class="fa-solid fa-user-plus"></i>
                            </span>
                            <i class="fa-solid fa-arrow-right text-gray-300 dark:text-gray-700 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
                        </div>
                        <h3 class="mt-5 font-display font-semibold text-lg">Register your face</h3>
                        <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400">Enroll once and get verified automatically in every session after this.</p>
                    </a>
                @endif

                {{-- 3. Sessions --}}
                <a href="{{ route('sessions.index') }}"
                    class="group relative flex flex-col rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-soft hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="flex items-start justify-between">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-violet-50 to-teal-50 dark:from-violet-950/50 dark:to-teal-950/50 text-violet-600 dark:text-teal-400 text-lg">
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                        <i class="fa-solid fa-arrow-right text-gray-300 dark:text-gray-700 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
                    </div>
                    <h3 class="mt-5 font-display font-semibold text-lg">Sessions</h3>
                    <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400">Browse all upcoming and past sessions and check attendance.</p>
                </a>

                
                {{-- 4. Attendance history --}}
                <a href="{{ route('attendance-history') }}"
                    class="group relative flex flex-col rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-soft hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="flex items-start justify-between">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-violet-50 to-teal-50 dark:from-violet-950/50 dark:to-teal-950/50 text-violet-600 dark:text-teal-400 text-lg">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </span>
                        <i class="fa-solid fa-arrow-right text-gray-300 dark:text-gray-700 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
                    </div>
                    <h3 class="mt-5 font-display font-semibold text-lg">Attendance History</h3>
                    <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400">See your full check-in record anytime.</p>
                </a>
                
            </div>
                
                @if (isset($session) && $session)
                <h1 class="my-5 font-display text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Current Live Session
                </h1>

                <div>
                               {{-- 2. Live session (shows only when a latest session exists) --}}
                    <a href="{{ route('student-attendance.take', $session->session_token) }}"
                        class="group relative flex flex-col rounded-2xl border border-teal-200 dark:border-teal-800 bg-teal-50/50 dark:bg-teal-950/20 p-6 shadow-soft hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <div class="flex items-start justify-between">
                            <span class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-violet-600 to-teal-500 text-white text-lg relative">
                                <i class="fa-solid fa-video"></i>
                                <span class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-teal-400 motion-safe:animate-ping"></span>
                                <span class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-teal-500"></span>
                            </span>
                            <i class="fa-solid fa-arrow-right text-gray-300 dark:text-gray-700 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
                        </div>
                        <h3 class="mt-5 font-display font-semibold text-lg">{{ $session->subject->name }}</h3>
                        <h3 class="font-display font-semibold text-md">{{ $session->subject->teacher }}</h3>
                        <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400">Date:{{ $session->date->format('d M, Y') }} </p>
                        <p>Live now — join and get verified your attendance in seconds.</p>
                    </a>

            </div>
                @endif
        </section>
    </main>
@endsection