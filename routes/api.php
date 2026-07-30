<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiController::class, 'index']);
Route::get('/current-session', [ApiController::class, 'current_session']);
Route::get('/settings', [ApiController::class, 'settings']);
Route::get('/sessions', [ApiController::class, 'sessions']);
Route::get('/sessions/show/{token}', [ApiController::class, 'showSession']);

Route::post('/register-student', [ApiController::class, 'store']);

Route::get('/embeddings', [ApiController::class, 'embeddings']);
// Route::post('/take-attendance', [ApiController::class, 'takeAttendance']);
Route::post('/take-attendance', function (Request $request) {
    return response()->json([
        'ok' => true,
        'data' => $request->all(),
    ]);
});
