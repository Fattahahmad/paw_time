<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Services\FirebaseService;

echo "=== SEND TEST NOTIFICATION ===\n\n";

// Get user with active tokens
$user = User::find(4); // fattah ahmad
if (!$user) {
    echo "User not found\n";
    exit(1);
}

echo "Sending to: {$user->name} (ID: {$user->id})\n";
echo "Email: {$user->email}\n";
echo "Active tokens: " . $user->fcmTokens()->active()->count() . "\n\n";

try {
    $service = app(FirebaseService::class);
    
    echo "Calling sendAndLog()...\n";
    $log = $service->sendAndLog(
        $user,
        '🚀 Test Notification ' . date('H:i:s'),
        'Ini test notifikasi dari script PHP. Kalau masuk berarti berhasil!',
        [
            'type' => 'test',
            'test_id' => uniqid(),
            'timestamp' => time(),
        ]
    );

    echo "\n✅ FUNCTION EXECUTED\n";
    echo "Log ID: {$log->id}\n";
    echo "Status: {$log->status}\n";
    echo "Sent at: {$log->sent_at}\n";
    
    if ($log->error_message) {
        echo "Error: {$log->error_message}\n";
    }
    
    echo "\n--- Results Detail ---\n";
    if (isset($log->data['results'])) {
        foreach ($log->data['results'] as $idx => $result) {
            echo "\nToken #{$idx}:\n";
            echo "  Token ID: {$result['token_id']}\n";
            echo "  Device: {$result['device_type']}\n";
            echo "  Success: " . ($result['success'] ? 'YES ✅' : 'NO ❌') . "\n";
            
            if ($result['success']) {
                echo "  Message ID: {$result['message_id']}\n";
            } else {
                echo "  Error: {$result['error']}\n";
                if (isset($result['status'])) {
                    echo "  HTTP Status: {$result['status']}\n";
                }
            }
        }
    } else {
        echo "No results data\n";
        print_r($log->data);
    }
    
} catch (\Exception $e) {
    echo "\n❌ EXCEPTION\n";
    echo "Message: {$e->getMessage()}\n";
    echo "File: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n\n=== CHECK DATABASE ===\n";
$latestLogs = App\Models\NotificationLog::latest()->take(3)->get();
echo "Latest 3 notification logs:\n";
foreach ($latestLogs as $l) {
    echo "  ID: {$l->id} | {$l->title} | {$l->status} | {$l->created_at}\n";
}
