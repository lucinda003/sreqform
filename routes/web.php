<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }

    if (app()->runningUnitTests()) {
        return view('welcome');
    }

    return redirect()->route('service-requests.track');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified']);

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');

Route::get('/service-requests/create', [ServiceRequestController::class, 'create'])->name('service-requests.create');
Route::post('/service-requests', [ServiceRequestController::class, 'store'])->name('service-requests.store');
Route::get('/track-your-request', [ServiceRequestController::class, 'track'])->name('service-requests.track');
Route::get('/track-your-request/{referenceCode}/edit', [ServiceRequestController::class, 'trackEdit'])
    ->middleware('signed')
    ->name('service-requests.track.edit');
Route::post('/track-your-request/{referenceCode}/send-edit-link', [ServiceRequestController::class, 'sendTrackEditLink'])
    ->name('service-requests.track.send-edit-link');
Route::post('/track-your-request/{referenceCode}/chat-request', [ServiceRequestController::class, 'requestTrackChat'])
    ->name('service-requests.track.chat-request');
Route::post('/track-your-request/{referenceCode}/messages', [ServiceRequestController::class, 'postTrackMessage'])
    ->name('service-requests.track.messages.store');
Route::get('/track-your-request/{referenceCode}/messages', [ServiceRequestController::class, 'trackMessages'])
    ->name('service-requests.track.messages.index');
Route::put('/track-your-request/{referenceCode}', [ServiceRequestController::class, 'trackUpdate'])
    ->middleware('signed')
    ->name('service-requests.track.update');
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
    Route::post('/service-requests/{serviceRequest}/chat-request-decision', [ServiceRequestController::class, 'decideChatRequest'])->name('service-requests.chat-request.decision');
    Route::post('/service-requests/{serviceRequest}/messages', [ServiceRequestController::class, 'postAdminMessage'])->name('service-requests.messages.store');
    Route::get('/service-requests/{serviceRequest}/messages', [ServiceRequestController::class, 'adminMessages'])->name('service-requests.messages.index');
    Route::get('/service-requests/{serviceRequest}/print', [ServiceRequestController::class, 'print'])->name('service-requests.print');
    Route::get('/service-requests/{serviceRequest}/pdf', [ServiceRequestController::class, 'downloadPdf'])->name('service-requests.pdf');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
});

require __DIR__.'/auth.php';
