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
            Log::error('Firebase credentials file not found', ['path' => $credentialsPath]);
            throw new \Exception('Firebase credentials file not found at: ' . $credentialsPath);
        }

        try {
            $client = new GoogleClient();
            $client->setAuthConfig($credentialsPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

            $token = $client->fetchAccessTokenWithAssertion();
            
            if (!isset($token['access_token'])) {
                Log::error('Failed to get Firebase access token', ['token_response' => $token]);
                throw new \Exception('Failed to get access token from Firebase');
            }
            
            $this->accessToken = $token['access_token'];
            return $this->accessToken;
        } catch (\Exception $e) {
            Log::error('Firebase getAccessToken error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
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
            $accessToken = $this->getAccessToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $message);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('name'),
                ];
            }

            // Get full error details
            $errorData = $response->json('error');
            $errorMessage = $errorData['message'] ?? 'Unknown error';
            $errorCode = $errorData['code'] ?? null;
            $errorStatus = $errorData['status'] ?? null;
            
            Log::warning('FCM send failed', [
                'status' => $response->status(),
                'error_message' => $errorMessage,
                'error_code' => $errorCode,
                'error_status' => $errorStatus,
                'full_response' => $response->body(),
                'token_preview' => substr($fcmToken, 0, 20) . '...'
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'status' => $response->status(),
                'error_code' => $errorCode,
            ];
        } catch (\Exception $e) {
            Log::error('FCM Send Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
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
            Log::info('No active FCM tokens', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);
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

            // If token is invalid (400 or 404), deactivate it
            if (!$result['success'] && isset($result['status']) && in_array($result['status'], [400, 404])) {
                $token->deactivate();
                Log::info("FCM Token {$token->id} deactivated - status {$result['status']}");
            } elseif ($result['success']) {
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
