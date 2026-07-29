<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\RegistrationSetting;
use App\Models\Student;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'API is working!']);
    }

    public function embeddings()
    {

        $students = Student::whereNotNull('face_embedding') //->where('status', 'active')
            ->select('id', 'name', 'roll', 'face_embedding', 'photo', 'phone', 'email')
            ->get()
            ->map(function ($student) {
                return [
                    'id'        => $student->id,
                    'name'      => $student->name,
                    'roll'      => $student->roll,
                    'photo_url'      => $student->photo_url,
                    'email'      => $student->email,
                    // JS fetch functions search for 'embedding' key
                    'embedding' => $student->face_embedding,
                ];
            });

        return response()->json($students);
    }

    public function current_session()
    {
        $session = AttendanceSession::with('subject')->whereNotNull('started_at')->whereNull('ended_at')->latest()->limit(1)->first();

        if($session) {
            return response()->json([
                'session' => $session,
                'status' => true,
            ]);
        }

        return response()->json([
            'session' => $session,
            'status' => false,
        ]);
    }

    public function settings()
    {
        $settings = RegistrationSetting::first();
        return response()->json($settings);
    }

    public function sessions()
    {
        $active_session = AttendanceSession::with('subject')->whereNotNull('started_at')->whereNull('ended_at')->latest()->limit(1)->first();
        $sessions = AttendanceSession::with('subject')->withCount('attendances')->whereNotNull('started_at')->whereNotNull('ended_at')->latest()->get();

        return response()->json([
            'active_session' => $active_session,
            'sessions' => $sessions,
        ]);
    }

    public function showSession($token)
    {
        $session = AttendanceSession::with(['subject','attendances','attendances.student'])->where('session_token', $token)->firstOrFail();

        if($session) {
            return response()->json([
                'session' => $session,
                'status' => true,
            ]);
        }

        return response()->json([
            'session' => $session,
            'status' => false,
        ]);
    }

}
