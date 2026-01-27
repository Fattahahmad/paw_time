<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthCheck;
use App\Models\Pet;
use App\Models\Reminder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard data.
     *
     * GET /api/dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get stats
        $totalPets = $user->pets()->count();
        $totalReminders = Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))->count();
        $pendingReminders = Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->where('status', 'pending')->count();
        $completedReminders = Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->where('status', 'done')->count();

        // Upcoming reminders (next 7 days)
        $upcomingReminders = Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->where('status', 'pending')
            ->whereDate('remind_date', '>=', now())
            ->whereDate('remind_date', '<=', now()->addDays(7))
            ->with('pet:id,pet_name,species,image_url')
            ->orderBy('remind_date', 'asc')
            ->limit(5)
            ->get();

        // Today's reminders
        $todayReminders = Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->where('status', 'pending')
            ->whereDate('remind_date', now())
            ->with('pet:id,pet_name,species,image_url')
            ->orderBy('remind_date', 'asc')
            ->get();

        // Overdue reminders
        $overdueReminders = Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->where('status', 'pending')
            ->whereDate('remind_date', '<', now())
            ->count();

        // Recent pets
        $pets = $user->pets()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_pets' => $totalPets,
                    'total_reminders' => $totalReminders,
                    'pending_reminders' => $pendingReminders,
                    'completed_reminders' => $completedReminders,
                    'overdue_reminders' => $overdueReminders,
                ],
                'today_reminders' => $todayReminders->map(fn($r) => $this->formatReminder($r)),
                'upcoming_reminders' => $upcomingReminders->map(fn($r) => $this->formatReminder($r)),
                'pets' => $pets->map(fn($p) => $this->formatPet($p)),
            ]
        ]);
    }

    /**
     * Get quick stats only.
     *
     * GET /api/dashboard/stats
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'total_pets' => $user->pets()->count(),
                'pending_reminders' => Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
                    ->where('status', 'pending')->count(),
                'today_tasks' => Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
                    ->where('status', 'pending')
                    ->whereDate('remind_date', now())->count(),
                'overdue_tasks' => Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
                    ->where('status', 'pending')
                    ->whereDate('remind_date', '<', now())->count(),
                'health_checks_this_month' => HealthCheck::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
                    ->whereMonth('check_date', now()->month)
                    ->whereYear('check_date', now()->year)->count(),
            ]
        ]);
    }

    /**
     * Get activity feed.
     *
     * GET /api/dashboard/activity
     */
    public function activity(Request $request)
    {
        $user = $request->user();
        $limit = $request->get('limit', 10);

        // Get recent completed reminders
        $completedReminders = Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->where('status', 'done')
            ->with('pet:id,pet_name')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($r) {
                return [
                    'type' => 'reminder_completed',
                    'message' => "Completed: {$r->title} for {$r->pet->pet_name}",
                    'category' => $r->category,
                    'date' => $r->updated_at,
                ];
            });

        // Get recent health checks
        $healthChecks = HealthCheck::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->with('pet:id,pet_name')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($h) {
                return [
                    'type' => 'health_check',
                    'message' => "{$h->check_type} for {$h->pet->pet_name}",
                    'date' => $h->created_at,
                ];
            });

        // Merge and sort by date
        $activities = $completedReminders->concat($healthChecks)
            ->sortByDesc('date')
            ->take($limit)
            ->values();

        return response()->json([
            'success' => true,
            'data' => $activities,
        ]);
    }

    /**
     * Format reminder for response.
     */
    private function formatReminder(Reminder $reminder)
    {
        return [
            'id' => $reminder->id,
            'title' => $reminder->title,
            'category' => $reminder->category,
            'remind_date' => $reminder->remind_date->format('Y-m-d'),
            'remind_time' => $reminder->remind_date->format('H:i'),
            'remind_formatted' => $reminder->remind_date->format('M d - h:i A'),
            'status' => $reminder->status,
            'pet' => [
                'id' => $reminder->pet->id,
                'name' => $reminder->pet->pet_name,
                'species' => $reminder->pet->species,
                'image_url' => $reminder->pet->image_url ? url($reminder->pet->image_url) : null,
            ],
        ];
    }

    /**
     * Format pet for response.
     */
    private function formatPet(Pet $pet)
    {
        return [
            'id' => $pet->id,
            'pet_name' => $pet->pet_name,
            'species' => $pet->species,
            'breed' => $pet->breed,
            'gender' => $pet->gender,
            'image_url' => $pet->image_url ? url($pet->image_url) : null,
            'age' => $pet->birth_date ? $this->calculateAge($pet->birth_date) : null,
        ];
    }

    /**
     * Calculate pet age.
     */
    private function calculateAge($birthDate)
    {
        $birth = \Carbon\Carbon::parse($birthDate);
        $now = now();

        // Handle future birth date
        if ($birth->isFuture()) {
            return 'Not born yet';
        }

        $years = (int) $birth->diffInYears($now);
        $months = (int) $birth->copy()->addYears($years)->diffInMonths($now);

        if ($years > 0) {
            $result = $years . ' year' . ($years > 1 ? 's' : '');
            if ($months > 0) {
                $result .= ' ' . $months . ' month' . ($months > 1 ? 's' : '');
            }
            return $result;
        }

        if ($months > 0) {
            return $months . ' month' . ($months > 1 ? 's' : '');
        }

        // Less than a month old
        $days = (int) $birth->diffInDays($now);
        if ($days > 0) {
            return $days . ' day' . ($days > 1 ? 's' : '');
        }

        return 'Newborn';
    }
}
