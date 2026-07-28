@extends('layout.website')
@section('title', 'Session')
@section('content')
    <main class="mx-auto max-w-7xl mt-10 px-6 lg:px-8 pb-20">
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

                <div class="grid grid-cols-3 gap-4 sm:gap-8 sm:border-l sm:border-gray-200 dark:sm:border-gray-800 sm:pl-8">
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
            </div>
        </div>
        <div class="mt-8">
            <h4 class="text-lg font-bold mb-4">Attendance Records</h4>
            @if($session->attendances && $session->attendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Image</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Name</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Roll</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Email</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Phone</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($session->attendances as $attendance)
                                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="py-3 px-4">
                                        @if($attendance->student->photo_url)
                                            <img src="{{ $attendance->student->photo_url }}" alt="{{ $attendance->student->name }}" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-900 dark:text-gray-100">{{ $attendance->student->name }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">{{ $attendance->student->roll }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">{{ $attendance->student->email }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">{{ $attendance->student->phone }}</td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                            {{ Str::ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No attendance records yet.</p>
            @endif
        </div>
    </main>
@endsection
