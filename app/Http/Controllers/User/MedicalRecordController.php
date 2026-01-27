<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalRecordController extends Controller
{
    /**
     * Download medical record as PDF.
     */
    public function downloadPDF(MedicalRecord $medicalRecord)
    {
        // Ensure user can only download their own medical records
        if ($medicalRecord->appointment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $medicalRecord->load(['appointment.user', 'appointment.pet', 'createdBy', 'healthCheck']);
        
        $pdf = Pdf::loadView('pdfs.medical-record', compact('medicalRecord'));
        
        $filename = 'medical-record-' . $medicalRecord->id . '-' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
