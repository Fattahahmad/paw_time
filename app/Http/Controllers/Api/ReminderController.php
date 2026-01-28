<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * Get all reminders for authenticated user.
     *
     * GET /api/reminders
     * Query params: pet_id, category, repeat_type (schedule), status, date, date_from, date_to
     */
    public function index(Request $request)
    {
        $query = Reminder::whereHas('pet', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('pet:id,pet_name,species,image_url');

        // Filter by pet
        if ($request->has('pet_id')) {
            $query->where('pet_id', $request->pet_id);
        }

        // Filter by category (feeding, grooming, vaccination, medication, checkup, other)
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by schedule/repeat_type (none, daily, weekly, monthly, yearly)
        if ($request->has('repeat_type')) {
            $query->where('repeat_type', $request->repeat_type);
        }

        // Alias: schedule = repeat_type
        if ($request->has('schedule')) {
            $query->where('repeat_type', $request->schedule);
        }

        // Filter by status (pending, done, skipped)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by title or description
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by specific date
        if ($request->has('date')) {
            $query->whereDate('remind_date', $request->date);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('remind_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('remind_date', '<=', $request->date_to);
        }

        // Filter upcoming (today and future)
        if ($request->boolean('upcoming')) {
            $query->whereDate('remind_date', '>=', now()->toDateString())
                  ->where('status', 'pending');
        }

        // Filter today only
        if ($request->boolean('today')) {
            $query->whereDate('remind_date', now()->toDateString());
        }

        // Order
        $orderBy = $request->get('order_by', 'remind_date');
        $orderDir = $request->get('order_dir', 'asc');
        $query->orderBy($orderBy, $orderDir);

        // Pagination
        $perPage = $request->get('per_page', 20);
        if ($request->boolean('all')) {
            $reminders = $query->get();
        } else {
            $reminders = $query->paginate($perPage);
        }

        $formatReminder = function ($reminder) {
            return $this->formatReminder($reminder);
        };

        if ($request->boolean('all')) {
            return response()->json([
                'success' => true,
                'data' => $reminders->map($formatReminder),
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $reminders->getCollection()->map($formatReminder),
            'meta' => [
                'current_page' => $reminders->currentPage(),
                'last_page' => $reminders->lastPage(),
                'per_page' => $reminders->perPage(),
                'total' => $reminders->total(),
            ]
        ]);
    }

    /**
     * Get a specific reminder.
     *
     * GET /api/reminders/{reminder}
     */
    public function show(Request $request, Reminder $reminder)
    {
        // Verify ownership
        if ($reminder->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Reminder not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatReminder($reminder->load('pet')),
        ]);
    }

    /**
     * Store a new reminder.
     *
     * POST /api/reminders
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'remind_date' => 'required|date',
            'remind_time' => 'required|date_format:H:i',
            'category' => 'required|in:vaccination,grooming,feeding,medication,checkup,other',
            'repeat_type' => 'nullable|in:none,daily,weekly,monthly,yearly',
        ]);

        // Verify user owns this pet
        $pet = $request->user()->pets()->find($validated['pet_id']);
        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found',
            ], 404);
        }

        $reminder = $pet->reminders()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'remind_date' => $validated['remind_date'] . ' ' . $validated['remind_time'],
            'category' => $validated['category'],
            'repeat_type' => $validated['repeat_type'] ?? 'none',
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reminder created successfully',
            'data' => $this->formatReminder($reminder->load('pet')),
        ], 201);
    }

    /**
     * Update a reminder.
     *
     * PUT /api/reminders/{reminder}
     */
    public function update(Request $request, Reminder $reminder)
    {
        // Verify ownership
        if ($reminder->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Reminder not found',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'remind_date' => 'sometimes|date',
            'remind_time' => 'sometimes|date_format:H:i',
            'category' => 'sometimes|in:vaccination,grooming,feeding,medication,checkup,other',
            'repeat_type' => 'nullable|in:none,daily,weekly,monthly,yearly',
            'status' => 'sometimes|in:pending,done,skipped',
        ]);

        // Build update data
        $updateData = [];

        if (isset($validated['title'])) {
            $updateData['title'] = $validated['title'];
        }
        if (array_key_exists('description', $validated)) {
            $updateData['description'] = $validated['description'];
        }
        if (isset($validated['remind_date']) || isset($validated['remind_time'])) {
            $date = $validated['remind_date'] ?? $reminder->remind_date->format('Y-m-d');
            $time = $validated['remind_time'] ?? $reminder->remind_date->format('H:i');
            $updateData['remind_date'] = $date . ' ' . $time;
        }
        if (isset($validated['category'])) {
            $updateData['category'] = $validated['category'];
        }
        if (array_key_exists('repeat_type', $validated)) {
            $updateData['repeat_type'] = $validated['repeat_type'] ?? 'none';
        }
        if (isset($validated['status'])) {
            $updateData['status'] = $validated['status'];
        }

        $reminder->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Reminder updated successfully',
            'data' => $this->formatReminder($reminder->fresh()->load('pet')),
        ]);
    }

    /**
     * Mark reminder as done.
     *
     * PATCH /api/reminders/{reminder}/done
     */
    public function markDone(Request $request, Reminder $reminder)
    {
        // Verify ownership
        if ($reminder->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Reminder not found',
            ], 404);
        }

        $reminder->update(['status' => 'done']);

        return response()->json([
            'success' => true,
            'message' => 'Reminder marked as done',
            'data' => $this->formatReminder($reminder->fresh()->load('pet')),
        ]);
    }

    /**
     * Mark reminder as skipped.
     *
     * PATCH /api/reminders/{reminder}/skip
     */
    public function markSkipped(Request $request, Reminder $reminder)
    {
        // Verify ownership
        if ($reminder->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Reminder not found',
            ], 404);
        }

        $reminder->update(['status' => 'skipped']);

        return response()->json([
            'success' => true,
            'message' => 'Reminder marked as skipped',
            'data' => $this->formatReminder($reminder->fresh()->load('pet')),
        ]);
    }

    /**
     * Delete a reminder.
     *
     * DELETE /api/reminders/{reminder}
     */
    public function destroy(Request $request, Reminder $reminder)
    {
        // Verify ownership
        if ($reminder->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Reminder not found',
            ], 404);
        }

        $reminder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reminder deleted successfully',
        ]);
    }

    /**
     * Get filter options for reminders.
     *
     * GET /api/reminders/filters
     */
    public function filters(Request $request)
    {
        $user = $request->user();

        // Get user's pets for pet filter
        $pets = $user->pets()->select('id', 'pet_name', 'species')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => [
                    ['value' => 'feeding', 'label' => 'Feeding'],
                    ['value' => 'grooming', 'label' => 'Grooming'],
                    ['value' => 'vaccination', 'label' => 'Vaccination'],
                    ['value' => 'medication', 'label' => 'Medication'],
                    ['value' => 'checkup', 'label' => 'Checkup'],
                    ['value' => 'other', 'label' => 'Other'],
                ],
                'schedules' => [
                    ['value' => 'none', 'label' => 'One-time'],
                    ['value' => 'daily', 'label' => 'Daily'],
                    ['value' => 'weekly', 'label' => 'Weekly'],
                    ['value' => 'monthly', 'label' => 'Monthly'],
                    ['value' => 'yearly', 'label' => 'Yearly'],
                ],
                'statuses' => [
                    ['value' => 'pending', 'label' => 'Pending'],
                    ['value' => 'done', 'label' => 'Completed'],
                    ['value' => 'skipped', 'label' => 'Skipped'],
                ],
                'pets' => $pets->map(fn($p) => [
                    'value' => $p->id,
                    'label' => $p->pet_name,
                    'species' => $p->species,
                ]),
            ],
        ]);
    }

    /**
     * Get calendar data (dates with reminders).
     *
     * GET /api/reminders/calendar
     * Query params: month (Y-m), pet_id
     */
    public function calendar(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $query = Reminder::whereHas('pet', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })
        ->whereYear('remind_date', substr($month, 0, 4))
        ->whereMonth('remind_date', substr($month, 5, 2));

        if ($request->has('pet_id')) {
            $query->where('pet_id', $request->pet_id);
        }

        $reminders = $query->with('pet:id,pet_name')->get();

        // Group by date
        $grouped = $reminders->groupBy(function ($reminder) {
            return $reminder->remind_date->format('Y-m-d');
        });

        $calendarData = [];
        foreach ($grouped as $date => $dateReminders) {
            $calendarData[] = [
                'date' => $date,
                'count' => $dateReminders->count(),
                'reminders' => $dateReminders->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'title' => $r->title,
                        'category' => $r->category,
                        'status' => $r->status,
                        'time' => $r->remind_date->format('H:i'),
                        'pet_name' => $r->pet->pet_name,
                    ];
                }),
            ];
        }

        return response()->json([
            'success' => true,
            'month' => $month,
            'data' => $calendarData,
        ]);
    }

    /**
     * Format reminder for response.
     */
    private function formatReminder(Reminder $reminder)
    {
        return [
            'id' => $reminder->id,
            'pet_id' => $reminder->pet_id,
            'pet' => $reminder->pet ? [
                'id' => $reminder->pet->id,
                'name' => $reminder->pet->pet_name,
                'species' => $reminder->pet->species,
                'image_url' => $reminder->pet->image_url ? url($reminder->pet->image_url) : null,
            ] : null,
            'title' => $reminder->title,
            'description' => $reminder->description,
            'remind_date' => $reminder->remind_date->format('Y-m-d'),
            'remind_time' => $reminder->remind_date->format('H:i'),
            'remind_datetime' => $reminder->remind_date->toIso8601String(),
            'remind_formatted' => $reminder->remind_date->format('M d, Y - h:i A'),
            'category' => $reminder->category,
            'repeat_type' => $reminder->repeat_type,
            'status' => $reminder->status,
            'is_overdue' => $reminder->status === 'pending' && $reminder->remind_date->isPast(),
            'created_at' => $reminder->created_at,
            'updated_at' => $reminder->updated_at,
        ];
    }
}
