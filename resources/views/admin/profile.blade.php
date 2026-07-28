@extends('layout.app')

@section('title', 'Edit Profile')

@section('content')

<main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold">
                Edit Profile
            </h1>

            <p class="text-sm text-ink-400 mt-1">
                Update your account information.
            </p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
            class="inline-flex items-center gap-2 border border-ink-200 dark:border-ink-700 hover:bg-ink-100 dark:hover:bg-ink-800 px-4 py-2.5 rounded-xl text-sm transition">

            <i class="fa-solid fa-arrow-left"></i>
            Back
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-2xl p-5">

            <div class="flex gap-3">

                <div
                    class="w-10 h-10 rounded-lg bg-green-500/10 text-green-500 flex items-center justify-center shrink-0">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <div>

                    <h4 class="font-semibold text-green-600">
                        Success
                    </h4>

                    <p class="mt-2 text-sm text-green-500">
                        {{ session('success') }}
                    </p>

                </div>

            </div>

        </div>

    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-2xl p-5">

            <div class="flex gap-3">

                <div
                    class="w-10 h-10 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center shrink-0">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>

                <div>

                    <h4 class="font-semibold text-red-600">
                        Validation Error
                    </h4>

                    <ul class="mt-2 text-sm text-red-500 list-disc ml-5 space-y-1">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif

    {{-- Form --}}
    <div class="bg-white dark:bg-ink-850 rounded-2xl border border-ink-100 dark:border-ink-700">

        <form
            action="{{ route('profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 space-y-6">

            @csrf

            {{-- Profile Image --}}
            <div class="flex flex-col items-center gap-4">

                @if(Auth::user()->photo)

                    <img
                        src="{{ asset('storage/' . Auth::user()->photo) }}"
                        alt="Profile"
                        class="w-32 h-32 rounded-full object-cover border-4 border-brand-500 shadow">

                @else

                    <div
                        class="w-32 h-32 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-5xl text-gray-500">

                        <i class="fa-solid fa-user"></i>

                    </div>

                @endif

                <div class="w-full max-w-sm">

                    <label class="block text-sm font-medium mb-2">
                        Profile Photo
                    </label>

                    <input
                        type="file"
                        name="photo"
                        accept="image/*"
                        class="block w-full rounded-xl border border-ink-200 dark:border-ink-700 file:mr-4 file:border-0 file:bg-brand-500 file:text-white file:px-4 file:py-3 file:cursor-pointer">

                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Name --}}
                <div>

                    <label class="block text-sm font-medium mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', Auth::user()->name) }}"
                        placeholder="Enter your name"
                        class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:ring-2 focus:ring-brand-500 outline-none">

                </div>

                {{-- Email --}}
                <div>

                    <label class="block text-sm font-medium mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', Auth::user()->email) }}"
                        placeholder="example@gmail.com"
                        class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:ring-2 focus:ring-brand-500 outline-none">

                </div>

            </div>

            <div class="flex justify-end gap-3 pt-2">

                <a
                    href="{{ url()->previous() }}"
                    class="px-5 py-3 rounded-xl border border-ink-200 dark:border-ink-700 hover:bg-ink-100 dark:hover:bg-ink-800 transition">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-medium shadow-lg shadow-brand-500/20 transition">

                    <i class="fa-solid fa-floppy-disk mr-2"></i>

                    Update Profile

                </button>

            </div>

        </form>

    </div>

</main>

@endsection
