@extends('layout.app')

@section('title', 'Subjects')

@section('content')
<main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold">Subjects</h1>
            <p class="text-sm text-ink-400 mt-1">
                Manage all subjects from here.
            </p>
        </div>

        <a href="{{ route('subjects.create') }}"
            class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-lg shadow-brand-500/20">
            <i class="fa-solid fa-plus"></i>
            Add Subject
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div
            class="flex items-start gap-3 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl p-4">

            <div
                class="w-10 h-10 rounded-lg bg-green-500/10 text-green-600 flex items-center justify-center shrink-0">
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

    {{-- Card --}}
    <div class="bg-white dark:bg-ink-850 rounded-2xl border border-ink-100 dark:border-ink-700 overflow-hidden">

        {{-- Top --}}
        <div class="p-5 flex items-center justify-between">

            <h2 class="font-semibold text-lg">
                Subject List
            </h2>

            <span class="text-sm text-ink-400">
                Total Subjects :
                <strong>{{ $subjects->total() }}</strong>
            </span>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr
                        class="border-y border-ink-100 dark:border-ink-700 text-xs uppercase text-ink-400">

                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Code</th>
                        <th class="px-5 py-3 text-left">Name</th>
                        <th class="px-5 py-3 text-left">Teacher</th>
                        <th class="px-5 py-3 text-center">Credit</th>
                        <th class="px-5 py-3 text-center">Type</th>
                        <th class="px-5 py-3 text-center">Action</th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-ink-100 dark:divide-ink-700">

                    @forelse($subjects as $subject)

                        <tr class="hover:bg-ink-50 dark:hover:bg-ink-800">

                            <td class="px-5 py-4">
                                {{ $loop->iteration + ($subjects->currentPage() - 1) * $subjects->perPage() }}
                            </td>

                            <td class="px-5 py-4 font-medium">
                                {{ $subject->code }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $subject->name }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $subject->teacher }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                {{ $subject->credit }}
                            </td>

                            <td class="px-5 py-4 text-center">

                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $subject->type === 'theory'
                                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'
                                        : 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' }}">

                                    {{ ucfirst($subject->type) }}

                                </span>

                            </td>

                            <td class="px-5 py-4">

                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('subjects.edit', $subject) }}"
                                        class="w-9 h-9 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center hover:bg-amber-500 hover:text-white transition">

                                        <i class="fa-solid fa-pen"></i>

                                    </a>

                                    <form
                                        action="{{ route('subjects.destroy', $subject) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            onclick="return confirm('Delete this subject?')"
                                            class="w-9 h-9 rounded-lg bg-red-500/10 text-red-600 hover:bg-red-500 hover:text-white transition">

                                            <i class="fa-solid fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-12 text-ink-400">

                                <i class="fa-solid fa-book text-3xl mb-3 block"></i>

                                No subjects found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="p-5 border-t border-ink-100 dark:border-ink-700">

            {{ $subjects->links() }}

        </div>

    </div>

</main>
@endsection
