<?php

namespace App\Services;

use App\Models\FcmToken;
use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Client as GoogleClient;

class FirebaseService
{
    private string $projectId;
    private ?string $accessToken = null;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id');
    }

    /**
     * Get OAuth2 access token for FCM v1 API.
     */
    private function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $credentialsPath = storage_path('app/firebase-credentials.json');

        if (!file_exists($credentialsPath)) {
            throw new \Exception('Firebase credentials file not found at: ' . $credentialsPath);
        }

        $client = new GoogleClient();
        $client->setAuthConfig($credentialsPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();
        $this->accessToken = $token['access_token'];

        return $this->accessToken;
    }

    /**
     * Send notification to a single device.
     */
    public function sendToDevice(string $fcmToken, string $title, string $body, array $data = []): array
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $message = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => array_map('strval', $data), // FCM requires string values
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'paw_time_reminders',
                    ],
                ],
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($url, $message);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('name'),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error.message', 'Unknown error'),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('FCM Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send notification to a user (all their devices).
     */
    public function sendToUser(User $user, string $title, string $body, array $data = []): array
    {
        $tokens = $user->fcmTokens()->active()->get();

        if ($tokens->isEmpty()) {
            return [
                'success' => false,
                'error' => 'No active FCM tokens for user',
            ];
        }

        $results = [];
        foreach ($tokens as $token) {
            $result = $this->sendToDevice($token->token, $title, $body, $data);
            $results[] = [
                'token_id' => $token->id,
                'device_type' => $token->device_type,
                ...$result,
            ];

            // If token is invalid, deactivate it
            if (!$result['success'] && isset($result['status']) && $result['status'] === 404) {
                $token->deactivate();
            } else {
                $token->markAsUsed();
            }
        }

        return [
            'success' => collect($results)->where('success', true)->isNotEmpty(),
            'results' => $results,
        ];
    }

    /**
     * Send notification and log it.
     */
    public function sendAndLog(
        User $user,
        string $title,
        string $body,
        array $data = [],
        ?int $reminderId = null
    ): NotificationLog {
        // Create log entry
        $log = NotificationLog::create([
            'user_id' => $user->id,
            'reminder_id' => $reminderId,
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'status' => 'pending',
            'scheduled_at' => now(),
        ]);

        // Send notification
        $result = $this->sendToUser($user, $title, $body, $data);

        // Update log based on result
        if ($result['success']) {
            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
                'data' => array_merge($data, ['results' => $result['results']]),
            ]);
        } else {
            $log->update([
                'status' => 'failed',
                'error_message' => $result['error'] ?? 'Unknown error',
                'data' => array_merge($data, ['results' => $result['results'] ?? []]),
            ]);
        }

        return $log->fresh();
    }
}
