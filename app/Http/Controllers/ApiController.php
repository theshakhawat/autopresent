<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\RegistrationSetting;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        if ($session) {
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
        $session = AttendanceSession::with(['subject', 'attendances', 'attendances.student'])->where('session_token', $token)->firstOrFail();

        if ($session) {
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


    public function store(Request $request)
    {

        // 1. Custom Validation Check
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'roll'      => 'required|string|max:100|unique:students,roll',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:20',
            'image'     => 'required|string', // Base64 String
            'embedding' => 'required',        // Stringified JSON Array or Array
        ], [
            'roll.unique' => 'This Roll number is already registered!',
            'image.required' => 'Face image is required.',
            'embedding.required' => 'Face embedding data is missing.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(), // Send primary error message
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // 2. Decode and Save Base64 Photo
            $photoPath = $this->uploadBase64Image($request->input('image'));

            // 3. Process Embedding (JSON String or Array to Array format)
            $rawEmbedding = $request->input('embedding');
            $embeddingData = is_string($rawEmbedding)
                ? json_decode($rawEmbedding, true)
                : $rawEmbedding;

            // 4. Create Student in Database
            $student = Student::create([
                'name'           => $request->input('name'),
                'roll'           => $request->input('roll'),
                'email'          => $request->input('email') ?: null,
                'phone'          => $request->input('phone') ?: null,
                'photo'          => $photoPath,
                'face_embedding' => $embeddingData,
                'status'         => 'pending', // Default status as requested in JS alert ('Wait for account active!')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student registered successfully!',
                'student' => $student
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save student data: ' . $e->getMessage()
            ], 500);
        }
    }

    private function uploadBase64Image($base64String)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
            $data = substr($base64String, strpos($base64String, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, etc.

            $data = base64_decode($data);
            if ($data === false) {
                return null;
            }

            $fileName = 'students/' . Str::uuid() . '.' . $type;

            // Storage inside storage/app/public/students
            Storage::disk('public')->put($fileName, $data);

            return  $fileName;
        }

        return null;
    }
}
