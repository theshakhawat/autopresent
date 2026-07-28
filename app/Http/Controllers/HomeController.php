<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\RegistrationSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function welcome()
    {
        $session = AttendanceSession::with('subject')->whereNotNull('started_at')->whereNull('ended_at')->latest()->limit(1)->first();
        return view('welcome', compact('session'));
    }

    public function sessions()
    {
        $sesion = AttendanceSession::with('subject')->whereNotNull('started_at')->whereNull('ended_at')->latest()->limit(1)->first();
        $sesions = AttendanceSession::with('subject')->whereNotNull('started_at')->whereNotNull('ended_at')->latest()->paginate(20);
        return view('sessions', compact('sesions', 'sesion'));
    }

    public function showSession($token)
    {
        $session = AttendanceSession::with(['subject','attendances','attendances.student'])->where('session_token', $token)->firstOrFail();
        return view('session.show', compact('session'));
    }

    public function attendanceHistory()
    {
        return view('history');
    }

    public function attendanceHistoryCheck(Request $request)
    {
        $attendances = Attendance::with(['session','session.subject'])->where('student_id', $request->student_id)->get();

        return response()->json([
            'history' => $attendances,
            'total' => $attendances->count(),
        ]);
    }
}
