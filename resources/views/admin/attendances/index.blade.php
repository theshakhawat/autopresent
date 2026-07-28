@extends('layout.app')

@section('title', 'Attendance Records')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-bold">
                    Attendance
                </h1>

                <p class="text-sm text-ink-400 mt-1">
                    View all student attendance records.
                </p>
            </div>

            <a href="{{ route('attendances.create') }}"
                class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-lg shadow-brand-500/20">

                <i class="fa-solid fa-plus"></i>

                Create Attendance

            </a>
        </div>



        {{-- Success --}}
        @if (session('success'))
            <div
                class="flex items-start gap-3 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl p-4">

                <div class="w-10 h-10 rounded-lg bg-green-500/10 text-green-600 flex items-center justify-center shrink-0">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <div>

                    <h4 class="font-semibold text-green-700 dark:text-green-400">
                        Success
                    </h4>

                    <p class="text-sm text-green-600 dark:text-green-300 mt-1">
                        {{ session('success') }}
                    </p>

                </div>

            </div>
        @endif

        {{-- Error --}}
        @if (session('error'))
            <div
                class="flex items-start gap-3 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl p-4">

                <div class="w-10 h-10 rounded-lg bg-red-500/10 text-red-600 flex items-center justify-center shrink-0">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>

                <div>

                    <h4 class="font-semibold text-red-700 dark:text-red-400">
                        Error
                    </h4>

                    <p class="text-sm text-red-600 dark:text-red-300 mt-1">
                        {{ session('error') }}
                    </p>

                </div>

            </div>
        @endif

        <div class="bg-white dark:bg-ink-850 rounded-2xl border border-ink-100 dark:border-ink-700 overflow-hidden">

            {{-- Top --}}
            <div class="p-5 flex items-center justify-between">

                <h2 class="font-semibold text-lg">
                    Attendance List
                </h2>

                <span class="text-sm text-ink-400">
                    Total Records :
                    <strong>{{ $attendances->total() }}</strong>
                </span>

            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr class="border-y border-ink-100 dark:border-ink-700 text-xs uppercase text-ink-400">

                            <th class="px-5 py-3 text-left">#</th>
                            <th class="px-5 py-3 text-left">Student</th>
                            <th class="px-5 py-3 text-left">Subject</th>
                            <th class="px-5 py-3 text-left">Date</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-left">Attendance Time</th>
                            <th class="px-5 py-3 text-center">Action</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-ink-100 dark:divide-ink-700">

                        @forelse ($attendances as $attendance)
                            <tr class="hover:bg-ink-50 dark:hover:bg-ink-800">

                                <td class="px-5 py-4">

                                    {{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}

                                </td>

                                <td class="px-5 py-4">

                                    <div>

                                        <p class="font-medium">
                                            {{ $attendance->student->name }}
                                        </p>

                                        <p class="text-xs text-ink-400">
                                            ID :
                                            {{ $attendance->student->roll }}
                                        </p>

                                    </div>

                                </td>

                                <td class="px-5 py-4">

                                    {{ $attendance->session->subject->name }}

                                </td>

                                <td class="px-5 py-4">

                                    {{ $attendance->session->date->format('d M Y') }}

                                </td>

                                <td class="px-5 py-4 text-center">

                                    @if ($attendance->status == 'present')
                                        <span
                                            class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400">

                                            Present

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400">

                                            Absent

                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-4">

                                    {{ $attendance->attendance_time->format('d M Y, h:i:s A') }}

                                </td>

                                <td class="px-5 py-4">

                                    <div class="flex justify-center gap-2">

                                        {{-- Edit --}}
                                        <a href="{{ route('attendances.edit', $attendance) }}"
                                            class="w-9 h-9 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center hover:bg-amber-500 hover:text-white transition">

                                            <i class="fa-solid fa-pen"></i>

                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('attendances.destroy', $attendance) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this attendance record?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="w-9 h-9 rounded-lg bg-red-500/10 text-red-600 flex items-center justify-center hover:bg-red-500 hover:text-white transition">

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-12 text-ink-400">

                                    <i class="fa-solid fa-user-check text-3xl mb-3 block"></i>

                                    No attendance records found.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="p-5 border-t border-ink-100 dark:border-ink-700">

                {{ $attendances->links() }}

            </div>

        </div>

    </main>
@endsection
