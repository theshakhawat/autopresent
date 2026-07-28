@extends('layout.app')

@section('title', 'Create Attendance')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 sm:p-6">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="font-display text-2xl font-bold">
                Create Attendance
            </h1>

            <p class="text-sm text-ink-400 mt-1">
                Create a new attendance record.
            </p>
        </div>

        

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div
                class="mb-6 flex items-start gap-3 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl p-4">

                <div
                    class="w-10 h-10 rounded-lg bg-red-500/10 text-red-600 flex items-center justify-center shrink-0">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>

                <div>

                    <h4 class="font-semibold text-red-700 dark:text-red-400">
                        Validation Error
                    </h4>

                    <ul class="mt-2 text-sm text-red-600 dark:text-red-300 list-disc ml-5">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>
        @endif

        <div
            class="bg-white dark:bg-ink-850 rounded-2xl border border-ink-100 dark:border-ink-700">

            <form action="{{ route('attendances.store') }}"
                method="POST"
                class="p-6 space-y-6">

                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Student --}}
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Student
                        </label>

                        <select
                            name="student_id"
                            required
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 dark:bg-gray-900 bg-gray-100 px-4 py-3 focus:outline-none">

                            <option class="dark:bg-gray-900 bg-gray-100" value="">
                                Select Student
                            </option>

                            @foreach ($students as $student)

                                <option
                                    class="dark:bg-gray-900 bg-gray-100"
                                    value="{{ $student->id }}"
                                    {{ old('student_id') == $student->id ? 'selected' : '' }}>

                                    {{ $student->name }} ({{ $student->roll }})

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Attendance Session --}}
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Attendance Session
                        </label>

                        <select
                            name="attendance_session_id"
                            required
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 dark:bg-gray-900 bg-gray-100 px-4 py-3 focus:outline-none">

                            <option class="dark:bg-gray-900 bg-gray-100" value="">
                                Select Session
                            </option>

                            @foreach ($attendanceSessions as $session)

                                <option
                                    class="dark:bg-gray-900 bg-gray-100"
                                    value="{{ $session->id }}"
                                    {{ old('attendance_session_id') == $session->id ? 'selected' : '' }}>

                                    {{ $session->subject->name }}
                                    -
                                    {{ $session->date->format('d M Y') }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Status --}}
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Status
                        </label>

                        <select
                            name="status"
                            required
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 dark:bg-gray-900 bg-gray-100 px-4 py-3 focus:outline-none">

                            <option class="dark:bg-gray-900 bg-gray-100"
                                value="present"
                                {{ old('status', 'present') == 'present' ? 'selected' : '' }}>
                                Present
                            </option>

                            <option class="dark:bg-gray-900 bg-gray-100"
                                value="absent"
                                {{ old('status') == 'absent' ? 'selected' : '' }}>
                                Absent
                            </option>

                        </select>

                    </div>

                    {{-- Attendance Time --}}
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Attendance Time
                        </label>

                        <input
                            type="datetime-local"
                            name="attendance_time"
                            required
                            value="{{ old('attendance_time', now()->format('Y-m-d\TH:i')) }}"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 dark:bg-ink-900 px-4 py-3 focus:outline-none">

                    </div>

                </div>

                <div class="flex justify-end gap-3 pt-4">

                    <a href="{{ route('attendances.index') }}"
                        class="px-5 py-3 rounded-xl border border-ink-200 dark:border-ink-700 hover:bg-ink-100 dark:hover:bg-ink-800 transition">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="px-5 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white transition">

                        <i class="fa-solid fa-floppy-disk mr-2"></i>

                        Save Attendance

                    </button>

                </div>

            </form>

        </div>

    </main>
@endsection
