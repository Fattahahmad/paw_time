<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class NotificationTestController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Show notification test page.
     */
    public function index()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('pages.admin.notification-test', compact('users'));
    }

    /**
     * Send test notification.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:500',
            'type' => 'nullable|string',
        ]);

        $user = User::findOrFail($validated['user_id']);

        try {
            // Use sendAndLog to save notification log
            $log = $this->firebaseService->sendAndLog(
                $user,
                $validated['title'],
                $validated['message'],
                [
                    'type' => $validated['type'] ?? 'test',
                    'test' => true,
                ]
            );

            if ($log->status === 'sent') {
                return response()->json([
                    'success' => true,
                    'message' => "Notifikasi berhasil dikirim ke {$user->name}",
                    'details' => $log->data,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim notifikasi: ' . ($log->error_message ?? 'Unknown error'),
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send broadcast notification to all users.
     */
    public function broadcast(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:500',
        ]);

        $users = User::where('role', 'user')->get();
        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => [],
        ];

        foreach ($users as $user) {
            try {
                // Use sendAndLog to save each notification
                $log = $this->firebaseService->sendAndLog(
                    $user,
                    $validated['title'],
                    $validated['message'],
                    ['type' => 'broadcast', 'broadcast' => true]
                );

                if ($log->status === 'sent') {
                    $results['success']++;
                    $results['details'][] = [
                        'user' => $user->name,
                        'status' => 'sent',
                        'log_id' => $log->id,
                    ];
                } else {
                    $results['failed']++;
                    $results['details'][] = [
                        'user' => $user->name,
                        'status' => 'failed',
                        'error' => $log->error_message,
                    ];
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['details'][] = [
                    'user' => $user->name,
                    'status' => 'error',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Broadcast selesai: {$results['success']} berhasil, {$results['failed']} gagal",
            'results' => $results,
        ]);
    }
}
