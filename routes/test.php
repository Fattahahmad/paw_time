<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Reminder;

Route::get('/test-reminder', function() {
    // Get first user
    $user = User::where('role', 'user')->first();
    
    if (!$user) {
        return response()->json(['error' => 'No user found'], 404);
    }
    
    // Create test reminder
    $reminder = $user->reminders()->create([
        'title' => 'Test General Reminder',
        'description' => 'This is a test reminder for all pets',
        'remind_date' => now()->addDay(),
        'category' => 'feeding',
        'repeat_type' => 'daily',
        'status' => 'pending',
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Reminder created successfully!',
        'data' => [
            'reminder_id' => $reminder->id,
            'user' => $user->name,
            'title' => $reminder->title,
            'category' => $reminder->category,
            'remind_date' => $reminder->remind_date,
        ]
    ]);
})->name('test.reminder');
