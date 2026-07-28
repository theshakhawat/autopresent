@extends('layout.app')

@section('title', 'Edit Student')

@section('content')
<main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold">
                Edit Student
            </h1>

            <p class="text-sm text-ink-400 mt-1">
                Update student information.
            </p>
        </div>

        <a href="{{ route('students.index') }}"
            class="inline-flex items-center gap-2 border border-ink-200 dark:border-ink-700 hover:bg-ink-100 dark:hover:bg-ink-800 px-4 py-2.5 rounded-xl transition">

            <i class="fa-solid fa-arrow-left"></i>
            Back
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-2xl p-5">

            <div class="flex gap-3">

                <div class="w-10 h-10 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>

                <div>

                    <h4 class="font-semibold text-red-600">
                        Validation Error
                    </h4>

                    <ul class="mt-2 ml-5 list-disc text-sm text-red-500 space-y-1">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif

    <div class="bg-white dark:bg-ink-850 rounded-2xl border border-ink-100 dark:border-ink-700">

        <form
            action="{{ route('students.update', $student) }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 space-y-6">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Name --}}
                <div>

                    <label class="block text-sm font-medium mb-2">
                        Student Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $student->name) }}"
                        class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:ring-2 focus:ring-brand-500 outline-none">

                </div>

                {{-- Roll --}}
                <div>

                    <label class="block text-sm font-medium mb-2">
                        Roll
                    </label>

                    <input
                        type="text"
                        name="roll"
                        value="{{ old('roll', $student->roll) }}"
                        class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:ring-2 focus:ring-brand-500 outline-none">

                </div>

                {{-- Phone --}}
                <div>

                    <label class="block text-sm font-medium mb-2">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $student->phone) }}"
                        class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:ring-2 focus:ring-brand-500 outline-none">

                </div>

                {{-- Email --}}
                <div>

                    <label class="block text-sm font-medium mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $student->email) }}"
                        class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:ring-2 focus:ring-brand-500 outline-none">

                </div>

                {{-- Photo --}}
                <div class="md:col-span-2">

                    <label class="block text-sm font-medium mb-3">
                        Student Photo
                    </label>

                    <div class="flex flex-col md:flex-row gap-6 items-start">

                        {{-- Image Preview --}}
                        <div>

                            <img
                                id="preview"
                                src="{{ $student->photo_url }}"
                                alt="{{ $student->name }}"
                                class="w-36 h-36 rounded-2xl object-cover border border-ink-200 dark:border-ink-700">

                        </div>

                        <div class="flex-1">

                            <input
                                id="photo"
                                type="file"
                                name="photo"
                                accept="image/*"
                                class="block w-full rounded-xl border border-ink-200 dark:border-ink-700 file:mr-4 file:border-0 file:bg-brand-500 file:text-white file:px-4 file:py-3 file:cursor-pointer">

                            <p class="text-xs text-ink-400 mt-3">
                                Leave empty if you don't want to change the photo.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('students.index') }}"
                    class="px-5 py-3 rounded-xl border border-ink-200 dark:border-ink-700 hover:bg-ink-100 dark:hover:bg-ink-800 transition">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white shadow-lg shadow-brand-500/20 transition">

                    <i class="fa-solid fa-floppy-disk mr-2"></i>

                    Update Student

                </button>

            </div>

        </form>

    </div>

</main>

<script>
    const input = document.getElementById('photo');
    const preview = document.getElementById('preview');

    input.addEventListener('change', function () {

        if (this.files && this.files[0]) {

            preview.src = URL.createObjectURL(this.files[0]);

        }

    });
</script>

@endsection
