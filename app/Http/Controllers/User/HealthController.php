<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthCheck;
use App\Models\Appointment;

class HealthController extends Controller
{
    /**
     * Display the health page with consultation history.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $pets = $user->pets()->get();

        // Get all health checks (consultations) for user's pets
        $consultations = HealthCheck::whereHas('pet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('pet')
        ->orderBy('created_at', 'desc')
        ->get();

        // Get all appointments for user
        $appointments = Appointment::where('user_id', $user->id)
            ->with(['pet', 'medicalRecord'])
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();

        // Doctor info (static/hardcoded since there's only one doctor = admin)
        $doctorInfo = [
            'name' => 'drh. Khoirunissa Pangesti',
            'specialty' => 'Veterinary',
            'rating' => 4.8,
            'appointments' => 1250,
            'whatsapp' => '6282136666170',
            'tags' => ['Pet behaviors', 'Pet Food', 'Pet Treatments'],
            'image' => 'assets/image/doctor.png',
            'description' => 'drh. Khoirunissa Pangesti is a highly experienced veterinarian with 8 years of dedicated practice, showcasing a profound commitment to animal care.',
        ];

        return view('pages.user.health', compact('user', 'pets', 'consultations', 'doctorInfo'));
    }

    /**
     * Store a new consultation booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'complaint' => 'required|string|max:500',
        ]);

        // Verify user owns this pet
        $pet = $request->user()->pets()->findOrFail($validated['pet_id']);

        // Get doctor info
        $doctorName = 'drh. Khoirunissa Pangesti';
        $doctorPhone = '6281222551815';

        // Create health check record
        $consultation = $pet->healthChecks()->create([
            'check_date' => now()->toDateString(),
            'complaint' => $validated['complaint'],
            'status' => 'pending',
            'doctor_name' => $doctorName,
            'doctor_phone' => $doctorPhone,
        ]);

        // Build WhatsApp message
        $message = $this->buildWhatsAppMessage($pet, $validated['complaint'], $consultation->id);
        $whatsappUrl = "https://wa.me/{$doctorPhone}?text=" . urlencode($message);

        return response()->json([
            'success' => true,
            'message' => 'Consultation booked successfully!',
            'consultation_id' => $consultation->id,
            'whatsapp_url' => $whatsappUrl,
        ]);
    }

    /**
     * Build WhatsApp message template.
     */
    private function buildWhatsAppMessage($pet, $complaint, $consultationId)
    {
        $user = $pet->user;

        $message = "*KONSULTASI Shine Vet*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "*ID Konsultasi:* #{$consultationId}\n";
        $message .= "*Pemilik:* {$user->name}\n\n";
        $message .= "*Data Hewan:*\n";
        $message .= "• Nama: {$pet->pet_name}\n";
        $message .= "• Jenis: {$pet->species}\n";
        $message .= "• Ras: " . ($pet->breed ?? '-') . "\n";
        $message .= "• Gender: " . ucfirst($pet->gender) . "\n";
        $message .= "• Umur: {$pet->age}\n\n";
        $message .= "*Keluhan:*\n{$complaint}\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "Mohon konfirmasi jadwal konsultasi. Terima kasih!";

        return $message;
    }

    /**
     * Update health check / consultation.
     */
    public function update(Request $request, HealthCheck $healthCheck)
    {
        // Verify user owns this health check's pet
        if ($healthCheck->pet->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,on_progress,done',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'next_visit_date' => 'nullable|date',
        ]);

        $healthCheck->update($validated);

        return redirect()->back()->with('success', 'Consultation updated successfully!');
    }

    /**
     * Delete health check / consultation.
     */
    public function destroy(Request $request, HealthCheck $healthCheck)
    {
        // Verify user owns this health check's pet
        if ($healthCheck->pet->user_id !== $request->user()->id) {
            abort(403);
        }

        $healthCheck->delete();

        return redirect()->back()->with('success', 'Consultation deleted successfully!');
    }
}
