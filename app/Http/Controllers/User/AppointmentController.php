<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display user's appointments.
     */
    public function index()
    {
        $appointments = Appointment::with(['pet', 'medicalRecord'])
            ->where('user_id', auth()->id())
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('pages.user.appointments.index', compact('appointments'));
    }

    /**
     * Store new appointment booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'appointment_date' => 'nullable|date|after:now',
            'notes' => 'required|string|max:1000',
        ]);

        // Verify pet belongs to user
        $pet = auth()->user()->pets()->findOrFail($validated['pet_id']);

        // Create appointment with default date if not provided
        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'pet_id' => $validated['pet_id'],
            'appointment_date' => $validated['appointment_date'] ?? now()->addDays(1)->setTime(9, 0),
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        // Generate WhatsApp URL
        $whatsappNumber = '6281234567890'; // Admin WhatsApp
        $message = urlencode("Halo, saya {$request->user()->name} ingin booking appointment untuk {$pet->pet_name}.\n\nKeluhan: {$validated['notes']}\n\nMohon konfirmasi jadwal. Terima kasih!");
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$message}";

        return response()->json([
            'success' => true,
            'message' => 'Appointment berhasil dibuat',
            'whatsapp_url' => $whatsappUrl,
            'appointment' => $appointment->load('pet'),
        ]);
    }

    /**
     * Show appointment details.
     */
    public function show(Appointment $appointment)
    {
        // Ensure user can only view their own appointments
        if ($appointment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $appointment->load(['pet', 'medicalRecord.createdBy']);

        return view('pages.user.appointments.show', compact('appointment'));
    }
}
