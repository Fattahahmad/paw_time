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
