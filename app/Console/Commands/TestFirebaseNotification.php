<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Console\Command;

class TestFirebaseNotification extends Command
{
    protected $signature = 'firebase:test {--user= : User ID to test} {--token= : FCM token to test directly}';
    protected $description = 'Test Firebase notification sending';

    public function handle(FirebaseService $firebase)
    {
        $this->info('Testing Firebase Notification...');
        $this->newLine();

        // Check credentials file
        $credentialsPath = storage_path('app/firebase-credentials.json');
        if (!file_exists($credentialsPath)) {
            $this->error("❌ Firebase credentials file not found at: {$credentialsPath}");
            return Command::FAILURE;
        }
        $this->info("✓ Firebase credentials file found");

        // Check project ID
        $projectId = config('services.firebase.project_id');
        if (empty($projectId)) {
            $this->error("❌ FIREBASE_PROJECT_ID not set in .env");
            return Command::FAILURE;
        }
        $this->info("✓ Project ID: {$projectId}");

        // If token provided, test directly
        if ($token = $this->option('token')) {
            $this->info("Testing with provided FCM token...");
            $result = $firebase->sendToDevice(
                $token,
                '🐾 Test Notification',
                'Selamat! Firebase notification berhasil dikonfigurasi.',
                ['type' => 'test', 'timestamp' => now()->toISOString()]
            );

            if ($result['success']) {
                $this->info("✓ Notification sent successfully!");
                $this->info("  Message ID: " . ($result['message_id'] ?? 'N/A'));
            } else {
                $this->error("❌ Failed to send notification");
                $this->error("  Error: " . ($result['error'] ?? 'Unknown'));
            }
            return Command::SUCCESS;
        }

        // If user ID provided, test with user's tokens
        if ($userId = $this->option('user')) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("❌ User not found with ID: {$userId}");
                return Command::FAILURE;
            }

            $tokens = $user->fcmTokens()->active()->get();
            if ($tokens->isEmpty()) {
                $this->warn("⚠ User {$user->name} has no active FCM tokens");
                $this->info("Register a token first using POST /api/notifications/register");
                return Command::FAILURE;
            }

            $this->info("Sending test notification to {$user->name} ({$tokens->count()} devices)...");

            $result = $firebase->sendAndLog(
                $user,
                '🐾 Test Notification',
                'Selamat! Firebase notification berhasil dikonfigurasi untuk ' . $user->name,
                ['type' => 'test']
            );

            if ($result->status === 'sent') {
                $this->info("✓ Notification sent successfully!");
            } else {
                $this->error("❌ Failed: " . $result->error_message);

                // Show detailed error from results
                if ($result->data && isset($result->data['results'])) {
                    $this->newLine();
                    $this->error("Detailed errors:");
                    foreach ($result->data['results'] as $deviceResult) {
                        $device = $deviceResult['device_type'] ?? 'unknown';
                        $error = $deviceResult['error'] ?? 'Unknown error';
                        $status = $deviceResult['status'] ?? 'N/A';
                        $this->line("  - Device ({$device}): {$error} [Status: {$status}]");
                    }
                }
            }
            return Command::SUCCESS;
        }

        // Show instructions
        $this->newLine();
        $this->info("Firebase is configured. To test notifications:");
        $this->newLine();
        $this->line("  1. Test with FCM token directly:");
        $this->line("     php artisan firebase:test --token=YOUR_FCM_TOKEN");
        $this->newLine();
        $this->line("  2. Test with user (requires registered FCM token):");
        $this->line("     php artisan firebase:test --user=1");
        $this->newLine();
        $this->line("  3. Get FCM token from browser console (after enabling notifications)");
        $this->newLine();

        // Show registered tokens
        $totalTokens = \App\Models\FcmToken::count();
        $this->info("📊 Database Status:");
        $this->line("   Total FCM Tokens: {$totalTokens}");

        if ($totalTokens > 0) {
            $this->newLine();
            $this->info("Registered Tokens:");
            \App\Models\FcmToken::with('user')->get()->each(function($token) {
                $this->line("   - User: {$token->user->name}, Device: {$token->device_type}, Active: " . ($token->is_active ? 'Yes' : 'No'));
            });
        }

        return Command::SUCCESS;
    }
}
