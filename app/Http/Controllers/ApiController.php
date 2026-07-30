<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\RegistrationSetting;
use App\Models\Student;
use App\Services\FaceMatcher;
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
        $students = Student::where('status', 'active')
            ->whereNotNull('face_embedding')
            ->get();

        $data = [];

        foreach ($students as $student) {
            $embedding = FaceMatcher::parse($student->face_embedding);

            // empty [] skip
            if (count($embedding) < 64) {
                continue;
            }

            $data[] = [
                'id'        => $student->id,
                'name'      => $student->name,
                'roll'      => $student->roll,
                'photo_url' => $student->photo ? asset('storage/' . $student->photo) : null,
                'email'     => $student->email,
                'phone'     => $student->phone,
                'embedding' => $embedding,
            ];
        }

        return response()->json($data);
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
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'roll'      => 'required|string|max:100|unique:students,roll',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:20',
            'image'     => 'required|string',
            'embedding' => 'required',
        ], [
            'roll.unique'        => 'This Roll number is already registered!',
            'image.required'     => 'Face image is required.',
            'embedding.required' => 'Face embedding data is missing.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            /*
        |--------------------------------------------------------------------------
        | 2. Embedding process
        |--------------------------------------------------------------------------
        */
            $embeddingData = FaceMatcher::parse($request->input('embedding'));

            if (count($embeddingData) < 64) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid face data. Please scan again.',
                ], 422);
            }

            /*
        |--------------------------------------------------------------------------
        | 3. Threshold
        |--------------------------------------------------------------------------
        | তোমার Android test:
        | same face = 0.726
        | different face = -0.070
        | তাই safe threshold = 0.50
        */
            $threshold = 0.50;

            try {
                $setting = RegistrationSetting::first();

                if ($setting && $setting->similarity_threshold !== null) {
                    $threshold = (float) $setting->similarity_threshold;
                }
            } catch (\Exception $e) {
                $threshold = 0.50;
            }

            /*
        |--------------------------------------------------------------------------
        | 4. Duplicate face check
        |--------------------------------------------------------------------------
        | এখানে /api/embeddings URL hit করছি না।
        | কারণ same backend এর database থেকে সরাসরি নেয়া faster + safer।
        |
        | যেগুলা [] empty সেগুলা FaceMatcher skip করবে।
        | যেগুলার length আলাদা, যেমন 128 vs 192, সেগুলাও skip করবে।
        */
            $students = Student::where('status', 'active')
                ->whereNotNull('face_embedding')
                ->get();

            $match = FaceMatcher::findBestMatch($embeddingData, $students);

            if ($match['student'] && $match['score'] >= $threshold) {
                return response()->json([
                    'success'    => false,
                    'duplicate'  => true,
                    'message'    => 'This face is already registered as '
                        . $match['student']->name
                        . ' (Roll ' . $match['student']->roll . ').',
                    'student'    => [
                        'id'    => $match['student']->id,
                        'name'  => $match['student']->name,
                        'roll'  => $match['student']->roll,
                        'email' => $match['student']->email,
                        'phone' => $match['student']->phone,
                    ],
                    'similarity' => round($match['score'], 4),
                ], 409);
            }

            /*
        |--------------------------------------------------------------------------
        | 5. Decode and save base64 photo
        |--------------------------------------------------------------------------
        */
            $photoPath = $this->uploadBase64Image($request->input('image'));

            if (!$photoPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid face image. Please capture again.',
                ], 422);
            }

            /*
        |--------------------------------------------------------------------------
        | 6. Insert student
        |--------------------------------------------------------------------------
        */
            $student = Student::create([
                'name'           => $request->input('name'),
                'roll'           => $request->input('roll'),
                'email'          => $request->input('email') ?: null,
                'phone'          => $request->input('phone') ?: null,
                'photo'          => $photoPath,
                'face_embedding' => $embeddingData,
                'status'         => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student registered successfully!',
                'student' => [
                    'id'        => $student->id,
                    'name'      => $student->name,
                    'roll'      => $student->roll,
                    'email'     => $student->email,
                    'phone'     => $student->phone,
                    'photo_url' => asset('storage/' . $student->photo),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save student data: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function uploadBase64Image($base64String)
    {
        if (!$base64String || !is_string($base64String)) {
            return null;
        }

        if (!preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
            return null;
        }

        $data = substr($base64String, strpos($base64String, ',') + 1);
        $extension = strtolower($type[1]);

        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }

        if (!in_array($extension, ['jpg', 'png', 'webp'])) {
            return null;
        }

        $data = base64_decode($data);

        if ($data === false) {
            return null;
        }

        $fileName = 'students/' . Str::uuid() . '.' . $extension;

        Storage::disk('public')->put($fileName, $data);

        return $fileName;
    }
}
