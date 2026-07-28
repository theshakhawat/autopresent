@extends('layout.website')
@section('title','All Session')
@section('content')
    @php
        $setting = App\Models\RegistrationSetting::first();
    @endphp
<main>
    {{-- ============ LIVE SESSION ============ --}}
    <section id="live-session" class="mx-auto max-w-7xl mt-10 px-6 lg:px-8 pb-20">
        <div
            class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-soft p-6 sm:p-8">
            @if (isset($sesion) && $sesion)
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <span class="mt-1 relative flex h-3 w-3">
                            <span
                                class="motion-safe:animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                        </span>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                                Live session</p>
                            <h3 class="mt-1 font-display text-xl font-bold">
                                {{ $sesion->subject->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $sesion->subject->teacher ?? ($sesion->teacher ?? 'Teacher TBA') }} ·
                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                    {{ Str::ucfirst($sesion->subject->type) . ' Class' }}
                                </span>

                            </p>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-3 gap-4 sm:gap-8 sm:border-l sm:border-gray-200 dark:sm:border-gray-800 sm:pl-8">
                        <div>
                            <p class="text-xs text-gray-400">Code</p>
                            <p class="font-semibold">{{ $sesion->subject->code ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Credits</p>
                            <p class="font-semibold">{{ $sesion->subject->credit ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Date</p>
                            <p class="font-semibold">{{ $sesion->subject->date ?? now()->format('D, M d') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('student-attendance.take', $sesion->session_token) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-violet-600 to-teal-500 px-5 py-3 text-sm font-semibold text-white shadow-soft hover:opacity-90 transition-opacity whitespace-nowrap">
                        Join attendance
                    </a>
                </div>
            @else
                <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                    <span
                        class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-6 w-6">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" />
                            <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="font-display font-semibold">No live session right now</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Your next class attendance will appear
                            here automatically once a teacher starts a session.</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ============ ALL SESSIONS ============ --}}
    <section id="all-sessions" class="mx-auto max-w-7xl px-6 lg:px-8 pb-20">
        <div class="mb-8">
            <h2 class="font-display text-2xl font-bold">All Sessions</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">View all available sessions</p>
        </div>
        @if (isset($sesions) && $sesions->count())
            <div class="grid gap-6">
                @foreach ($sesions as $session)
                    <div
                        class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-soft p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                            <div class="flex items-start gap-4">
                                <div class="mt-1">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                                        {{ Str::ucfirst($session->subject->type) . ' Class' }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-display text-xl font-bold">
                                        {{ $session->subject->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $session->subject->teacher ?? ($session->teacher ?? 'Teacher TBA') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $session->date->format('F j, Y') ?? 'Date TBA' }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-3 gap-4 sm:gap-8 sm:border-l sm:border-gray-200 dark:sm:border-gray-800 sm:pl-8">
                                <div>
                                    <p class="text-xs text-gray-400">Code</p>
                                    <p class="font-semibold">{{ $session->subject->code ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Credits</p>
                                    <p class="font-semibold">{{ $session->subject->credit ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Attendance</p>
                                    <p class="font-semibold">{{ $session->attendances ? $session->attendances->count() : 0 }}</p>
                                </div>
                            </div>
                            <a href="{{ route('sessions.show', $session->session_token) }}"
                                class="inline-flex items-center justify-center rounded-xl bg-gray-600 px-5 py-3 text-sm font-semibold text-white shadow-soft hover:opacity-90 transition-opacity whitespace-nowrap">
                                Show Attendance
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $sesions->links() }}
            </div>
        @else
            <div class="flex flex-col items-center gap-4 text-center rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-soft p-12">
                <span
                    class="grid h-12 w-12 place-items-center rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-6 w-6">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" />
                        <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                    </svg>
                </span>
                <div>
                    <h3 class="font-display font-semibold">No sessions available</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">There are no sessions at the moment.</p>
                </div>
            </div>
        @endif
    </section>
</main>
@endsection
