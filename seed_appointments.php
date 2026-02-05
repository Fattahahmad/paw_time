<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;
use App\Models\User;
use App\Models\Pet;

echo "=== Creating Sample Appointments ===\n\n";

// Get users and pets
$users = User::where('role', 'user')->get();
$pets = Pet::all();

if ($users->isEmpty() || $pets->isEmpty()) {
    echo "❌ No users or pets found!\n";
    exit;
}

// Clear existing appointments
Appointment::truncate();
echo "Cleared existing appointments.\n\n";

// Create sample appointments
$appointments = [
    [
        'user_id' => $users[0]->id,
        'pet_id' => $pets[0]->id,
        'appointment_date' => now()->addDays(3)->setTime(10, 0),
        'status' => 'pending',
        'notes' => 'Regular checkup and vaccination',
    ],
    [
        'user_id' => $users[0]->id,
        'pet_id' => $pets->count() > 1 ? $pets[1]->id : $pets[0]->id,
        'appointment_date' => now()->addDays(5)->setTime(14, 30),
        'status' => 'confirmed',
        'notes' => 'Follow-up visit for vaccination',
    ],
    [
        'user_id' => $users->count() > 1 ? $users[1]->id : $users[0]->id,
        'pet_id' => $pets->count() > 2 ? $pets[2]->id : $pets[0]->id,
        'appointment_date' => now()->addDays(7)->setTime(11, 0),
        'status' => 'pending',
        'notes' => 'Grooming and health check',
    ],
    [
        'user_id' => $users[0]->id,
        'pet_id' => $pets[0]->id,
        'appointment_date' => now()->subDays(5)->setTime(9, 30),
        'status' => 'completed',
        'notes' => 'Annual health check',
        'veterinarian_notes' => 'All vitals normal. Recommended diet adjustment.',
    ],
    [
        'user_id' => $users->count() > 1 ? $users[1]->id : $users[0]->id,
        'pet_id' => $pets->count() > 1 ? $pets[1]->id : $pets[0]->id,
        'appointment_date' => now()->addDays(2)->setTime(15, 0),
        'status' => 'confirmed',
        'notes' => 'Dental cleaning',
    ],
];

foreach ($appointments as $data) {
    $appointment = Appointment::create($data);
    $user = User::find($data['user_id']);
    $pet = Pet::find($data['pet_id']);
    echo "✅ Created: {$user->name} - {$pet->pet_name} - {$data['status']}\n";
}

echo "\n=== Summary ===\n";
echo "Total Appointments: " . Appointment::count() . "\n";
echo "Pending: " . Appointment::where('status', 'pending')->count() . "\n";
echo "Confirmed: " . Appointment::where('status', 'confirmed')->count() . "\n";
echo "Completed: " . Appointment::where('status', 'completed')->count() . "\n";
echo "Cancelled: " . Appointment::where('status', 'cancelled')->count() . "\n";
