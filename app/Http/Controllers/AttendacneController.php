<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendacneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::with([
            'student',
            'subject',
        ])->latest()->paginate(20);

        return view('admin.attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::orderBy('name')->get();

        $attendanceSessions = AttendanceSession::with('subject')
            ->latest()
            ->get();

        return view('admin.attendances.create', compact(
            'students',
            'attendanceSessions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendance_session_id' => ['required', 'exists:attendance_sessions,id'],
            'student_id'            => ['required', 'exists:students,id'],
            'status'                => ['required', 'in:present,absent'],
            'attendance_time'       => ['required', 'date'],
        ]);

        Attendance::create($validated);

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance created successfully.');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $students = Student::orderBy('name')->get();

        $attendanceSessions = AttendanceSession::with('subject')
            ->latest()
            ->get();

        return view('admin.attendances.edit', compact(
            'attendance',
            'students',
            'attendanceSessions'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'attendance_session_id' => ['required', 'exists:attendance_sessions,id'],
            'student_id'            => ['required', 'exists:students,id'],
            'status'                => ['required', 'in:present,absent'],
            'attendance_time'       => ['required', 'date'],
        ]);

        $attendance->update($validated);

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance deleted successfully.');
    }
}
