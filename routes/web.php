<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/service-requests/create', [ServiceRequestController::class, 'create'])->name('service-requests.create');
Route::post('/service-requests', [ServiceRequestController::class, 'store'])->name('service-requests.store');
Route::get('/track-your-request', [ServiceRequestController::class, 'track'])->name('service-requests.track');
Route::get('/track-your-request/{referenceCode}', [ServiceRequestController::class, 'trackView'])->name('service-requests.track.view');
Route::get('/service-requests/{serviceRequest}/capture-email', [ServiceRequestController::class, 'captureEmailForm'])
    ->middleware('signed')
    ->name('service-requests.capture-email');
Route::post('/service-requests/{serviceRequest}/capture-email', [ServiceRequestController::class, 'captureEmailStore'])
    ->middleware('signed')
    ->name('service-requests.capture-email.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::get('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('service-requests.show');
    Route::get('/service-requests/{serviceRequest}/edit', [ServiceRequestController::class, 'edit'])->name('service-requests.edit');
    Route::put('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'update'])->name('service-requests.update');
    Route::patch('/service-requests/{serviceRequest}/status', [ServiceRequestController::class, 'updateStatus'])->name('service-requests.update-status');
    Route::get('/service-requests/{serviceRequest}/print', [ServiceRequestController::class, 'print'])->name('service-requests.print');
    Route::get('/service-requests/{serviceRequest}/pdf', [ServiceRequestController::class, 'downloadPdf'])->name('service-requests.pdf');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
});

require __DIR__.'/auth.php';
