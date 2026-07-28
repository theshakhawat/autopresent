@extends('layout.app')

@section('title', 'Change Password')

@section('content')
<main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold">
                Change Password
            </h1>

            <p class="text-sm text-ink-400 mt-1">
                Update your account password securely.
            </p>
        </div>

        <a href="{{ url()->previous() }}"
            class="inline-flex items-center gap-2 border border-ink-200 dark:border-ink-700 hover:bg-ink-100 dark:hover:bg-ink-800 px-4 py-2.5 rounded-xl text-sm transition">

            <i class="fa-solid fa-arrow-left"></i>
            Back
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-2xl p-5">

            <div class="flex gap-3">

                <div class="w-10 h-10 rounded-lg bg-green-500/10 text-green-500 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div>
                    <h4 class="font-semibold text-green-600">
                        Success
                    </h4>

                    <p class="mt-1 text-sm text-green-500">
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

                <div class="w-10 h-10 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center shrink-0">
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

        <form action="{{ route('admin.update-password') }}" method="POST" class="p-6 space-y-6">

            @csrf

            <div class="grid grid-cols-1 gap-6">

                {{-- Current Password --}}
                <div>

                    <label class="block text-sm font-medium mb-2">
                        Current Password <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">

                        <input
                            type="password"
                            name="current_password"
                            id="current_password"
                            placeholder="Enter current password"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 pr-12 focus:ring-2 focus:ring-brand-500 outline-none">

                        <button
                            type="button"
                            onclick="togglePassword('current_password', this)"
                            class="absolute inset-y-0 right-0 px-4 text-ink-400 hover:text-brand-500">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                </div>

                {{-- New Password --}}
                <div>

                    <label class="block text-sm font-medium mb-2">
                        New Password <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">

                        <input
                            type="password"
                            name="new_password"
                            id="new_password"
                            placeholder="Enter new password"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 pr-12 focus:ring-2 focus:ring-brand-500 outline-none">

                        <button
                            type="button"
                            onclick="togglePassword('new_password', this)"
                            class="absolute inset-y-0 right-0 px-4 text-ink-400 hover:text-brand-500">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                </div>

                {{-- Confirm Password --}}
                <div>

                    <label class="block text-sm font-medium mb-2">
                        Confirm New Password <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">

                        <input
                            type="password"
                            name="new_password_confirmation"
                            id="new_password_confirmation"
                            placeholder="Confirm new password"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 pr-12 focus:ring-2 focus:ring-brand-500 outline-none">

                        <button
                            type="button"
                            onclick="togglePassword('new_password_confirmation', this)"
                            class="absolute inset-y-0 right-0 px-4 text-ink-400 hover:text-brand-500">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

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

                    <i class="fa-solid fa-key mr-2"></i>

                    Update Password

                </button>

            </div>

        </form>

    </div>

</main>

<script>
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

@endsection
