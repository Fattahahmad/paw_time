<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pet;
use App\Models\Reminder;
use App\Models\HealthCheck;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalPets' => Pet::count(),
            'totalReminders' => Reminder::count(),
            'totalHealthChecks' => HealthCheck::count(),
            'totalAppointments' => Appointment::count(),
            'pendingAppointments' => Appointment::pending()->count(),
            'confirmedAppointments' => Appointment::confirmed()->count(),
            'completedAppointments' => Appointment::completed()->count(),
            'newUsersThisWeek' => User::where('created_at', '>=', now()->subWeek())->count(),
            'pendingReminders' => Reminder::pending()->count(),
        ];

        // Pet categories stats
        $totalPets = Pet::count();
        $dogCount = Pet::where('species', 'Dog')->count();
        $catCount = Pet::where('species', 'Cat')->count();
        $birdCount = Pet::where('species', 'Bird')->count();
        $otherCount = $totalPets - $dogCount - $catCount - $birdCount;

        $petCategories = [
            [
                'name' => 'Dogs',
                'count' => $dogCount,
                'percentage' => $totalPets > 0 ? round(($dogCount / $totalPets) * 100, 1) : 0,
                'emoji' => '🐕',
                'color' => 'from-[#FFD4B2] to-[#FFA07A]'
            ],
            [
                'name' => 'Cats',
                'count' => $catCount,
                'percentage' => $totalPets > 0 ? round(($catCount / $totalPets) * 100, 1) : 0,
                'emoji' => '🐈',
                'color' => 'from-[#C4B5FD] to-[#A78BFA]'
            ],
            [
                'name' => 'Birds',
                'count' => $birdCount,
                'percentage' => $totalPets > 0 ? round(($birdCount / $totalPets) * 100, 1) : 0,
                'emoji' => '🐦',
                'color' => 'from-[#86EFAC] to-[#4ADE80]'
            ],
            [
                'name' => 'Others',
                'count' => $otherCount,
                'percentage' => $totalPets > 0 ? round(($otherCount / $totalPets) * 100, 1) : 0,
                'emoji' => '🐾',
                'color' => 'from-[#68C4CF] to-[#5AB0BB]'
            ],
        ];
        
        $stats = array_merge($stats, compact('petCategories'));

        $recentActivities = collect();

        // Get recent appointments
        $recentAppointments = Appointment::with(['user', 'pet'])->latest()->take(5)->get()->map(function ($appointment) {
            return [
                'type' => 'appointment',
                'user' => $appointment->user->name ?? 'Unknown',
                'action' => 'appointment ' . $appointment->status,
                'description' => $appointment->pet->pet_name ?? 'Unknown Pet',
                'date' => $appointment->created_at,
            ];
        });
        $recentActivities = $recentActivities->merge($recentAppointments);

        // Get recent users
        $recentUsers = User::latest()->take(5)->get()->map(function ($user) {
            return [
                'type' => 'user',
                'user' => $user->name,
                'action' => 'registered',
                'description' => 'New user registered',
                'date' => $user->created_at,
            ];
        });
        $recentActivities = $recentActivities->merge($recentUsers);

        // Get recent pets
        $recentPets = Pet::with('user')->latest()->take(5)->get()->map(function ($pet) {
            return [
                'type' => 'pet',
                'user' => $pet->user->name ?? 'Unknown',
                'action' => 'added pet',
                'description' => $pet->pet_name,
                'date' => $pet->created_at,
            ];
        });
        $recentActivities = $recentActivities->merge($recentPets);

        // Sort by date
        $recentActivities = $recentActivities->sortByDesc('date')->take(10);

        return view('pages.admin.dashboard', compact('stats', 'recentActivities'));
    }

    /**
     * Export data to CSV.
     */
    public function export()
    {
        $filename = 'paw-time-export-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Users section
            fputcsv($file, ['USERS']);
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Created At']);
            foreach (User::all() as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fputcsv($file, []);

            // Pets section
            fputcsv($file, ['PETS']);
            fputcsv($file, ['ID', 'Name', 'Type', 'Breed', 'Owner', 'Created At']);
            foreach (Pet::with('user')->get() as $pet) {
                fputcsv($file, [
                    $pet->id,
                    $pet->pet_name,
                    $pet->pet_type,
                    $pet->pet_breed,
                    $pet->user->name ?? 'Unknown',
                    $pet->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fputcsv($file, []);

            // Appointments section
            fputcsv($file, ['APPOINTMENTS']);
            fputcsv($file, ['ID', 'User', 'Pet', 'Date', 'Status', 'Created At']);
            foreach (Appointment::with(['user', 'pet'])->get() as $appointment) {
                fputcsv($file, [
                    $appointment->id,
                    $appointment->user->name ?? 'Unknown',
                    $appointment->pet->pet_name ?? 'Unknown',
                    $appointment->appointment_date->format('Y-m-d H:i:s'),
                    $appointment->status,
                    $appointment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
