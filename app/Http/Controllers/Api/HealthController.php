<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthCheck;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    /**
     * Get health checks.
     *
     * GET /api/health-checks
     * Query params: pet_id, check_type, date_from, date_to
     */
    public function index(Request $request)
    {
        $query = HealthCheck::whereHas('pet', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('pet:id,pet_name,species,image_url');

        // Filter by pet
        if ($request->has('pet_id')) {
            $query->where('pet_id', $request->pet_id);
        }

        // Filter by check type
        if ($request->has('check_type')) {
            $query->where('check_type', $request->check_type);
        }

        // Search by diagnosis, treatment, or notes
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('diagnosis', 'like', "%{$searchTerm}%")
                  ->orWhere('treatment', 'like', "%{$searchTerm}%")
                  ->orWhere('notes', 'like', "%{$searchTerm}%")
                  ->orWhere('check_type', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('check_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('check_date', '<=', $request->date_to);
        }

        // Order
        $query->orderBy('check_date', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 20);
        if ($request->boolean('all')) {
            $healthChecks = $query->get();
            return response()->json([
                'success' => true,
                'data' => $healthChecks->map(fn($h) => $this->formatHealthCheck($h)),
            ]);
        }

        $healthChecks = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $healthChecks->getCollection()->map(fn($h) => $this->formatHealthCheck($h)),
            'meta' => [
                'current_page' => $healthChecks->currentPage(),
                'last_page' => $healthChecks->lastPage(),
                'per_page' => $healthChecks->perPage(),
                'total' => $healthChecks->total(),
            ]
        ]);
    }

    /**
     * Get a specific health check.
     *
     * GET /api/health-checks/{healthCheck}
     */
    public function show(Request $request, HealthCheck $healthCheck)
    {
        // Verify ownership
        if ($healthCheck->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Health check not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatHealthCheck($healthCheck->load('pet')),
        ]);
    }

    /**
     * Store a new health check.
     *
     * POST /api/health-checks
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'check_date' => 'required|date',
            'check_type' => 'required|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Verify ownership
        $pet = $request->user()->pets()->find($validated['pet_id']);
        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found',
            ], 404);
        }

        $healthCheck = $pet->healthChecks()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Health check created successfully',
            'data' => $this->formatHealthCheck($healthCheck->load('pet')),
        ], 201);
    }

    /**
     * Update a health check.
     *
     * PUT /api/health-checks/{healthCheck}
     */
    public function update(Request $request, HealthCheck $healthCheck)
    {
        // Verify ownership
        if ($healthCheck->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Health check not found',
            ], 404);
        }

        $validated = $request->validate([
            'check_date' => 'sometimes|date',
            'check_type' => 'sometimes|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $healthCheck->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Health check updated successfully',
            'data' => $this->formatHealthCheck($healthCheck->fresh()->load('pet')),
        ]);
    }

    /**
     * Delete a health check.
     *
     * DELETE /api/health-checks/{healthCheck}
     */
    public function destroy(Request $request, HealthCheck $healthCheck)
    {
        // Verify ownership
        if ($healthCheck->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Health check not found',
            ], 404);
        }

        $healthCheck->delete();

        return response()->json([
            'success' => true,
            'message' => 'Health check deleted successfully',
        ]);
    }

    /**
     * Get health summary for a pet.
     *
     * GET /api/health-checks/summary
     * Query params: pet_id (required)
     */
    public function summary(Request $request)
    {
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
        ]);

        // Verify ownership
        $pet = $request->user()->pets()->find($request->pet_id);
        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found',
            ], 404);
        }

        $healthChecks = $pet->healthChecks()->orderBy('check_date', 'desc')->get();

        if ($healthChecks->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'No health checks recorded',
            ]);
        }

        $lastCheck = $healthChecks->first();
        $checkTypes = $healthChecks->groupBy('check_type');

        return response()->json([
            'success' => true,
            'data' => [
                'pet' => [
                    'id' => $pet->id,
                    'name' => $pet->pet_name,
                ],
                'total_checks' => $healthChecks->count(),
                'last_check' => [
                    'date' => $lastCheck->check_date->format('Y-m-d'),
                    'type' => $lastCheck->check_type,
                    'weight' => $lastCheck->weight,
                    'temperature' => $lastCheck->temperature,
                ],
                'check_types' => $checkTypes->map(function ($checks, $type) {
                    return [
                        'type' => $type,
                        'count' => $checks->count(),
                        'last_date' => $checks->first()->check_date->format('Y-m-d'),
                    ];
                })->values(),
                'weight_history' => $healthChecks->whereNotNull('weight')->take(10)->map(function ($h) {
                    return [
                        'date' => $h->check_date->format('Y-m-d'),
                        'weight' => $h->weight,
                    ];
                })->values(),
            ]
        ]);
    }

    /**
     * Get filter options for health checks.
     *
     * GET /api/health-checks/filters
     */
    public function filters(Request $request)
    {
        $user = $request->user();

        // Get user's pets for pet filter
        $pets = $user->pets()->select('id', 'pet_name', 'species')->get();

        // Get distinct check types used by user
        $checkTypes = HealthCheck::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->distinct()
            ->pluck('check_type')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'check_types' => [
                    ['value' => 'routine', 'label' => 'Routine Checkup'],
                    ['value' => 'vaccination', 'label' => 'Vaccination'],
                    ['value' => 'illness', 'label' => 'Illness'],
                    ['value' => 'injury', 'label' => 'Injury'],
                    ['value' => 'surgery', 'label' => 'Surgery'],
                    ['value' => 'dental', 'label' => 'Dental'],
                    ['value' => 'other', 'label' => 'Other'],
                ],
                'used_check_types' => $checkTypes,
                'pets' => $pets->map(fn($p) => [
                    'value' => $p->id,
                    'label' => $p->pet_name,
                    'species' => $p->species,
                ]),
            ],
        ]);
    }

    /**
     * Format health check for response.
     */
    private function formatHealthCheck(HealthCheck $healthCheck)
    {
        return [
            'id' => $healthCheck->id,
            'pet_id' => $healthCheck->pet_id,
            'pet' => $healthCheck->pet ? [
                'id' => $healthCheck->pet->id,
                'name' => $healthCheck->pet->pet_name,
                'species' => $healthCheck->pet->species,
                'image_url' => $healthCheck->pet->image_url ? url($healthCheck->pet->image_url) : null,
            ] : null,
            'check_date' => $healthCheck->check_date->format('Y-m-d'),
            'check_date_formatted' => $healthCheck->check_date->format('M d, Y'),
            'check_type' => $healthCheck->check_type,
            'weight' => $healthCheck->weight,
            'temperature' => $healthCheck->temperature,
            'diagnosis' => $healthCheck->diagnosis,
            'treatment' => $healthCheck->treatment,
            'notes' => $healthCheck->notes,
            'created_at' => $healthCheck->created_at,
            'updated_at' => $healthCheck->updated_at,
        ];
    }
}
