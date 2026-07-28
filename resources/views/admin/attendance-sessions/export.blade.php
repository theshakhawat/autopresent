@extends('layout.app')

@section('title', 'Export Attendance')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">

        <div class="max-w-3xl mx-auto bg-white dark:bg-ink-850 rounded-2xl border border-ink-100 dark:border-ink-700 p-6">

            <h1 class="font-display text-2xl font-bold mb-4">Export Attendance</h1>

            <label class="block text-sm font-medium text-ink-600 mb-2">Export Content</label>
            <textarea id="exportContent" rows="12" class="w-full p-3 border rounded-lg bg-ink-50 dark:bg-ink-900 text-sm" >{{ $content ?? '' }}</textarea>

            <div class="mt-4 flex gap-2">
                <a href="javascript:void(0)" onclick="copyContent()" class="inline-flex items-center px-4 py-2 bg-brand-500 text-white rounded-lg">Copy</a>

                <a href="javascript:void(0)" onclick="downloadFile()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg">Download .txt</a>

                <a href="{{ route('attendance-sessions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-ink-700 rounded-lg">Back</a>
            </div>

        </div>

    </main>
    @include('errors.alert')
    <script>
        function copyContent(){
            const el = document.getElementById('exportContent');
            el.select();
            document.execCommand('copy');
            showAlert('Copied!','Attendance content copied to clipboard!', 'success');
        }

        function downloadFile(){
            const content = document.getElementById('exportContent').value;
            const blob = new Blob([content], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'attendance_export.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    </script>

@endsection
