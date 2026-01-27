<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Register or update FCM token for the authenticated user.
     */
    public function registerToken(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'device_type' => 'required|in:web,android,ios',
            'device_name' => 'nullable|string|max:100',
        ]);

        $user = $request->user();

        // Check if token already exists
        $existingToken = FcmToken::where('token', $validated['token'])->first();

        if ($existingToken) {
            // Update existing token
            $existingToken->update([
                'user_id' => $user->id,
                'device_type' => $validated['device_type'],
                'device_name' => $validated['device_name'] ?? null,
                'is_active' => true,
                'last_used_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'FCM token updated successfully',
                'token_id' => $existingToken->id,
            ]);
        }

        // Create new token
        $fcmToken = $user->fcmTokens()->create([
            'token' => $validated['token'],
            'device_type' => $validated['device_type'],
            'device_name' => $validated['device_name'] ?? null,
            'is_active' => true,
            'last_used_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FCM token registered successfully',
            'token_id' => $fcmToken->id,
        ], 201);
    }

    /**
     * Remove FCM token (logout/unregister).
     */
    public function removeToken(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
        ]);

        $deleted = FcmToken::where('token', $validated['token'])
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json([
            'success' => $deleted > 0,
            'message' => $deleted > 0 ? 'Token removed successfully' : 'Token not found',
        ]);
    }

    /**
     * Get user's notification history.
     */
    public function history(Request $request)
    {
        $notifications = $request->user()
            ->notificationLogs()
            ->with('reminder.pet')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Send test notification to the authenticated user.
     */
    public function sendTest(Request $request, FirebaseService $firebase)
    {
        $user = $request->user();

        $result = $firebase->sendAndLog(
            $user,
            '🐾 Test Notification',
            'Selamat! Notifikasi Paw Time berhasil dikonfigurasi.',
            ['type' => 'test']
        );

        return response()->json([
            'success' => $result->status === 'sent',
            'message' => $result->status === 'sent'
                ? 'Test notification sent successfully'
                : 'Failed to send test notification',
            'log_id' => $result->id,
        ]);
    }
}
