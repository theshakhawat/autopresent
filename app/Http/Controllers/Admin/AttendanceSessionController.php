<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttendanceSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendanceSessions = AttendanceSession::with('subject')
            ->withCount([
                'attendances as present_count' => function ($query) {
                    $query->where('status', 'present');
                },

                'attendances as absent_count' => function ($query) {
                    $query->where('status', 'absent');
                },
            ])
            ->latest()
            ->paginate(10);


        return view(
            'admin.attendance-sessions.index',
            compact('attendanceSessions')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::orderBy('name')->get();

        return view('admin.attendance-sessions.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'date'       => ['required', 'date'],
        ]);

        AttendanceSession::create([
            'subject_id'    => $validated['subject_id'],
            'date'          => $validated['date'],
            'status'        => 'active',
            'session_token' => Str::uuid(),
        ]);

        return redirect()
            ->route('attendance-sessions.index')
            ->with('success', 'Attendance session created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceSession $attendanceSession)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceSession $attendanceSession)
    {
        if ($attendanceSession->started_at) {
            return redirect()
                ->route('attendance-sessions.index')
                ->with('error', 'This session has already started and cannot be edited.');
        }

        $subjects = Subject::orderBy('name')->get();

        return view('admin.attendance-sessions.edit', compact(
            'attendanceSession',
            'subjects'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendanceSession $attendanceSession)
    {
        if ($attendanceSession->started_at) {
            return redirect()
                ->route('attendance-sessions.index')
                ->with('error', 'This session has already started and cannot be edited.');
        }

        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'date'       => ['required', 'date'],
        ]);

        $attendanceSession->update($validated);

        return redirect()
            ->route('attendance-sessions.index')
            ->with('success', 'Attendance session updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceSession $attendanceSession)
    {
        if ($attendanceSession->started_at && !$attendanceSession->ended_at) {
            return redirect()
                ->route('attendance-sessions.index')
                ->with('error', 'Started sessions cannot be deleted.');
        }

        $attendanceSession->delete();

        return redirect()
            ->route('attendance-sessions.index')
            ->with('success', 'Attendance session deleted successfully.');
    }


    public function start(AttendanceSession $attendanceSession)
    {
        if ($attendanceSession->started_at) {

            return back()
                ->with('error', 'Session already started.');
        }


        $attendanceSession->update([
            'started_at' => now(),
            'status' => 'active',
        ]);


        return back()
            ->with('success', 'Attendance session started.');
    }


    public function close(AttendanceSession $attendanceSession)
    {
        if (!$attendanceSession->started_at) {
            return back()->with('error', 'Session has not started yet.');
        }

        DB::transaction(function () use ($attendanceSession) {

            // যেসব student already attendance দিয়েছে
            $presentStudentIds = Attendance::where(
                'attendance_session_id',
                $attendanceSession->id
            )->pluck('student_id');

            // যেসব student attendance দেয়নি
            $absentStudents = Student::where('status', 'active')
                ->whereNotIn('id', $presentStudentIds)
                ->get();

            foreach ($absentStudents as $student) {
                Attendance::create([
                    'attendance_session_id' => $attendanceSession->id,
                    'student_id' => $student->id,
                    'status' => 'absent',
                    'attendance_time' => now(),
                ]);
            }

            // Session Close
            $attendanceSession->update([
                'ended_at' => now(),
                'status' => 'closed',
            ]);
        });

        return back()->with('success', 'Attendance session closed successfully.');
    }

    public function export(AttendanceSession $attendanceSession)
    {
        if (!$attendanceSession->started_at) {
            return back()
                ->with('error', 'Session has not started yet.');
        }

        if (!$attendanceSession->ended_at) {
            return back()
                ->with('error', 'Session has not ended yet.');
        }

        $attendances = $attendanceSession->attendances()->with('student')->get();

        $batch =  'CSE-105';
        $date = $attendanceSession->date ? date('y-m-d', strtotime($attendanceSession->date)) : 'N/A';
        $subject = $attendanceSession->subject->name ?? 'N/A';
        $code = $attendanceSession->subject->code ?? $attendanceSession->code ?? 'N/A';

        $lines = [];
        foreach ($attendances as $attendance) {
            $student = $attendance->student;
            $identifier = $student->roll;
            $lines[] = $identifier;
        }

        $content = "Batch: {$batch}\n";
        $content .= "Date: {$date}\n";
        $content .= "Subject: {$subject}\n";
        $content .= "Code: {$code}\n";
        $content .= "============\n";
        $content .= implode("\n", $lines);
        $content .= "\n\nTotal: " . count($lines);

        return view('admin.attendance-sessions.export', compact('content', 'batch', 'date', 'subject', 'code'));
    }
}
