<?php

use Illuminate\Support\Facades\Route;

Route::name('pages.')->group(function () {
    Route::view('/', 'pages.home')->name('home');
    Route::view('/about', 'pages.about')->name('about');
    Route::view('/contact', 'pages.contact')->name('contact');
});

Route::name('auth.')->group(function () {
    Route::get('/login', function() {
        return view('pages.auth');
    })->name('login')->middleware('guest');

    Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])->middleware('guest');
    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->middleware('guest');
    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
});

Route::middleware(['auth'])->name('user.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chart', [App\Http\Controllers\User\ChartController::class, 'index'])->name('chart');
    Route::get('/reminder', [App\Http\Controllers\User\ReminderController::class, 'index'])->name('reminder');
    Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/health', [App\Http\Controllers\User\HealthController::class, 'index'])->name('health');

    // Pet CRUD
    Route::get('/pets/{pet}', [App\Http\Controllers\User\ProfileController::class, 'getPetDetail'])->name('pets.show');
    Route::post('/pets', [App\Http\Controllers\User\PetController::class, 'store'])->name('pets.store');
    Route::put('/pets/{pet}', [App\Http\Controllers\User\PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{pet}', [App\Http\Controllers\User\PetController::class, 'destroy'])->name('pets.destroy');

    // Reminder CRUD
    Route::post('/reminders', [App\Http\Controllers\User\ReminderController::class, 'store'])->name('reminders.store');
    Route::put('/reminders/{reminder}', [App\Http\Controllers\User\ReminderController::class, 'update'])->name('reminders.update');
    Route::patch('/reminders/{reminder}/done', [App\Http\Controllers\User\ReminderController::class, 'markDone'])->name('reminders.done');
    Route::delete('/reminders/{reminder}', [App\Http\Controllers\User\ReminderController::class, 'destroy'])->name('reminders.destroy');

    // Growth Chart
    Route::post('/growth', [App\Http\Controllers\User\ChartController::class, 'store'])->name('growth.store');

    // Health Checks
    Route::post('/health-checks', [App\Http\Controllers\User\HealthController::class, 'store'])->name('health.store');
    Route::put('/health-checks/{healthCheck}', [App\Http\Controllers\User\HealthController::class, 'update'])->name('health.update');
    Route::delete('/health-checks/{healthCheck}', [App\Http\Controllers\User\HealthController::class, 'destroy'])->name('health.destroy');

    // Appointments (User-side)
    Route::get('/appointments', [App\Http\Controllers\User\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [App\Http\Controllers\User\AppointmentController::class, 'show'])->name('appointments.show');

    // Medical Records (User download)
    Route::get('/medical-records/{medicalRecord}/download', [App\Http\Controllers\User\MedicalRecordController::class, 'downloadPDF'])->name('medical-records.download');

    // Notifications (Web-specific routes)
    Route::post('/notifications/register', [App\Http\Controllers\Api\NotificationController::class, 'registerToken'])->name('notifications.register');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [App\Http\Controllers\Admin\DashboardController::class, 'export'])->name('dashboard.export');

    // User Management
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');

    // Pet Management
    Route::get('/pets', [App\Http\Controllers\Admin\PetController::class, 'index'])->name('pets.index');
    Route::get('/pets/create', [App\Http\Controllers\Admin\PetController::class, 'create'])->name('pets.create');
    Route::post('/pets', [App\Http\Controllers\Admin\PetController::class, 'store'])->name('pets.store');
    Route::get('/pets/{pet}', [App\Http\Controllers\Admin\PetController::class, 'show'])->name('pets.show');
    Route::get('/pets/{pet}/edit', [App\Http\Controllers\Admin\PetController::class, 'edit'])->name('pets.edit');
    Route::put('/pets/{pet}', [App\Http\Controllers\Admin\PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{pet}', [App\Http\Controllers\Admin\PetController::class, 'destroy'])->name('pets.destroy');

    // Appointments
    Route::get('/appointments', [App\Http\Controllers\Admin\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [App\Http\Controllers\Admin\AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [App\Http\Controllers\Admin\AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [App\Http\Controllers\Admin\AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/{appointment}/edit', [App\Http\Controllers\Admin\AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [App\Http\Controllers\Admin\AppointmentController::class, 'update'])->name('appointments.update');
    Route::post('/appointments/{appointment}/cancel', [App\Http\Controllers\Admin\AppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Medical Records
    Route::post('/medical-records', [App\Http\Controllers\Admin\MedicalRecordController::class, 'store'])->name('medical-records.store');
    Route::get('/medical-records/{medicalRecord}', [App\Http\Controllers\Admin\MedicalRecordController::class, 'show'])->name('medical-records.show');
    Route::get('/medical-records/{medicalRecord}/download', [App\Http\Controllers\Admin\MedicalRecordController::class, 'downloadPDF'])->name('medical-records.download');

    // Reminders
    Route::get('/reminders', [App\Http\Controllers\Admin\ReminderController::class, 'index'])->name('reminders.index');
    Route::get('/reminders/create', [App\Http\Controllers\Admin\ReminderController::class, 'create'])->name('reminders.create');
    Route::post('/reminders', [App\Http\Controllers\Admin\ReminderController::class, 'store'])->name('reminders.store');
    Route::get('/reminders/{reminder}', [App\Http\Controllers\Admin\ReminderController::class, 'show'])->name('reminders.show');
    Route::get('/reminders/{reminder}/edit', [App\Http\Controllers\Admin\ReminderController::class, 'edit'])->name('reminders.edit');
    Route::put('/reminders/{reminder}', [App\Http\Controllers\Admin\ReminderController::class, 'update'])->name('reminders.update');
    Route::delete('/reminders/{reminder}', [App\Http\Controllers\Admin\ReminderController::class, 'destroy'])->name('reminders.destroy');

    // Health Checks
    Route::get('/health-checks', [App\Http\Controllers\Admin\HealthCheckController::class, 'index'])->name('health-checks.index');
    Route::get('/health-checks/create', [App\Http\Controllers\Admin\HealthCheckController::class, 'create'])->name('health-checks.create');
    Route::post('/health-checks', [App\Http\Controllers\Admin\HealthCheckController::class, 'store'])->name('health-checks.store');
    Route::get('/health-checks/{healthCheck}', [App\Http\Controllers\Admin\HealthCheckController::class, 'show'])->name('health-checks.show');
    Route::get('/health-checks/{healthCheck}/edit', [App\Http\Controllers\Admin\HealthCheckController::class, 'edit'])->name('health-checks.edit');
    Route::put('/health-checks/{healthCheck}', [App\Http\Controllers\Admin\HealthCheckController::class, 'update'])->name('health-checks.update');
    Route::delete('/health-checks/{healthCheck}', [App\Http\Controllers\Admin\HealthCheckController::class, 'destroy'])->name('health-checks.destroy');

    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [App\Http\Controllers\Admin\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::post('/settings/password', [App\Http\Controllers\Admin\SettingsController::class, 'updatePassword'])->name('settings.update-password');
});
