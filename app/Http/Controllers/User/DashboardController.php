<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Reminder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $pets = Pet::forUser($user->id)
            ->with('latestGrowth')
            ->latest()
            ->get();

        $upcomingReminders = Reminder::whereHas('pet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->upcoming()
        ->take(5)
        ->get();

        $stats = [
            'total_pets' => Pet::forUser($user->id)->count(),
            'pending_reminders' => Reminder::whereHas('pet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'pending')->count(),
            'completed_today' => Reminder::whereHas('pet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'done')->whereDate('remind_date', today())->count(),
        ];

        return view('pages.user.dashboard', compact('user', 'pets', 'upcomingReminders', 'stats'));
    }
}
