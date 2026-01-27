<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Pet;
use App\Models\MedicalRecord;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Display appointments in kanban view.
     */
    public function index()
    {
        $appointments = [
            'pending' => Appointment::with(['user', 'pet'])->pending()->orderBy('appointment_date')->get(),
            'confirmed' => Appointment::with(['user', 'pet'])->confirmed()->orderBy('appointment_date')->get(),
            'completed' => Appointment::with(['user', 'pet'])->completed()->orderBy('appointment_date', 'desc')->get(),
            'cancelled' => Appointment::with(['user', 'pet'])->cancelled()->orderBy('appointment_date', 'desc')->get(),
        ];

        return view('pages.admin.appointments.index', compact('appointments'));
    }

    /**
     * Show create appointment form.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $pets = Pet::with('user')->orderBy('pet_name')->get();

        return view('pages.admin.appointments.create', compact('users', 'pets'));
    }

    /**
     * Store new appointment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $appointment = Appointment::create($validated);

            // Send notification
            $this->sendAppointmentNotification($appointment, 'created');

            DB::commit();

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Appointment created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create appointment: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show appointment details with medical record.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'pet', 'medicalRecord.createdBy']);
        
        return view('pages.admin.appointments.show', compact('appointment'));
    }

    /**
     * Show edit form.
     */
    public function edit(Appointment $appointment)
    {
        $appointment->load(['user', 'pet']);
        $users = User::orderBy('name')->get();
        $pets = Pet::with('user')->orderBy('pet_name')->get();

        return view('pages.admin.appointments.edit', compact('appointment', 'users', 'pets'));
    }

    /**
     * Update appointment.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
            'veterinarian_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $appointment->status;
            $appointment->update($validated);

            // Auto-generate medical record if status changed to completed
            if ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
                if (!$appointment->medicalRecord) {
                    MedicalRecord::create([
                        'appointment_id' => $appointment->id,
                        'created_by' => auth()->id(),
                        'diagnosis' => 'To be filled',
                        'treatment' => 'To be filled',
                        'prescription' => null,
                    ]);
                }
            }

            // Send notification if status changed
            if ($oldStatus !== $validated['status']) {
                $this->sendAppointmentNotification($appointment, 'status_changed');
            }

            DB::commit();

            return redirect()->route('admin.appointments.show', $appointment)
                ->with('success', 'Appointment updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update appointment: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Cancel appointment.
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'required|string',
            'cancellation_fee' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $appointment->update([
                'status' => 'cancelled',
                'cancellation_reason' => $validated['cancellation_reason'],
                'cancellation_fee' => $validated['cancellation_fee'] ?? 0,
            ]);

            // Send cancellation notification
            $this->sendAppointmentNotification($appointment, 'cancelled');

            DB::commit();

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Appointment cancelled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel appointment: ' . $e->getMessage());
        }
    }

    /**
     * Send appointment notifications.
     */
    protected function sendAppointmentNotification(Appointment $appointment, string $type)
    {
        $user = $appointment->user;
        $pet = $appointment->pet;

        $notifications = [
            'created' => [
                'title' => 'Appointment Scheduled',
                'body' => "Your appointment for {$pet->pet_name} has been scheduled for {$appointment->formatted_date}",
            ],
            'status_changed' => [
                'title' => match($appointment->status) {
                    'confirmed' => 'Appointment Confirmed',
                    'completed' => 'Visit Completed',
                    'cancelled' => 'Appointment Cancelled',
                    default => 'Appointment Updated',
                },
                'body' => match($appointment->status) {
                    'confirmed' => "Your appointment for {$pet->pet_name} on {$appointment->formatted_date} has been confirmed",
                    'completed' => "Your visit for {$pet->pet_name} has been completed. Check your medical record.",
                    'cancelled' => "Your appointment for {$pet->pet_name} has been cancelled" . ($appointment->cancellation_reason ? ": {$appointment->cancellation_reason}" : ""),
                    default => "Your appointment status has been updated",
                },
            ],
            'cancelled' => [
                'title' => 'Appointment Cancelled',
                'body' => "Your appointment for {$pet->pet_name} has been cancelled: {$appointment->cancellation_reason}",
            ],
        ];

        $notification = $notifications[$type] ?? null;

        if ($notification && $user) {
            try {
                $this->firebaseService->sendToUser(
                    $user->id,
                    $notification['title'],
                    $notification['body'],
                    [
                        'type' => 'appointment',
                        'appointment_id' => $appointment->id,
                        'action' => 'view_appointment',
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send appointment notification: ' . $e->getMessage());
            }
        }
    }
}
