<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Models\Pet;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * Display a listing of reminders.
     */
    public function index(Request $request)
    {
        $query = Reminder::with('user');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filter by category
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $reminders = $query->orderBy('remind_date', 'desc')->paginate(15);

        $stats = [
            'total' => Reminder::count(),
            'pending' => Reminder::pending()->count(),
            'done' => Reminder::done()->count(),
            'overdue' => Reminder::pending()->where('remind_date', '<', now())->count(),
        ];

        return view('pages.admin.reminders.index', compact('reminders', 'stats'));
    }

    /**
     * Display the specified reminder.
     */
    public function show(Reminder $reminder)
    {
        $reminder->load('user');
        return view('pages.admin.reminders.show', compact('reminder'));
    }

    /**
     * Show the form for creating a new reminder.
     */
    public function create()
    {
        $users = \App\Models\User::where('role', 'user')->orderBy('name')->get();
        return view('pages.admin.reminders.create', compact('users'));
    }

    /**
     * Store a newly created reminder.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'remind_date' => 'required|date_format:Y-m-d\TH:i',
            'category' => 'required|in:vaccination,grooming,feeding,medication,checkup,other',
            'repeat_type' => 'nullable|in:none,daily,weekly,monthly,yearly',
            'status' => 'required|in:pending,done,skipped',
        ]);

        Reminder::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'remind_date' => str_replace('T', ' ', $validated['remind_date']),
            'category' => $validated['category'],
            'repeat_type' => $validated['repeat_type'] ?? 'none',
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.reminders.index')
            ->with('success', 'Reminder created successfully!');
    }

    /**
     * Show the form for editing the specified reminder.
     */
    public function edit(Reminder $reminder)
    {
        $reminder->load('user');
        $users = \App\Models\User::where('role', 'user')->orderBy('name')->get();

        return view('pages.admin.reminders.edit', compact('reminder', 'users'));
    }

    /**
     * Update the specified reminder.
     */
    public function update(Request $request, Reminder $reminder)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'remind_date' => 'required|date_format:Y-m-d\TH:i',
            'category' => 'required|in:vaccination,grooming,feeding,medication,checkup,other',
            'repeat_type' => 'nullable|in:none,daily,weekly,monthly,yearly',
            'status' => 'required|in:pending,done,skipped',
        ]);

        $reminder->update([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'remind_date' => str_replace('T', ' ', $validated['remind_date']),
            'category' => $validated['category'],
            'repeat_type' => $validated['repeat_type'] ?? 'none',
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.reminders.index')
            ->with('success', 'Reminder updated successfully!');
    }

    /**
     * Remove the specified reminder.
     */
    public function destroy(Reminder $reminder)
    {
        $reminder->delete();

        return redirect()->route('admin.reminders.index')
            ->with('success', 'Reminder deleted successfully!');
    }
}
