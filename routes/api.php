<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiController::class, 'index']);
Route::get('/embeddings', [ApiController::class, 'embeddings']);
Route::get('/current-session', [ApiController::class, 'current_session']);
