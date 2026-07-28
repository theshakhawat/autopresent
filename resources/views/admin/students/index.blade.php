@extends('layout.app')

@section('title', 'Students')

@section('content')
<main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold">Students</h1>
            <p class="text-sm text-ink-400 mt-1">
                Manage all students from here.
            </p>
        </div>

        <a href="{{ route('students.create') }}"
            class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-lg shadow-brand-500/20">
            <i class="fa-solid fa-plus"></i>
            Add Student
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="flex items-start gap-3 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl p-4">
            <div class="w-10 h-10 rounded-lg bg-green-500/10 text-green-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <h4 class="font-semibold text-green-700 dark:text-green-400">Success</h4>
                <p class="text-sm text-green-600 dark:text-green-300 mt-1">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white dark:bg-ink-850 rounded-2xl border border-ink-100 dark:border-ink-700 overflow-hidden">

        {{-- Top --}}
        <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-ink-400"></i>
                    <input type="text" name="search" value="{{ request()->search }}"
                        placeholder="Search student..."
                        class="w-72 pl-11 pr-4 py-2.5 rounded-xl border border-ink-200 dark:border-ink-700 bg-transparent focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </form>

            <span class="text-sm text-ink-400">
                Total Students : <strong>{{ $students->total() }}</strong>
            </span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-y border-ink-100 dark:border-ink-700 text-xs uppercase text-ink-400">
                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Photo</th>
                        <th class="px-5 py-3 text-left">Roll</th>
                        <th class="px-5 py-3 text-left">Name</th>
                        <th class="px-5 py-3 text-left">Phone</th>
                        <th class="px-5 py-3 text-left">Email</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-ink-100 dark:divide-ink-700">
                    @forelse($students as $student)
                        <tr class="hover:bg-ink-50 dark:hover:bg-ink-800">
                            <td class="px-5 py-4">
                                {{ $loop->iteration + ($students->currentPage()-1) * $students->perPage() }}
                            </td>

                            <td class="px-5 py-4">
                                <img src="{{ $student->photo_url }}" class="w-11 h-11 rounded-full object-cover">
                            </td>

                            <td class="px-5 py-4">
                                {{ $student->roll }}
                            </td>

                            <td class="px-5 py-4 font-medium">
                                {{ $student->name }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $student->phone }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $student->email }}
                            </td>

                            {{-- Dynamic Status Column with Quick Action Badges --}}
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-1 bg-ink-50 dark:bg-ink-800 p-1 rounded-xl border border-ink-100 dark:border-ink-700">
                                    {{-- Active Badge / Button --}}
                                    <form action="{{ route('students.update-status', $student) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit"
                                            class="px-2.5 py-1 text-xs font-semibold rounded-lg transition {{ $student->status === 'active' ? 'bg-emerald-500 text-white shadow-sm' : 'text-ink-400 hover:text-emerald-600' }}"
                                            title="Set status to active">
                                            Active
                                        </button>
                                    </form>

                                    {{-- Pending Badge / Button --}}
                                    <form action="{{ route('students.update-status', $student) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit"
                                            class="px-2.5 py-1 text-xs font-semibold rounded-lg transition {{ $student->status === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'text-ink-400 hover:text-amber-600' }}"
                                            title="Set status to pending">
                                            Pending
                                        </button>
                                    </form>

                                    {{-- Blocked Badge / Button --}}
                                    <form action="{{ route('students.update-status', $student) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="blocked">
                                        <button type="submit"
                                            class="px-2.5 py-1 text-xs font-semibold rounded-lg transition {{ $student->status === 'blocked' ? 'bg-rose-500 text-white shadow-sm' : 'text-ink-400 hover:text-rose-600' }}"
                                            title="Set status to blocked">
                                            Blocked
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('students.show', $student) }}"
                                        class="w-9 h-9 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center hover:bg-blue-500 hover:text-white transition">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a href="{{ route('students.edit', $student) }}"
                                        class="w-9 h-9 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center hover:bg-amber-500 hover:text-white transition">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ route('students.destroy', $student) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete this student?')"
                                            class="w-9 h-9 rounded-lg bg-red-500/10 text-red-600 hover:bg-red-500 hover:text-white transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-ink-400">
                                <i class="fa-solid fa-users text-3xl mb-3 block"></i>
                                No students found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-5 border-t border-ink-100 dark:border-ink-700">
            {{ $students->links() }}
        </div>
    </div>
</main>
@endsection
