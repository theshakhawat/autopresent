@extends('layout.app')

@section('title', 'Add Subject')

@section('content')
<main class="flex-1 overflow-y-auto p-4 sm:p-6">

    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold font-display">
                Add Subject
            </h1>

            <p class="text-sm text-ink-400 mt-1">
                Create a new subject.
            </p>
        </div>

        {{-- Error --}}
        @if ($errors->any())

            <div class="mb-6 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl p-4">

                <div class="flex items-start gap-3">

                    <div class="w-10 h-10 rounded-lg bg-red-500/10 text-red-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>

                    <div>

                        <h4 class="font-semibold text-red-700 dark:text-red-400">
                            Validation Error
                        </h4>

                        <ul class="mt-2 text-sm text-red-600 dark:text-red-300 list-disc list-inside space-y-1">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif

        {{-- Form --}}
        <div class="bg-white dark:bg-ink-850 border border-ink-100 dark:border-ink-700 rounded-2xl p-6">

            <form action="{{ route('subjects.store') }}" method="POST">

                @csrf

                <div class="grid md:grid-cols-2 gap-6">

                    {{-- Subject Name --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Subject Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter subject name"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    {{-- Subject Code --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Subject Code
                        </label>

                        <input
                            type="text"
                            name="code"
                            value="{{ old('code') }}"
                            placeholder="Enter subject code"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    {{-- Teacher --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Teacher
                        </label>

                        <input
                            type="text"
                            name="teacher"
                            value="{{ old('teacher') }}"
                            placeholder="Teacher name"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    {{-- Credit --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Credit
                        </label>

                        <input
                            type="number"
                            step="0.5"
                            min="0"
                            name="credit"
                            value="{{ old('credit') }}"
                            placeholder="3"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    {{-- Type --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium mb-2">
                            Subject Type
                        </label>

                        <select
                            name="type"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">

                            <option value="">Select Type</option>

                            <option value="theory" {{ old('type') == 'theory' ? 'selected' : '' }}>
                                Theory
                            </option>

                            <option value="lab" {{ old('type') == 'lab' ? 'selected' : '' }}>
                                Lab
                            </option>

                        </select>

                    </div>

                </div>

                <div class="flex items-center justify-end gap-3 mt-8">

                    <a
                        href="{{ route('subjects.index') }}"
                        class="px-5 py-2.5 rounded-xl border border-ink-200 dark:border-ink-700 hover:bg-ink-100 dark:hover:bg-ink-800 transition">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white transition shadow-lg shadow-brand-500/20">

                        <i class="fa-solid fa-floppy-disk mr-2"></i>

                        Save Subject

                    </button>

                </div>

            </form>

        </div>

    </div>

</main>
@endsection
