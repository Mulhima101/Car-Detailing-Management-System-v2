<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\ProfileController;

// Route::get('/', function(){
//     return view("testpage");
// });

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Service request public routes
Route::get('/service/request', [ServiceRequestController::class, 'create'])->name('service.create');
Route::post('/service/request', [ServiceRequestController::class, 'store'])->name('service.store');
Route::get('/service/thankyou/{id}', [ServiceRequestController::class, 'thankYou'])->name('service.thankyou');
Route::get('/service/status/{id}', [ServiceRequestController::class, 'status'])->name('service.status');

require __DIR__.'/auth.php';

// Admin routes (protected by authentication)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/services', [DashboardController::class, 'allServices'])->name('services');
    Route::get('/completed-services', [DashboardController::class, 'completedServices'])->name('completed-services');
    Route::get('/customers', [DashboardController::class, 'customers'])->name('customers');
    Route::get('/service/{id}', [DashboardController::class, 'viewServiceDetails'])->name('service.details');
    Route::patch('/service/{id}/status', [DashboardController::class, 'updateStatus'])->name('service.update-status');
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::post('/settings/system', [SettingsController::class, 'updateSystem'])->name('settings.system');
    Route::post('/settings/email-templates', [SettingsController::class, 'updateEmailTemplates'])->name('settings.email-templates');
    Route::post('/settings/process-queue', [SettingsController::class, 'processQueue'])->name('settings.process-queue');
    Route::post('/settings/start-queue', [SettingsController::class, 'startQueueWorker'])->name('settings.start-queue');
    Route::post('/settings/stop-queue', [SettingsController::class, 'stopQueueWorker'])->name('settings.stop-queue');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Calendar routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('admin.calendar');
    Route::get('/admin/calendar/events', [App\Http\Controllers\CalendarController::class, 'getEvents'])->name('admin.calendar.events');
    Route::post('/admin/calendar/events', [App\Http\Controllers\CalendarController::class, 'store'])->name('admin.calendar.events.store');
    Route::put('/admin/calendar/events/{id}', [App\Http\Controllers\CalendarController::class, 'update'])->name('admin.calendar.events.update');
    Route::delete('/admin/calendar/events/{id}', [App\Http\Controllers\CalendarController::class, 'destroy'])->name('admin.calendar.events.destroy');
    Route::post('/admin/calendar/sync', [App\Http\Controllers\CalendarController::class, 'syncCarServices'])->name('admin.calendar.sync');
});