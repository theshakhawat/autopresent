@extends('layout.app')

@section('title', 'Create Attendance Session')

@section('content')
<main class="flex-1 overflow-y-auto p-4 sm:p-6">

    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">

            <h1 class="text-2xl font-bold font-display">
                Create Attendance Session
            </h1>

            <p class="text-sm text-ink-400 mt-1">
                Create a new attendance session.
            </p>

        </div>


        {{-- Error Message --}}
        @if ($errors->any())

            <div
                class="mb-6 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl p-4">

                <div class="flex items-start gap-3">

                    <div
                        class="w-10 h-10 rounded-lg bg-red-500/10 text-red-600 flex items-center justify-center shrink-0">

                        <i class="fa-solid fa-circle-exclamation"></i>

                    </div>

                    <div>

                        <h4 class="font-semibold text-red-700 dark:text-red-400">
                            Validation Error
                        </h4>


                        <ul class="mt-2 text-sm text-red-600 dark:text-red-300 list-disc list-inside space-y-1">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif



        {{-- Form Card --}}
        <div
            class="bg-white dark:bg-ink-850 border border-ink-100 dark:border-ink-700 rounded-2xl p-6">


            <form action="{{ route('attendance-sessions.store') }}" method="POST">

                @csrf


                <div class="space-y-6">


                    {{-- Subject --}}
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Select Subject
                        </label>


                        <select
                            name="subject_id"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">


                            <option value="" class="dark:bg-gray-900 bg-gray-100">
                                Select Subject
                            </option>


                            @foreach($subjects as $subject)

                                <option class="dark:bg-gray-900 bg-gray-100"
                                    value="{{ $subject->id }}"
                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>

                                    {{ $subject->name }} ({{ $subject->code }})

                                </option>

                            @endforeach


                        </select>

                    </div>



                    {{-- Date --}}
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Attendance Date
                        </label>


                        <input
                            type="date"
                            name="date"
                            value="{{ old('date', date('Y-m-d')) }}"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">


                    </div>



                    {{-- Info Box --}}
                    <div
                        class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl p-4">

                        <div class="flex gap-3">

                            <div
                                class="w-10 h-10 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-circle-info"></i>

                            </div>


                            <div>

                                <h4 class="font-semibold text-blue-700 dark:text-blue-400">
                                    Note
                                </h4>


                                <p class="text-sm text-blue-600 dark:text-blue-300 mt-1">

                                    After starting this attendance session,
                                    subject and date cannot be changed.

                                </p>

                            </div>

                        </div>

                    </div>


                </div>



                {{-- Buttons --}}
                <div class="flex justify-end gap-3 mt-8">


                    <a href="{{ route('attendance-sessions.index') }}"
                        class="px-5 py-2.5 rounded-xl border border-ink-200 dark:border-ink-700 hover:bg-ink-100 dark:hover:bg-ink-800 transition">

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white transition shadow-lg shadow-brand-500/20">


                        <i class="fa-solid fa-calendar-plus mr-2"></i>

                        Create Session


                    </button>


                </div>


            </form>


        </div>


    </div>

</main>
@endsection
