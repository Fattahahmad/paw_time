<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GrowthController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\ReminderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
| Base URL: /api
|
*/

// ============================================
// PUBLIC ROUTES (No authentication required)
// ============================================

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Cron endpoint for scheduled notifications (secured by secret token)
Route::get('/cron/send-notifications', function () {
    $secret = request()->header('X-Cron-Secret') ?? request()->query('secret');

    if ($secret !== config('services.cron.secret')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    \Illuminate\Support\Facades\Artisan::call('reminders:send-notifications');

    return response()->json([
        'success' => true,
        'message' => 'Notification check completed',
        'output' => \Illuminate\Support\Facades\Artisan::output(),
    ]);
});

// ============================================
// PROTECTED ROUTES (Authentication required)
// ============================================

Route::middleware('auth:sanctum')->group(function () {

    // --------------------------------------------
    // AUTH ROUTES
    // --------------------------------------------
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::put('/user', [AuthController::class, 'updateProfile']);
        Route::put('/password', [AuthController::class, 'changePassword']);
    });

    // --------------------------------------------
    // DASHBOARD ROUTES
    // --------------------------------------------
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/stats', [DashboardController::class, 'stats']);
        Route::get('/activity', [DashboardController::class, 'activity']);
    });

    // --------------------------------------------
    // PETS ROUTES
    // GET    /api/pets              - List all pets (filters: species, gender, search)
    // POST   /api/pets              - Create pet
    // GET    /api/pets/{id}         - Get pet detail
    // PUT    /api/pets/{id}         - Update pet
    // DELETE /api/pets/{id}         - Delete pet
    // --------------------------------------------
    Route::apiResource('pets', PetController::class);

    // --------------------------------------------
    // REMINDERS ROUTES
    // GET    /api/reminders              - List reminders (filters: pet_id, category, repeat_type/schedule, status, date, date_from, date_to, upcoming, today)
    // POST   /api/reminders              - Create reminder
    // GET    /api/reminders/{id}         - Get reminder detail
    // PUT    /api/reminders/{id}         - Update reminder
    // DELETE /api/reminders/{id}         - Delete reminder
    // PATCH  /api/reminders/{id}/done    - Mark as done
    // PATCH  /api/reminders/{id}/skip    - Mark as skipped
    // GET    /api/reminders/calendar     - Get calendar data
    // GET    /api/reminders/filters      - Get filter options
    // --------------------------------------------
    Route::get('/reminders/filters', [ReminderController::class, 'filters']);
    Route::get('/reminders/calendar', [ReminderController::class, 'calendar']);
    Route::patch('/reminders/{reminder}/done', [ReminderController::class, 'markDone']);
    Route::patch('/reminders/{reminder}/skip', [ReminderController::class, 'markSkipped']);
    Route::apiResource('reminders', ReminderController::class);

    // --------------------------------------------
    // GROWTH ROUTES
    // GET    /api/growth              - List growth records (requires: pet_id, filters: date_from, date_to, limit)
    // GET    /api/growth/chart        - Get chart data (requires: pet_id, filters: type, period)
    // GET    /api/growth/filters      - Get filter options
    // POST   /api/growth              - Create growth record
    // GET    /api/growth/{id}         - Get growth record detail
    // PUT    /api/growth/{id}         - Update growth record
    // DELETE /api/growth/{id}         - Delete growth record
    // --------------------------------------------
    Route::get('/growth/filters', [GrowthController::class, 'filters']);
    Route::get('/growth/chart', [GrowthController::class, 'chart']);
    Route::apiResource('growth', GrowthController::class);

    // --------------------------------------------
    // HEALTH CHECKS ROUTES
    // GET    /api/health-checks              - List health checks (filters: pet_id, check_type, date_from, date_to)
    // GET    /api/health-checks/filters      - Get filter options
    // GET    /api/health-checks/summary      - Get health summary (requires: pet_id)
    // POST   /api/health-checks              - Create health check
    // GET    /api/health-checks/{id}         - Get health check detail
    // PUT    /api/health-checks/{id}         - Update health check
    // DELETE /api/health-checks/{id}         - Delete health check
    // --------------------------------------------
    Route::get('/health-checks/filters', [HealthController::class, 'filters']);
    Route::get('/health-checks/summary', [HealthController::class, 'summary']);
    Route::apiResource('health-checks', HealthController::class);

    // --------------------------------------------
    // NOTIFICATION ROUTES
    // POST   /api/notifications/register   - Register FCM token
    // POST   /api/notifications/remove     - Remove FCM token
    // GET    /api/notifications/history    - Get notification history
    // POST   /api/notifications/test       - Send test notification
    // --------------------------------------------
    Route::prefix('notifications')->group(function () {
        Route::post('/register', [NotificationController::class, 'registerToken']);
        Route::post('/remove', [NotificationController::class, 'removeToken']);
        Route::get('/history', [NotificationController::class, 'history']);
        Route::post('/test', [NotificationController::class, 'sendTest']);
    });
});
