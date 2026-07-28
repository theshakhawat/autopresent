@extends('layout.app')

@section('title', 'Settings')

@section('content')

    <main class="flex-1 overflow-y-auto p-4 sm:p-6">

        <div class=" mx-auto">


            {{-- Header --}}
            <div class="mb-6">

                <h1 class="text-2xl font-bold font-display">
                    Settings
                </h1>

                <p class="text-sm text-ink-400 mt-1">
                    Control student access.
                </p>

            </div>


            {{-- Success --}}
            @if (session('success'))
                <div
                    class="mb-6 flex items-start gap-3 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl p-4">

                    <div class="w-10 h-10 rounded-lg bg-green-500/10 text-green-600 flex items-center justify-center">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>


                    <div>

                        <h4 class="font-semibold text-green-700 dark:text-green-400">
                            Success
                        </h4>


                        <p class="text-sm text-green-600 dark:text-green-300">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>
            @endif




            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-stretch">
                {{-- Card --}}
                <div class="bg-white dark:bg-ink-850 border border-ink-100 dark:border-ink-700 rounded-2xl p-6">
    
    
                    <form action="{{ route('registration-settings.update') }}" method="POST">
    
                        @csrf
    
    
                        <label class="block text-sm font-medium mb-3">
                            Registration Status
                        </label>
    
    
                        <select name="status"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">
    
    
                            <option value="inactive" class="dark:bg-gray-900 bg-gray-100"
                                {{ optional($setting)->status == 'inactive' ? 'selected' : '' }}>
    
                                Inactive
    
                            </option>
    
    
                            <option value="active" class="dark:bg-gray-900 bg-gray-100"
                                {{ optional($setting)->status == 'active' ? 'selected' : '' }}>
    
                                Active
    
                            </option>
    
    
                        </select>
    
    
    
                        <button type="submit"
                            class="mt-6 w-full bg-brand-500 hover:bg-brand-600 text-white py-3 rounded-xl transition shadow-lg shadow-brand-500/20">
    
                            <i class="fa-solid fa-save mr-2"></i>
    
                            Update Setting
    
                        </button>
    
    
                    </form>
    
    
                </div>
    
                {{-- Similarity Threshold --}}
                <div class="bg-white dark:bg-ink-850 border border-ink-100 dark:border-ink-700 rounded-2xl p-6">
    
                    <form action="{{ route('registration-settings.update-similarity') }}" method="POST">
    
                        @csrf
    
    
                        <label class="block text-sm font-medium mb-3">
                            Similarity Threshold (%)
                        </label>
    
                        <input type="number" name="similarity_threshold" min="0" max="1" step="0.01"
                            value="{{ old('similarity_threshold', $setting->similarity_threshold) }}"
                            placeholder="Enter similarity threshold"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">
    
                        <p class="mt-2 text-sm text-ink-400">
                            Recommended value: <strong>50 - 70</strong>. Higher values require
                            a more accurate face match.
                        </p>
    
                        <button type="submit"
                            class="mt-6 w-full bg-brand-500 hover:bg-brand-600 text-white py-3 rounded-xl transition shadow-lg shadow-brand-500/20">
    
                            <i class="fa-solid fa-sliders mr-2"></i>
    
                            Update Threshold
    
                        </button>
    
                    </form>
    
                </div>
    
                {{-- IP Blocking --}}
                <div class="bg-white dark:bg-ink-850 border border-ink-100 dark:border-ink-700 rounded-2xl p-6">
    
                     <form action="{{ route('ip_status') }}" method="POST">
    
                        @csrf
    
    
                        <label class="block text-sm font-medium mb-3">
                            IP Blocking
                        </label>
    
    
                        <select name="ip_status"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">
    
    
                            <option value="disable" class="dark:bg-gray-900 bg-gray-100"
                                {{ optional($setting)->ip_status == 'disable' ? 'selected' : '' }}>
    
                                Disable
    
                            </option>
    
    
                            <option value="enable" class="dark:bg-gray-900 bg-gray-100"
                                {{ optional($setting)->ip_status == 'enable' ? 'selected' : '' }}>
    
                                Enable
    
                            </option>
    
    
                        </select>
    
    
    
                        <button type="submit"
                            class="mt-6 w-full bg-brand-500 hover:bg-brand-600 text-white py-3 rounded-xl transition shadow-lg shadow-brand-500/20">
    
                            <i class="fa-solid fa-save mr-2"></i>
    
                            Update IP Blocking
    
                        </button>
    
    
                    </form>
    
                </div>
                
                {{-- Whitelist IPs --}}
                <div class="bg-white dark:bg-ink-850 border border-ink-100 dark:border-ink-700 rounded-2xl p-6">
    
                    <form action="{{ route('whitelist_ips') }}" method="POST">
    
                        @csrf
    
    
                        <label class="block text-sm font-medium mb-3">
                            Whitelist IPs
                        </label>
    
                     <textarea
                            name="whitelist_ips"
                            rows="5"
                            placeholder="Example:&#10;192.168.1.10,192.168.1.11&#10;203.0.113.5"
                            class="w-full rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('whitelist_ips', $setting->whitelist_ips) }}</textarea>
                        
                        @error('whitelist_ips')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
    
                        <p class="mt-2 text-sm text-ink-400">
                            Note: use <strong>,(comma)</strong> to sperate multiple ips
                        </p>
                        
                        <small>Your current Public IP: {{ request()->ip() }}</small>
    
                        <button type="submit"
                            class="mt-6 w-full bg-brand-500 hover:bg-brand-600 text-white py-3 rounded-xl transition shadow-lg shadow-brand-500/20">
    
                            <i class="fa-solid fa-sliders mr-2"></i>
    
                            Update IP List
    
                        </button>
    
                    </form>
    
                </div>
    
            </div>
            
        </div>

    </main>

@endsection
