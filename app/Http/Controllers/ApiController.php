<?php

namespace App\Http\Controllers;

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

    
}
