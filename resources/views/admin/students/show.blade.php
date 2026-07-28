@extends('layout.app')

@section('title', 'Student Details')

@section('content')
<main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

        <div>
            <h1 class="font-display text-2xl font-bold">
                Student Details
            </h1>

            <p class="text-sm text-ink-400 mt-1">
                View student information.
            </p>
        </div>

        <div class="flex gap-3">

            <a href="{{ route('students.edit', $student) }}"
                class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl transition">

                <i class="fa-solid fa-pen"></i>
                Edit
            </a>

            <a href="{{ route('students.index') }}"
                class="inline-flex items-center gap-2 border border-ink-200 dark:border-ink-700 hover:bg-ink-100 dark:hover:bg-ink-800 px-4 py-2.5 rounded-xl transition">

                <i class="fa-solid fa-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <div class="bg-white dark:bg-ink-850 rounded-2xl border border-ink-100 dark:border-ink-700 overflow-hidden">

        <div class="p-8">

            <div class="flex flex-col lg:flex-row gap-8">

                {{-- Student Image --}}
                <div class="flex justify-center">

                    <img
                        src="{{ $student->photo_url }}"
                        alt="{{ $student->name }}"
                        class="w-44 h-44 rounded-2xl object-cover border border-ink-200 dark:border-ink-700">

                </div>

                {{-- Student Information --}}
                <div class="flex-1">

                    <h2 class="text-2xl font-bold">
                        {{ $student->name }}
                    </h2>

                    <p class="text-ink-400 mt-1">
                        Student Information
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

                        <div>
                            <label class="text-xs uppercase text-ink-400">
                                Roll
                            </label>

                            <div class="mt-2 font-medium">
                                {{ $student->roll }}
                            </div>
                        </div>

                        <div>
                            <label class="text-xs uppercase text-ink-400">
                                Phone
                            </label>

                            <div class="mt-2 font-medium">
                                {{ $student->phone ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <label class="text-xs uppercase text-ink-400">
                                Email
                            </label>

                            <div class="mt-2 font-medium break-all">
                                {{ $student->email ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <label class="text-xs uppercase text-ink-400">
                                Created At
                            </label>

                            <div class="mt-2 font-medium">
                                {{ $student->created_at->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        <div>
                            <label class="text-xs uppercase text-ink-400">
                                Last Updated
                            </label>

                            <div class="mt-2 font-medium">
                                {{ $student->updated_at->format('d M Y, h:i A') }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>
@endsection
