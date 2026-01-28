<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicalRecordController extends Controller
{
    /**
     * Get all medical records for authenticated user.
     *
     * GET /api/medical-records
     * Query params: pet_id, appointment_id, search, date_from, date_to
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = MedicalRecord::whereHas('appointment', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['appointment.pet:id,pet_name,species,image_url']);

        // Filter by pet (through appointment)
        if ($request->has('pet_id')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('pet_id', $request->pet_id);
            });
        }

        // Filter by appointment
        if ($request->has('appointment_id')) {
            $query->where('appointment_id', $request->appointment_id);
        }

        // Search by diagnosis, treatment, prescription
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('diagnosis', 'like', "%{$searchTerm}%")
                  ->orWhere('treatment', 'like', "%{$searchTerm}%")
                  ->orWhere('prescription', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by date range (based on appointment date)
        if ($request->has('date_from')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->whereDate('appointment_date', '>=', $request->date_from);
            });
        }
        if ($request->has('date_to')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->whereDate('appointment_date', '<=', $request->date_to);
            });
        }

        // Order
        $query->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 20);
        if ($request->boolean('all')) {
            $records = $query->get();
            return response()->json([
                'success' => true,
                'data' => $records->map(fn($r) => $this->formatMedicalRecord($r)),
            ]);
        }

        $records = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $records->getCollection()->map(fn($r) => $this->formatMedicalRecord($r)),
            'meta' => [
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'per_page' => $records->perPage(),
                'total' => $records->total(),
            ]
        ]);
    }

    /**
     * Get a specific medical record.
     *
     * GET /api/medical-records/{medicalRecord}
     */
    public function show(Request $request, MedicalRecord $medicalRecord)
    {
        // Verify ownership through appointment
        if ($medicalRecord->appointment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Medical record not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatMedicalRecord($medicalRecord->load(['appointment.pet'])),
        ]);
    }

    /**
     * Store a new medical record.
     *
     * POST /api/medical-records
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Verify ownership of appointment
        $appointment = $request->user()->appointments()->find($validated['appointment_id']);
        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found',
            ], 404);
        }

        // Check if appointment already has medical record
        if ($appointment->medicalRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment already has a medical record. Use update instead.',
            ], 400);
        }

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('medical-records', 'public');
                $attachments[] = [
                    'path' => Storage::url($path),
                    'name' => $file->getClientOriginalName(),
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }

        $medicalRecord = MedicalRecord::create([
            'appointment_id' => $validated['appointment_id'],
            'created_by' => $request->user()->id,
            'diagnosis' => $validated['diagnosis'],
            'treatment' => $validated['treatment'] ?? null,
            'prescription' => $validated['prescription'] ?? null,
            'attachments' => !empty($attachments) ? $attachments : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Medical record created successfully',
            'data' => $this->formatMedicalRecord($medicalRecord->load(['appointment.pet'])),
        ], 201);
    }

    /**
     * Update a medical record.
     *
     * PUT /api/medical-records/{medicalRecord}
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        // Verify ownership through appointment
        if ($medicalRecord->appointment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Medical record not found',
            ], 404);
        }

        $validated = $request->validate([
            'diagnosis' => 'sometimes|string',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle new file uploads (append to existing)
        if ($request->hasFile('attachments')) {
            $attachments = $medicalRecord->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('medical-records', 'public');
                $attachments[] = [
                    'path' => Storage::url($path),
                    'name' => $file->getClientOriginalName(),
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        $medicalRecord->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Medical record updated successfully',
            'data' => $this->formatMedicalRecord($medicalRecord->fresh()->load(['appointment.pet'])),
        ]);
    }

    /**
     * Delete a medical record.
     *
     * DELETE /api/medical-records/{medicalRecord}
     */
    public function destroy(Request $request, MedicalRecord $medicalRecord)
    {
        // Verify ownership through appointment
        if ($medicalRecord->appointment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Medical record not found',
            ], 404);
        }

        // Delete attachments from storage
        if ($medicalRecord->attachments) {
            foreach ($medicalRecord->attachments as $attachment) {
                $path = str_replace('/storage/', '', $attachment['path']);
                Storage::disk('public')->delete($path);
            }
        }

        $medicalRecord->delete();

        return response()->json([
            'success' => true,
            'message' => 'Medical record deleted successfully',
        ]);
    }

    /**
     * Remove a specific attachment from medical record.
     *
     * DELETE /api/medical-records/{medicalRecord}/attachments/{index}
     */
    public function removeAttachment(Request $request, MedicalRecord $medicalRecord, $index)
    {
        // Verify ownership through appointment
        if ($medicalRecord->appointment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Medical record not found',
            ], 404);
        }

        $attachments = $medicalRecord->attachments ?? [];

        if (!isset($attachments[$index])) {
            return response()->json([
                'success' => false,
                'message' => 'Attachment not found',
            ], 404);
        }

        // Delete from storage
        $path = str_replace('/storage/', '', $attachments[$index]['path']);
        Storage::disk('public')->delete($path);

        // Remove from array
        array_splice($attachments, $index, 1);
        $medicalRecord->update(['attachments' => !empty($attachments) ? $attachments : null]);

        return response()->json([
            'success' => true,
            'message' => 'Attachment removed successfully',
            'data' => $this->formatMedicalRecord($medicalRecord->fresh()->load(['appointment.pet'])),
        ]);
    }

    /**
     * Get medical records by pet.
     *
     * GET /api/medical-records/pet/{pet}
     */
    public function byPet(Request $request, $petId)
    {
        // Verify ownership of pet
        $pet = $request->user()->pets()->find($petId);
        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found',
            ], 404);
        }

        $records = MedicalRecord::whereHas('appointment', function ($q) use ($petId) {
            $q->where('pet_id', $petId);
        })->with(['appointment'])
          ->orderBy('created_at', 'desc')
          ->get();

        return response()->json([
            'success' => true,
            'pet' => [
                'id' => $pet->id,
                'name' => $pet->pet_name,
            ],
            'data' => $records->map(fn($r) => $this->formatMedicalRecord($r)),
        ]);
    }

    /**
     * Format medical record for response.
     */
    private function formatMedicalRecord(MedicalRecord $record)
    {
        return [
            'id' => $record->id,
            'appointment_id' => $record->appointment_id,
            'appointment' => $record->appointment ? [
                'id' => $record->appointment->id,
                'date' => $record->appointment->appointment_date->format('Y-m-d H:i'),
                'date_formatted' => $record->appointment->appointment_date->format('M d, Y - h:i A'),
                'status' => $record->appointment->status,
                'pet' => $record->appointment->pet ? [
                    'id' => $record->appointment->pet->id,
                    'name' => $record->appointment->pet->pet_name,
                    'species' => $record->appointment->pet->species,
                    'image_url' => $record->appointment->pet->image_url ? url($record->appointment->pet->image_url) : null,
                ] : null,
            ] : null,
            'diagnosis' => $record->diagnosis,
            'treatment' => $record->treatment,
            'prescription' => $record->prescription,
            'attachments' => $record->attachments ? array_map(function ($att, $idx) {
                return [
                    'index' => $idx,
                    'path' => url($att['path']),
                    'name' => $att['name'],
                    'type' => $att['type'],
                    'size' => $att['size'],
                ];
            }, $record->attachments, array_keys($record->attachments)) : [],
            'has_attachments' => $record->has_attachments,
            'attachment_count' => $record->attachment_count,
            'created_at' => $record->created_at,
            'updated_at' => $record->updated_at,
        ];
    }
}
