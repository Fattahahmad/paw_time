<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Get all appointments for authenticated user.
     *
     * GET /api/appointments
     * Query params: pet_id, status, date_from, date_to, search, upcoming
     */
    public function index(Request $request)
    {
        $query = $request->user()->appointments()->with('pet:id,pet_name,species,image_url');

        // Filter by pet
        if ($request->has('pet_id')) {
            $query->where('pet_id', $request->pet_id);
        }

        // Filter by status (pending, confirmed, completed, cancelled)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        // Search by notes
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('notes', 'like', "%{$searchTerm}%")
                  ->orWhere('veterinarian_notes', 'like', "%{$searchTerm}%");
            });
        }

        // Filter upcoming only
        if ($request->boolean('upcoming')) {
            $query->whereIn('status', ['pending', 'confirmed'])
                  ->where('appointment_date', '>=', now());
        }

        // Order
        $orderBy = $request->get('order_by', 'appointment_date');
        $orderDir = $request->get('order_dir', 'asc');
        $query->orderBy($orderBy, $orderDir);

        // Pagination
        $perPage = $request->get('per_page', 20);
        if ($request->boolean('all')) {
            $appointments = $query->get();
            return response()->json([
                'success' => true,
                'data' => $appointments->map(fn($a) => $this->formatAppointment($a)),
            ]);
        }

        $appointments = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $appointments->getCollection()->map(fn($a) => $this->formatAppointment($a)),
            'meta' => [
                'current_page' => $appointments->currentPage(),
                'last_page' => $appointments->lastPage(),
                'per_page' => $appointments->perPage(),
                'total' => $appointments->total(),
            ]
        ]);
    }

    /**
     * Get a specific appointment.
     *
     * GET /api/appointments/{appointment}
     */
    public function show(Request $request, Appointment $appointment)
    {
        // Verify ownership
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatAppointment($appointment->load(['pet', 'medicalRecord'])),
        ]);
    }

    /**
     * Store a new appointment.
     *
     * POST /api/appointments
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        // Verify ownership of pet
        $pet = $request->user()->pets()->find($validated['pet_id']);
        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found',
            ], 404);
        }

        $appointment = $request->user()->appointments()->create([
            'pet_id' => $validated['pet_id'],
            'appointment_date' => $validated['appointment_date'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment created successfully',
            'data' => $this->formatAppointment($appointment->load('pet')),
        ], 201);
    }

    /**
     * Update an appointment.
     *
     * PUT /api/appointments/{appointment}
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Verify ownership
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found',
            ], 404);
        }

        // Can only edit pending/confirmed appointments
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit completed or cancelled appointments',
            ], 400);
        }

        $validated = $request->validate([
            'appointment_date' => 'sometimes|date|after:now',
            'notes' => 'nullable|string',
            'status' => 'sometimes|in:pending,confirmed',
        ]);

        $appointment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Appointment updated successfully',
            'data' => $this->formatAppointment($appointment->fresh()->load('pet')),
        ]);
    }

    /**
     * Cancel an appointment.
     *
     * PATCH /api/appointments/{appointment}/cancel
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        // Verify ownership
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found',
            ], 404);
        }

        if ($appointment->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Appointment already cancelled',
            ], 400);
        }

        if ($appointment->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel completed appointment',
            ], 400);
        }

        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment cancelled successfully',
            'data' => $this->formatAppointment($appointment->fresh()->load('pet')),
        ]);
    }

    /**
     * Confirm an appointment.
     *
     * PATCH /api/appointments/{appointment}/confirm
     */
    public function confirm(Request $request, Appointment $appointment)
    {
        // Verify ownership
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found',
            ], 404);
        }

        if ($appointment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending appointments can be confirmed',
            ], 400);
        }

        $appointment->update(['status' => 'confirmed']);

        return response()->json([
            'success' => true,
            'message' => 'Appointment confirmed successfully',
            'data' => $this->formatAppointment($appointment->fresh()->load('pet')),
        ]);
    }

    /**
     * Complete an appointment.
     *
     * PATCH /api/appointments/{appointment}/complete
     */
    public function complete(Request $request, Appointment $appointment)
    {
        // Verify ownership
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found',
            ], 404);
        }

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending or confirmed appointments can be completed',
            ], 400);
        }

        $validated = $request->validate([
            'veterinarian_notes' => 'nullable|string',
        ]);

        $appointment->update([
            'status' => 'completed',
            'veterinarian_notes' => $validated['veterinarian_notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment completed successfully',
            'data' => $this->formatAppointment($appointment->fresh()->load('pet')),
        ]);
    }

    /**
     * Delete an appointment.
     *
     * DELETE /api/appointments/{appointment}
     */
    public function destroy(Request $request, Appointment $appointment)
    {
        // Verify ownership
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found',
            ], 404);
        }

        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Appointment deleted successfully',
        ]);
    }

    /**
     * Get filter options for appointments.
     *
     * GET /api/appointments/filters
     */
    public function filters(Request $request)
    {
        $user = $request->user();

        // Get user's pets for pet filter
        $pets = $user->pets()->select('id', 'pet_name', 'species')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'statuses' => [
                    ['value' => 'pending', 'label' => 'Pending'],
                    ['value' => 'confirmed', 'label' => 'Confirmed'],
                    ['value' => 'completed', 'label' => 'Completed'],
                    ['value' => 'cancelled', 'label' => 'Cancelled'],
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
     * Format appointment for response.
     */
    private function formatAppointment(Appointment $appointment)
    {
        $data = [
            'id' => $appointment->id,
            'pet_id' => $appointment->pet_id,
            'pet' => $appointment->pet ? [
                'id' => $appointment->pet->id,
                'name' => $appointment->pet->pet_name,
                'species' => $appointment->pet->species,
                'image_url' => $appointment->pet->image_url ? url($appointment->pet->image_url) : null,
            ] : null,
            'appointment_date' => $appointment->appointment_date->format('Y-m-d H:i'),
            'appointment_date_formatted' => $appointment->appointment_date->format('M d, Y - h:i A'),
            'status' => $appointment->status,
            'notes' => $appointment->notes,
            'veterinarian_notes' => $appointment->veterinarian_notes,
            'cancellation_reason' => $appointment->cancellation_reason,
            'cancellation_fee' => $appointment->cancellation_fee,
            'is_upcoming' => in_array($appointment->status, ['pending', 'confirmed']) && $appointment->appointment_date->isFuture(),
            'is_past' => $appointment->appointment_date->isPast(),
            'created_at' => $appointment->created_at,
            'updated_at' => $appointment->updated_at,
        ];

        if ($appointment->relationLoaded('medicalRecord') && $appointment->medicalRecord) {
            $data['medical_record'] = [
                'id' => $appointment->medicalRecord->id,
                'diagnosis' => $appointment->medicalRecord->diagnosis,
                'treatment' => $appointment->medicalRecord->treatment,
                'prescription' => $appointment->medicalRecord->prescription,
            ];
        }

        return $data;
    }
}
