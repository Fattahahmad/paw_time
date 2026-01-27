<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalRecordController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Store medical record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'health_check_id' => 'nullable|exists:health_checks,id',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'prescription' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Handle file uploads
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('medical-records', 'public');
                    $attachmentPaths[] = $path;
                }
            }

            // Check if medical record already exists
            $appointment = Appointment::findOrFail($validated['appointment_id']);
            $medicalRecord = $appointment->medicalRecord;

            if ($medicalRecord) {
                // Update existing record
                $medicalRecord->update([
                    'health_check_id' => $validated['health_check_id'] ?? null,
                    'diagnosis' => $validated['diagnosis'],
                    'treatment' => $validated['treatment'],
                    'prescription' => $validated['prescription'] ?? null,
                    'attachments' => !empty($attachmentPaths) ? $attachmentPaths : $medicalRecord->attachments,
                ]);
            } else {
                // Create new record
                $medicalRecord = MedicalRecord::create([
                    'appointment_id' => $validated['appointment_id'],
                    'health_check_id' => $validated['health_check_id'] ?? null,
                    'created_by' => auth()->id(),
                    'diagnosis' => $validated['diagnosis'],
                    'treatment' => $validated['treatment'],
                    'prescription' => $validated['prescription'] ?? null,
                    'attachments' => $attachmentPaths,
                ]);
            }

            // Send notification to user
            $this->sendMedicalRecordNotification($appointment);

            DB::commit();

            return redirect()->route('admin.appointments.show', $appointment)
                ->with('success', 'Medical record saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save medical record: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show medical record.
     */
    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['appointment.user', 'appointment.pet', 'createdBy', 'healthCheck']);
        
        return view('pages.admin.medical-records.show', compact('medicalRecord'));
    }

    /**
     * Download medical record as PDF.
     */
    public function downloadPDF(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['appointment.user', 'appointment.pet', 'createdBy', 'healthCheck']);
        
        $pdf = Pdf::loadView('pdfs.medical-record', compact('medicalRecord'));
        
        $filename = 'medical-record-' . $medicalRecord->id . '-' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Send medical record ready notification.
     */
    protected function sendMedicalRecordNotification(Appointment $appointment)
    {
        $user = $appointment->user;
        $pet = $appointment->pet;

        try {
            $this->firebaseService->sendToUser(
                $user->id,
                'Medical Record Ready',
                "Your pet {$pet->pet_name}'s medical record is now available for download",
                [
                    'type' => 'medical_record',
                    'appointment_id' => $appointment->id,
                    'action' => 'view_medical_record',
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send medical record notification: ' . $e->getMessage());
        }
    }
}
