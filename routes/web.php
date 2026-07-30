<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin & Coordinator only
    Route::middleware('role:admin,coordinator')->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('companies', CompanyController::class)->except(['show']);
        Route::resource('evaluations', EvaluationController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::patch('/requirements/{requirement}/status', [RequirementController::class, 'updateStatus'])->name('requirements.status');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // Admin, Coordinator, and Company reps can all approve time logs
    // (the controller itself restricts a Company user to only their own interns' logs)
    Route::middleware('role:admin,coordinator,company')->group(function () {
        Route::patch('/time-logs/{timeLog}/status', [TimeLogController::class, 'updateStatus'])->name('timelogs.status');
    });

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('coordinators', CoordinatorController::class)->except(['show']);
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });

    // Shared across all authenticated roles
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');

    // Time logs (students create their own; admin/coordinator view all — handled in controller)
    Route::get('/time-logs', [TimeLogController::class, 'index'])->name('timelogs.index');
    Route::get('/time-logs/create', [TimeLogController::class, 'create'])->name('timelogs.create');
    Route::post('/time-logs', [TimeLogController::class, 'store'])->name('timelogs.store');
    Route::delete('/time-logs/{timeLog}', [TimeLogController::class, 'destroy'])->name('timelogs.destroy');

    // Requirements (students submit; admin/coordinator review)
    Route::get('/requirements', [RequirementController::class, 'index'])->name('requirements.index');
    Route::post('/requirements', [RequirementController::class, 'store'])->name('requirements.store');
});
