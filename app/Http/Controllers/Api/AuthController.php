<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Detect device type from User-Agent.
     */
    private function getDeviceType(Request $request): string
    {
        $userAgent = strtolower($request->header('User-Agent', ''));

        if (str_contains($userAgent, 'android')) {
            return 'android';
        } elseif (str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ipad')) {
            return 'ios';
        }

        return 'web';
    }

    /**
     * Register a new user.
     *
     * POST /api/auth/register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'fcm_token' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Save FCM token if provided
        if (!empty($validated['fcm_token'])) {
            $deviceType = $this->getDeviceType($request);
            $user->fcmTokens()->updateOrCreate(
                ['token' => $validated['fcm_token']],
                ['device_type' => $deviceType, 'is_active' => true]
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar_url' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
                'fcm_token' => $validated['fcm_token'] ?? null,
            ]
        ], 201);
    }

    /**
     * Login user and create token.
     *
     * POST /api/auth/login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'fcm_token' => 'nullable|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Save FCM token if provided
        if (!empty($validated['fcm_token'])) {
            $deviceType = $this->getDeviceType($request);
            $user->fcmTokens()->updateOrCreate(
                ['token' => $validated['fcm_token']],
                ['device_type' => $deviceType, 'is_active' => true]
            );
        }

        // Revoke previous tokens (optional - for single device login)
        // $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ?? 'user',
                    'avatar_url' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
                'fcm_token' => $validated['fcm_token'] ?? null,
            ]
        ]);
    }

    /**
     * Logout user (revoke token).
     *
     * POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Logout from all devices (revoke all tokens).
     *
     * POST /api/auth/logout-all
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices',
        ]);
    }

    /**
     * Get authenticated user profile.
     *
     * GET /api/auth/user
     */
    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user',
                'avatar_url' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                'created_at' => $user->created_at,
            ]
        ]);
    }

    /**
     * Update user profile.
     *
     * PUT /api/auth/user
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * Change user password.
     *
     * PUT /api/auth/password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * Upload user profile photo.
     *
     * POST /api/auth/avatar
     */
    public function uploadAvatar(Request $request)
    {
        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('profiles', 'public');
        $user->update(['profile_image' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar uploaded successfully',
            'data' => [
                'avatar_url' => asset('storage/' . $path),
            ]
        ]);
    }

    /**
     * Remove user profile photo.
     *
     * DELETE /api/auth/avatar
     */
    public function removeAvatar(Request $request)
    {
        $user = $request->user();

        if (!$user->profile_image) {
            return response()->json([
                'success' => false,
                'message' => 'No avatar to remove',
            ], 400);
        }

        // Delete avatar from storage
        Storage::disk('public')->delete($user->profile_image);

        $user->update(['profile_image' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar removed successfully',
        ]);
    }
}
