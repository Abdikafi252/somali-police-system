<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CrimeController;
use App\Http\Controllers\SuspectController;
use App\Http\Controllers\PoliceCaseController;
use App\Http\Controllers\InvestigationController;
use App\Http\Controllers\ProsecutionController;
use App\Http\Controllers\CourtCaseController;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\InvestigationLogController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/password/reset', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('crimes', CrimeController::class);
    Route::get('/crimes/{crime}/pdf', [\App\Http\Controllers\CrimeController::class, 'exportPDF'])->name('crimes.pdf');
    Route::resource('suspects', SuspectController::class);
    Route::get('/cases/dashboard', [PoliceCaseController::class, 'dashboard'])->name('cases.dashboard');
    Route::resource('cases', PoliceCaseController::class);
    Route::resource('investigations', InvestigationController::class);
    Route::resource('prosecutions', ProsecutionController::class);
    Route::resource('court-cases', CourtCaseController::class);
    Route::resource('deployments', DeploymentController::class);
    Route::resource('facilities', FacilityController::class);
    Route::resource('stations', StationController::class);
    Route::resource('users', UserController::class);
    Route::resource('station-commanders', \App\Http\Controllers\StationCommanderController::class);
    Route::resource('station-officers', \App\Http\Controllers\StationOfficerController::class);
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit/export', [AuditController::class, 'export'])->name('audit.export');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/investigations/{id}/report', [InvestigationController::class, 'showReport'])->name('investigations.report');
    Route::post('/investigation-logs', [InvestigationLogController::class, 'store'])->name('investigation-logs.store');
    
    // User Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Global Search
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\NotificationController::class, 'show'])->name('show');
        Route::get('/check', [App\Http\Controllers\NotificationController::class, 'checkNew'])->name('check');
        Route::post('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear/all', [App\Http\Controllers\NotificationController::class, 'destroyAll'])->name('destroy-all');
    });

    // Chat System
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/users', [\App\Http\Controllers\ChatController::class, 'fetchUsers'])->name('chat.users');
    Route::get('/chat/messages', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.messages');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\SettingsController::class, 'index'])->name('index');
        Route::post('/theme', [App\Http\Controllers\SettingsController::class, 'updateTheme'])->name('theme');
        Route::post('/notifications', [App\Http\Controllers\SettingsController::class, 'updateNotifications'])->name('notifications');
    });
});
