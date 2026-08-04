<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\CoordinatorProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\PendingApprovalController;
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

    // Account Completion (Non-Student only) — Step 2 of registration, where
    // Designation and any conditional Office/Company fields are collected.
    Route::get('/account-completion', [AuthController::class, 'showAccountCompletion'])->name('account-completion.show');
    Route::post('/account-completion', [AuthController::class, 'storeAccountCompletion'])->name('account-completion.store');

    // Forgot / reset password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

    // Google OAuth — {type} is 'student' or 'non_student', picked from the login page buttons
    Route::get('/auth/google/{type}/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

    // Onboarding for a brand-new Non-Student Google sign-in (pick Designation, etc.)
    Route::get('/auth/google/onboarding', [GoogleAuthController::class, 'showOnboarding'])->name('google.onboarding');
    Route::post('/auth/google/onboarding', [GoogleAuthController::class, 'storeOnboarding'])->name('google.onboarding.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Authenticated routes
Route::middleware(['auth', 'profile.gate'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Intern profile completion (Student ID, Program, Year Level, etc.)
    Route::get('/profile/complete', [StudentProfileController::class, 'show'])->name('profile.complete');
    Route::post('/profile/complete', [StudentProfileController::class, 'store'])->name('profile.complete.store');

    // OJT Coordinator profile completion (Employee ID, Department, Designation, etc.)
    Route::get('/coordinator-profile/complete', [CoordinatorProfileController::class, 'show'])->name('coordinator-profile.complete');
    Route::post('/coordinator-profile/complete', [CoordinatorProfileController::class, 'store'])->name('coordinator-profile.complete.store');

    // Admin, Coordinator, and Dean (oversight role)
    Route::middleware('role:admin,coordinator,dean')->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('companies', CompanyController::class)->except(['show']);
        Route::resource('evaluations', EvaluationController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::patch('/requirements/{requirement}/status', [RequirementController::class, 'updateStatus'])->name('requirements.status');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // Admin, Coordinator, and Company reps record and approve time logs on behalf
    // of their interns — Interns themselves are view-only (see the shared
    // /time-logs route below).
    Route::middleware('role:admin,coordinator,company')->group(function () {
        Route::get('/time-logs/create', [TimeLogController::class, 'create'])->name('timelogs.create');
        Route::post('/time-logs', [TimeLogController::class, 'store'])->name('timelogs.store');
        Route::patch('/time-logs/{timeLog}/status', [TimeLogController::class, 'updateStatus'])->name('timelogs.status');
        Route::delete('/time-logs/{timeLog}', [TimeLogController::class, 'destroy'])->name('timelogs.destroy');
    });

    // Admin only — direct Coordinator account management (not approval, just CRUD)
    Route::middleware('role:admin')->group(function () {
        Route::resource('coordinators', CoordinatorController::class)->except(['show']);
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });

    // Admin and Dean — Pending Approvals (the controller itself scopes what
    // a Dean can see/act on vs. what only Admin can)
    Route::middleware('role:admin,dean')->group(function () {
        Route::get('/pending-approvals', [PendingApprovalController::class, 'index'])->name('pending-approvals.index');
        Route::patch('/pending-approvals/{pendingApproval}/approve', [PendingApprovalController::class, 'approve'])->name('pending-approvals.approve');
        Route::delete('/pending-approvals/{pendingApproval}/reject', [PendingApprovalController::class, 'reject'])->name('pending-approvals.reject');
    });

    // Shared across all authenticated roles
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');

    // Time logs — everyone can view (Interns see only their own, scoped in the controller)
    Route::get('/time-logs', [TimeLogController::class, 'index'])->name('timelogs.index');

    // Requirements (students submit; admin/coordinator review)
    Route::get('/requirements', [RequirementController::class, 'index'])->name('requirements.index');
    Route::post('/requirements', [RequirementController::class, 'store'])->name('requirements.store');
});
