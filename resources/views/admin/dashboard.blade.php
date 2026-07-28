@extends('layout.app')
@section('title', 'Dashboard')
@section('content')
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="font-display text-2xl font-bold">Welcome back, Shakhawat 👋</h1>
                <p class="text-sm text-ink-400 mt-1">Here's what's happening with your system today.</p>
                @if (isset($registrationSetting) && $registrationSetting->status === 'active')
                    <span class="inline-block mt-2 text-xs bg-teal-100 text-teal-700 px-2 py-1 rounded-full">
                        <i class="fa-solid fa-circle text-[6px] align-middle mr-1"></i>Registration: Active
                    </span>
                @else
                    <span class="inline-block mt-2 text-xs bg-ink-50 text-ink-500 px-2 py-1 rounded-full">
                        <i class="fa-solid fa-circle text-[6px] align-middle mr-1"></i>Registration: Inactive
                    </span>
                @endif
            </div>
            <a href="{{ route('attendance-sessions.create') }}"
                class="flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-lg shadow-brand-500/25 transition self-start">
                <i class="fa-solid fa-plus"></i> Create Session
            </a>
        </div>

        <!-- Stat cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            <div
                class="bg-white dark:bg-ink-850 rounded-2xl p-5 border border-ink-100 dark:border-ink-700 hover:shadow-lg hover:-translate-y-0.5 transition">
                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-brand-500/10 flex items-center justify-center text-brand-500">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                </div>
                <p class="font-display text-2xl font-bold mt-4">{{ $totalStudents }}</p>
                <p class="text-xs text-ink-400 mt-1">Total Students</p>
            </div>

            <div
                class="bg-white dark:bg-ink-850 rounded-2xl p-5 border border-ink-100 dark:border-ink-700 hover:shadow-lg hover:-translate-y-0.5 transition">
                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-teal-500/10 flex items-center justify-center text-teal-500">
                        <i class="fa-solid fa-book"></i>
                    </div>
                </div>
                <p class="font-display text-2xl font-bold mt-4">{{ $totalSubjects }}</p>
                <p class="text-xs text-ink-400 mt-1">Total Subjects</p>
            </div>

            <div
                class="bg-white dark:bg-ink-850 rounded-2xl p-5 border border-ink-100 dark:border-ink-700 hover:shadow-lg hover:-translate-y-0.5 transition">
                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                </div>
                <p class="font-display text-2xl font-bold mt-4">{{ $todayAttendances }}</p>
                <p class="text-xs text-ink-400 mt-1">Today's Attendances</p>
            </div>

            <div
                class="bg-white dark:bg-ink-850 rounded-2xl p-5 border border-ink-100 dark:border-ink-700 hover:shadow-lg hover:-translate-y-0.5 transition">
                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-fuchsia-500/10 flex items-center justify-center text-fuchsia-500">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                </div>
                <p class="font-display text-2xl font-bold mt-4">{{ $activeSessions }}</p>
                <p class="text-xs text-ink-400 mt-1">Active Sessions</p>
            </div>

            <div
                class="bg-white dark:bg-ink-850 rounded-2xl p-5 border border-ink-100 dark:border-ink-700 hover:shadow-lg hover:-translate-y-0.5 transition">
                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-500">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <span class="text-xs font-semibold text-teal-600 bg-teal-500/10 px-2 py-1 rounded-full">7d</span>
                </div>
                <p class="font-display text-2xl font-bold mt-4">{{ $newStudentsThisWeek }}</p>
                <p class="text-xs text-ink-400 mt-1">New Students</p>
            </div>
        </div>

 


        <!-- Recent Sessions + Recent New Students -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-ink-850 rounded-2xl p-5 border border-ink-100 dark:border-ink-700">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display font-semibold">Recent Sessions</h2>
                    <a href="{{ route('attendance-sessions.index') }}"
                        class="text-xs font-medium text-brand-500 hover:underline">View all</a>
                </div>
                <ul class="space-y-4">
                    @forelse($recentSessions as $session)
                        <li class="flex gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-brand-500/10 text-brand-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-chalkboard-user text-xs"></i>
                            </div>
                            <div class="text-sm flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <p><span
                                            class="font-medium">{{ $session->subject?->name ?? 'Unknown Subject' }}</span>
                                        — {{ optional($session->started_at)->diffForHumans() }}</p>
                                    @if (is_null($session->ended_at))
                                        <span
                                            class="text-[10px] font-semibold text-teal-600 bg-teal-500/10 px-2 py-0.5 rounded-full shrink-0">Live</span>
                                    @else
                                        <span
                                            class="text-[10px] font-semibold text-ink-400 bg-ink-50 dark:bg-ink-800 px-2 py-0.5 rounded-full shrink-0">Ended</span>
                                    @endif
                                </div>
                                <p class="text-xs text-ink-400">{{ $session->attendances_count ?? 0 }} attendances</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-ink-400">No recent sessions</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white dark:bg-ink-850 rounded-2xl p-5 border border-ink-100 dark:border-ink-700">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display font-semibold">Recent New Students</h2>
                    @if (Route::has('students.index'))
                        <a href="{{ route('students.index') }}"
                            class="text-xs font-medium text-brand-500 hover:underline">View all</a>
                    @endif
                </div>
                <ul class="space-y-4">
                    @forelse($recentStudents as $student)
                        <li class="flex gap-3 items-center">
                            <div
                                class="w-9 h-9 rounded-full bg-sky-500/10 text-sky-500 flex items-center justify-center shrink-0 font-semibold text-xs">
                                {{ strtoupper(substr($student->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="text-sm flex-1">
                                <p class="font-medium">{{ $student->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-ink-400">Joined
                                    {{ optional($student->created_at)->diffForHumans() }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-ink-400">No students registered yet</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <!-- Table -->
        <div class="bg-white dark:bg-ink-850 rounded-2xl border border-ink-100 dark:border-ink-700 overflow-hidden">
            <div class="flex items-center justify-between p-5 pb-0">
                <h2 class="font-display font-semibold">Recent Attendances</h2>
            </div>
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr
                            class="text-left text-ink-400 text-xs uppercase tracking-wider border-y border-ink-100 dark:border-ink-700">
                            <th class="px-5 py-3 font-medium">Student</th>
                            <th class="px-5 py-3 font-medium">Subject</th>
                            <th class="px-5 py-3 font-medium hidden md:table-cell">Session</th>
                            <th class="px-5 py-3 font-medium hidden sm:table-cell">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-100 dark:divide-ink-700">
                        @forelse($recentAttendances as $att)
                            <tr>
                                <td class="px-5 py-3">{{ $att->student?->name ?? 'Unknown' }}</td>
                                <td class="px-5 py-3">{{ $att->session->subject?->name ?? 'N/A' }}</td>
                                <td class="px-5 py-3 hidden md:table-cell">
                                    {{ $att->session?->date?->toDateString() ?? '—' }}</td>
                                <td class="px-5 py-3 hidden sm:table-cell">
                                    {{ optional($att->attendance_time)->format('Y-m-d H:i') }}</td>

                            </tr>
                        @empty
                            <tr>
                                <td class="p-5 text-sm text-ink-400" colspan="5">No recent attendances</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between p-5 text-xs text-ink-400">
                <span>Showing {{ $recentAttendances->total() }} attendances</span>
                <div>
                    {{ $recentAttendances->links() }}
                </div>
            </div>
        </div>

    </main>

    <script>
        (function() {
            // Weekly bar chart
            const labels = @json($weeklyLabels);
            const values = @json($weeklyValues);
            const max = Math.max(...values, 1);
            const container = document.getElementById('weeklyChart');
            if (container) {
                container.innerHTML = labels.map((lab, idx) => {
                    const h = Math.round((values[idx] / max) * 100);
                    return `<div class="flex-1 flex flex-col items-center justify-end group">
                        <span class="text-[10px] text-ink-400 mb-1 opacity-0 group-hover:opacity-100 transition">${values[idx]}</span>
                        <div class="bg-brand-500 rounded-t-md w-full transition-all hover:bg-brand-600" style="height:${h}%"></div>
                        <div class="text-[10px] mt-2 text-ink-400">${lab.slice(5)}</div>
                    </div>`;
                }).join('');
            }

            // Subject distribution pie chart (built with a conic-gradient, no external libs)
            const subjectLabels = @json($subjectLabels ?? []);
            const subjectValues = @json($subjectValues ?? []);
            const pieEl = document.getElementById('subjectPie');
            const legendEl = document.getElementById('subjectLegend');

            if (pieEl && subjectValues.length > 0) {
                const palette = ['#3b82f6', '#14b8a6', '#f59e0b', '#d946ef', '#ef4444', '#6366f1'];
                const total = subjectValues.reduce((a, b) => a + b, 0) || 1;

                let cursor = 0;
                const segments = subjectValues.map((v, i) => {
                    const start = (cursor / total) * 360;
                    cursor += v;
                    const end = (cursor / total) * 360;
                    return `${palette[i % palette.length]} ${start}deg ${end}deg`;
                });

                pieEl.style.background = `conic-gradient(${segments.join(', ')})`;

                legendEl.innerHTML = subjectLabels.map((lab, idx) => {
                    const pct = ((subjectValues[idx] / total) * 100).toFixed(1);
                    return `<li class="flex items-center justify-between gap-2">
                        <span class="flex items-center gap-2 truncate">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:${palette[idx % palette.length]}"></span>
                            <span class="truncate text-ink-500">${lab}</span>
                        </span>
                        <span class="font-medium text-ink-400 shrink-0">${pct}%</span>
                    </li>`;
                }).join('');
            }
        })();
    </script>
@endsection
