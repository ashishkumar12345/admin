<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ShortUrlController;

// Public Redirect Route
Route::get('/s/{code}', [ShortUrlController::class, 'redirect'])->name('shorturl.redirect');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Invitation Route
Route::get('/invite/{token}', [AuthController::class, 'showAcceptInvitation'])->name('invitation.accept');
Route::post('/invite/{token}', [AuthController::class, 'acceptInvitation']);

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [ShortUrlController::class, 'index'])->name('dashboard');

    Route::middleware(['role:SuperAdmin'])->group(function () {
        Route::post('/superadmin/invite-company', [CompanyController::class, 'inviteCompanyAdmin'])->name('company.invite');
    });

    Route::middleware(['role:Admin,Member'])->group(function () {
        Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('shorturl.store');
    });

    Route::middleware(['role:Admin'])->group(function () {
        Route::post('/admin/invite-member', [InvitationController::class, 'inviteUser'])->name('member.invite');
    });
    Route::get('/export/short-urls', [ShortUrlController::class, 'exportShortUrls'])->name('export.shorturls');
    Route::get('/export/companies', [ShortUrlController::class, 'exportCompanies'])->name('export.companies');
});