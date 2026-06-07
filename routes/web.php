<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Teacher\AssignmentController;
use App\Http\Controllers\Teacher\SubmissionController as TeacherSubmissionController;
use App\Http\Controllers\Student\ClassController as StudentClassController;
use App\Http\Controllers\Student\SubmissionController as StudentSubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/picture', [ProfileController::class, 'uploadProfilePicture'])->name('profile.upload-picture');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class);
    
    Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
        Route::get('/{auditLog}', [AuditLogController::class, 'show'])->name('show');
        Route::get('/export', [AuditLogController::class, 'export'])->name('export');
    });
});

// Teacher Routes
Route::prefix('teacher')->middleware(['auth', 'teacher'])->name('teacher.')->group(function () {
    Route::resource('classes', TeacherClassController::class);
    Route::get('/classes/{classroom}/students', [TeacherClassController::class, 'students'])->name('classes.students');
    Route::delete('/classes/{classroom}/students/{student}', [TeacherClassController::class, 'removeStudent'])->name('classes.remove-student');
    
    Route::resource('assignments', AssignmentController::class);
    Route::post('/assignments/{assignment}/grade', [TeacherSubmissionController::class, 'grade'])->name('submissions.grade');
    Route::get('/submissions/{submission}', [TeacherSubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{submission}/comment', [TeacherSubmissionController::class, 'comment'])->name('submissions.comment');
});

// Student Routes
Route::prefix('student')->middleware(['auth'])->name('student.')->group(function () {
    Route::get('/classes', [StudentClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/join', [StudentClassController::class, 'join'])->name('classes.join');
    Route::post('/classes/join', [StudentClassController::class, 'joinByCode'])->name('classes.join-code');
    Route::get('/classes/{classroom}', [StudentClassController::class, 'show'])->name('classes.show');
    Route::post('/classes/{classroom}/leave', [StudentClassController::class, 'leave'])->name('classes.leave');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/assignments/{assignment}/submit', [StudentSubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/assignments/{assignment}/submit', [StudentSubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions/{submission}', [StudentSubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{submission}/comment', [StudentSubmissionController::class, 'comment'])->name('submissions.comment');
});

require __DIR__.'/auth.php';
