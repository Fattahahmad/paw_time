<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * Display the reminders page.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $reminders = Reminder::where('user_id', $user->id)
            ->orderBy('remind_date', 'asc')
            ->get();

        $pendingReminders = $reminders->where('status', 'pending');
        $doneReminders = $reminders->where('status', 'done');

        return view('pages.user.reminder', compact('reminders', 'pendingReminders', 'doneReminders'));
    }

    /**
     * Store a newly created reminder.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'remind_date' => 'required|date',
            'remind_time' => 'required|date_format:H:i',
            'category' => 'required|in:vaccination,grooming,feeding,medication,checkup,other',
            'repeat_type' => 'nullable|in:none,daily,weekly,monthly,yearly',
        ]);

        $reminder = $request->user()->reminders()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'remind_date' => $validated['remind_date'] . ' ' . $validated['remind_time'],
            'category' => $validated['category'],
            'repeat_type' => $validated['repeat_type'] ?? 'none',
            'status' => 'pending',
        ]);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reminder added successfully!',
                'reminder' => $reminder,
            ]);
        }

        return redirect()->route('user.reminder')
            ->with('success', 'Reminder added successfully!');
    }

    /**
     * Update the specified reminder.
     */
    public function update(Request $request, Reminder $reminder)
    {
        // Verify user owns this reminder
        if ($reminder->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'remind_date' => 'required|date',
            'remind_time' => 'required|date_format:H:i',
            'category' => 'required|in:vaccination,grooming,feeding,medication,checkup,other',
            'repeat_type' => 'nullable|in:none,daily,weekly,monthly,yearly',
        ]);

        $reminder->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'remind_date' => $validated['remind_date'] . ' ' . $validated['remind_time'],
            'category' => $validated['category'],
            'repeat_type' => $validated['repeat_type'] ?? 'none',
        ]);

        return redirect()->back()->with('success', 'Reminder updated successfully!');
    }

    /**
     * Mark reminder as done.
     */
    public function markDone(Request $request, Reminder $reminder)
    {
        // Verify user owns this reminder
        if ($reminder->user_id !== $request->user()->id) {
            abort(403);
        }

        $reminder->markAsDone();

        return redirect()->back()->with('success', 'Reminder marked as done!');
    }

    /**
     * Remove the specified reminder.
     */
    public function destroy(Request $request, Reminder $reminder)
    {
        // Verify user owns this reminder
        if ($reminder->user_id !== $request->user()->id) {
            abort(403);
        }

        $reminder->delete();

        return redirect()->back()->with('success', 'Reminder deleted successfully!');
    }
}
