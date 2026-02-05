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
            $result = $this->firebaseService->sendToUser(
                $user,
                $validated['title'],
                $validated['message'],
                [
                    'type' => $validated['type'] ?? 'test',
                    'test' => true,
                ]
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => "Notifikasi berhasil dikirim ke {$user->name}",
                    'details' => $result,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim notifikasi: ' . ($result['error'] ?? 'Unknown error'),
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
                $result = $this->firebaseService->sendToUser(
                    $user,
                    $validated['title'],
                    $validated['message'],
                    ['type' => 'broadcast', 'broadcast' => true]
                );

                if ($result['success']) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                }

                $results['details'][] = [
                    'user' => $user->name,
                    'status' => $result['success'] ? 'sent' : 'failed',
                ];
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
