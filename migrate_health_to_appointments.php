<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;
use App\Models\HealthCheck;

echo "=== Migrate Health Checks to Appointments ===\n\n";

$healthChecks = HealthCheck::with(['pet.user'])->whereNull('appointment_id')->get();

echo "Found {$healthChecks->count()} health checks without appointments\n\n";

$created = 0;
$linked = 0;

foreach ($healthChecks as $hc) {
    if (!$hc->pet || !$hc->pet->user) {
        echo "⚠️  Skip HC #{$hc->id} - No pet/user\n";
        continue;
    }

    // Create appointment from health check
    $appointment = Appointment::create([
        'user_id' => $hc->pet->user_id,
        'pet_id' => $hc->pet_id,
        'appointment_date' => $hc->check_date,
        'status' => 'completed',
        'notes' => $hc->complaint ?? 'Migrated from health check',
        'veterinarian_notes' => $hc->diagnosis . "\n\nTreatment: " . $hc->treatment,
    ]);

    // Link health check to appointment
    $hc->update(['appointment_id' => $appointment->id]);

    echo "✅ Created appointment #{$appointment->id} from HC #{$hc->id} ({$hc->pet->pet_name})\n";
    $created++;
    $linked++;
}

echo "\n=== Summary ===\n";
echo "Appointments Created: {$created}\n";
echo "Health Checks Linked: {$linked}\n";
echo "Total Appointments Now: " . Appointment::count() . "\n";
