#!/usr/bin/env php
<?php

echo "Testing Reminder API after migration\n";
echo "=====================================\n\n";

// Test 1: Check database structure
echo "1. Checking reminders table structure...\n";
$output = shell_exec('php artisan tinker --execute="DB::select(\'SHOW COLUMNS FROM reminders\');"');
echo $output . "\n";

// Test 2: Check if we can create a reminder
echo "\n2. Testing reminder creation for user...\n";
$code = <<<'PHP'
$user = App\Models\User::where('role', 'user')->first();
if ($user) {
    try {
        $reminder = $user->reminders()->create([
            'title' => 'Test Reminder - Buy pet food',
            'description' => 'General reminder for all pets',
            'remind_date' => now()->addDays(1),
            'category' => 'feeding',
            'repeat_type' => 'daily',
            'status' => 'pending',
        ]);
        echo "✓ Reminder created successfully!\n";
        echo "  ID: " . $reminder->id . "\n";
        echo "  User: " . $user->name . "\n";
        echo "  Title: " . $reminder->title . "\n";
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ No user found\n";
}
PHP;

$output = shell_exec('php artisan tinker --execute="' . addslashes($code) . '"');
echo $output . "\n";

// Test 3: Check API endpoint
echo "\n3. Testing API endpoint...\n";
echo "   GET /api/reminders/filters\n";
$ch = curl_init('http://127.0.0.1:8000/api/reminders/filters');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   Status: " . $httpCode . "\n";
if ($httpCode == 401) {
    echo "   ✓ API requires authentication (expected)\n";
} elseif ($httpCode == 200) {
    echo "   Response: " . $response . "\n";
}

echo "\n=====================================\n";
echo "Test completed!\n";
