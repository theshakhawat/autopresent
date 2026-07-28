<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttendanceSessionController;
use App\Http\Controllers\AttendacneController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'welcome'])->name('home');

// Sessions
Route::get('/sessions', [HomeController::class, 'sessions'])->name('sessions.index');
Route::get('/attendance-history', [HomeController::class, 'attendanceHistory'])->name('attendance-history');
Route::post('/attendance/history/check', [HomeController::class, 'attendanceHistoryCheck'])->name('attendance-history.check');
Route::get('/sessions/show/{token}', [HomeController::class, 'showSession'])->name('sessions.show');

// Self Registration
Route::get('student-register', [StudentRegistrationController::class, 'create'])->name('student-register');
Route::get('/students/embeddings', [StudentRegistrationController::class, 'embeddings'])->name('student-embeddings');
Route::post('student-register', [StudentRegistrationController::class, 'store'])->name('student-register.store');

Route::get('student/attendance/take/{token}', [StudentRegistrationController::class, 'attendance'])->name('student-attendance.take')->middleware('whitelist.ip');
Route::post('/attendance/submit/', [StudentRegistrationController::class, 'attendanceSubmit'])->name('student-attendanceSubmit.take');

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');
});


Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/change-password', [AdminController::class, 'changePassword'])->name('admin.change-password');
    Route::post('/change-password', [AdminController::class, 'updatePassword'])->name('admin.update-password');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/profile', [AdminController::class, 'edit'])
        ->name('profile.edit');
    Route::post('/profile', [AdminController::class, 'update'])
        ->name('profile.update');
    // Student
    Route::resource('students', StudentController::class);
    Route::patch('/students/{student}/status', [StudentController::class, 'updateStatus'])
        ->name('students.update-status');

    Route::resource('subjects', SubjectController::class);
    Route::resource('attendance-sessions', AttendanceSessionController::class)
        ->except('show');
    Route::post(
        'attendance-sessions/{attendanceSession}/start',
        [AttendanceSessionController::class, 'start']
    )->name('attendance-sessions.start');
    Route::post(
        'attendance-sessions/{attendanceSession}/close',
        [AttendanceSessionController::class, 'close']
    )->name('attendance-sessions.close');
    Route::post(
        'attendance-sessions/{attendanceSession}/export',
        [AttendanceSessionController::class, 'export']
    )->name('attendance-sessions.export');

    // Registration
    Route::get('registration-settings',[AdminController::class, 'registrationSettings'])->name('registration-settings.index');
    Route::post('registration-settings/update',[AdminController::class, 'updateRegistrationSettings'])->name('registration-settings.update');
    Route::post('registration-settings/similarity',[AdminController::class, 'updateRegisterSimilarity'])->name('registration-settings.update-similarity');
    Route::post('registration-settings/ip_status',[AdminController::class, 'ip_status'])->name('ip_status');
    Route::post('registration-settings/whitelist_ips',[AdminController::class, 'whitelist_ips'])->name('whitelist_ips');


    // Attendance
    Route::resource('attendances', AttendacneController::class);
});
