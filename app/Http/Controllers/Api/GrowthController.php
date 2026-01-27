<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetGrowth;
use Illuminate\Http\Request;

class GrowthController extends Controller
{
    /**
     * Get growth records.
     *
     * GET /api/growth
     * Query params: pet_id (required), date_from, date_to, limit
     */
    public function index(Request $request)
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

        $query = $pet->growthRecords();

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('record_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('record_date', '<=', $request->date_to);
        }

        // Order by date
        $query->orderBy('record_date', 'desc');

        // Limit
        if ($request->has('limit')) {
            $records = $query->limit($request->limit)->get();
        } else {
            $records = $query->get();
        }

        return response()->json([
            'success' => true,
            'data' => $records->map(function ($record) {
                return $this->formatGrowth($record);
            }),
            'summary' => $this->calculateSummary($records),
        ]);
    }

    /**
     * Get chart data for a pet.
     *
     * GET /api/growth/chart
     * Query params: pet_id (required), type (weight/height/both), period (all/6m/1y)
     */
    public function chart(Request $request)
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

        $query = $pet->growthRecords()->orderBy('record_date', 'asc');

        // Filter by period
        $period = $request->get('period', 'all');
        if ($period === '6m') {
            $query->where('record_date', '>=', now()->subMonths(6));
        } elseif ($period === '1y') {
            $query->where('record_date', '>=', now()->subYear());
        } elseif ($period === '3m') {
            $query->where('record_date', '>=', now()->subMonths(3));
        }

        $records = $query->get();

        $type = $request->get('type', 'both');

        $chartData = [
            'labels' => $records->map(fn($r) => $r->record_date->format('M d, Y'))->toArray(),
            'datasets' => [],
        ];

        if ($type === 'weight' || $type === 'both') {
            $chartData['datasets'][] = [
                'label' => 'Weight (kg)',
                'data' => $records->pluck('weight')->toArray(),
            ];
        }

        if ($type === 'height' || $type === 'both') {
            $chartData['datasets'][] = [
                'label' => 'Height (cm)',
                'data' => $records->pluck('height')->toArray(),
            ];
        }

        return response()->json([
            'success' => true,
            'pet' => [
                'id' => $pet->id,
                'name' => $pet->pet_name,
            ],
            'chart' => $chartData,
            'summary' => $this->calculateSummary($records),
        ]);
    }

    /**
     * Get a specific growth record.
     *
     * GET /api/growth/{growth}
     */
    public function show(Request $request, PetGrowth $growth)
    {
        // Verify ownership
        if ($growth->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatGrowth($growth),
        ]);
    }

    /**
     * Store a new growth record.
     *
     * POST /api/growth
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'weight' => 'required|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'record_date' => 'required|date',
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

        $growth = $pet->growthRecords()->create([
            'weight' => $validated['weight'],
            'height' => $validated['height'] ?? null,
            'record_date' => $validated['record_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Growth record created successfully',
            'data' => $this->formatGrowth($growth),
        ], 201);
    }

    /**
     * Update a growth record.
     *
     * PUT /api/growth/{growth}
     */
    public function update(Request $request, PetGrowth $growth)
    {
        // Verify ownership
        if ($growth->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found',
            ], 404);
        }

        $validated = $request->validate([
            'weight' => 'sometimes|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'record_date' => 'sometimes|date',
            'notes' => 'nullable|string',
        ]);

        $growth->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Growth record updated successfully',
            'data' => $this->formatGrowth($growth->fresh()),
        ]);
    }

    /**
     * Delete a growth record.
     *
     * DELETE /api/growth/{growth}
     */
    public function destroy(Request $request, PetGrowth $growth)
    {
        // Verify ownership
        if ($growth->pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found',
            ], 404);
        }

        $growth->delete();

        return response()->json([
            'success' => true,
            'message' => 'Growth record deleted successfully',
        ]);
    }

    /**
     * Get filter options for growth records.
     *
     * GET /api/growth/filters
     */
    public function filters(Request $request)
    {
        $user = $request->user();

        // Get user's pets for pet filter
        $pets = $user->pets()->select('id', 'pet_name', 'species')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'periods' => [
                    ['value' => 'all', 'label' => 'All Time'],
                    ['value' => '3m', 'label' => 'Last 3 Months'],
                    ['value' => '6m', 'label' => 'Last 6 Months'],
                    ['value' => '1y', 'label' => 'Last Year'],
                ],
                'chart_types' => [
                    ['value' => 'weight', 'label' => 'Weight'],
                    ['value' => 'height', 'label' => 'Height'],
                    ['value' => 'both', 'label' => 'Both'],
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
     * Format growth record for response.
     */
    private function formatGrowth(PetGrowth $growth)
    {
        return [
            'id' => $growth->id,
            'pet_id' => $growth->pet_id,
            'weight' => $growth->weight,
            'height' => $growth->height,
            'record_date' => $growth->record_date->format('Y-m-d'),
            'record_date_formatted' => $growth->record_date->format('M d, Y'),
            'notes' => $growth->notes,
            'created_at' => $growth->created_at,
            'updated_at' => $growth->updated_at,
        ];
    }

    /**
     * Calculate summary statistics.
     */
    private function calculateSummary($records)
    {
        if ($records->isEmpty()) {
            return null;
        }

        $weights = $records->pluck('weight')->filter();
        $heights = $records->pluck('height')->filter();

        $firstRecord = $records->last(); // oldest (sorted desc)
        $lastRecord = $records->first(); // newest

        return [
            'total_records' => $records->count(),
            'current_weight' => $lastRecord->weight,
            'current_height' => $lastRecord->height,
            'weight_change' => $records->count() > 1 ? round($lastRecord->weight - $firstRecord->weight, 2) : 0,
            'height_change' => $records->count() > 1 && $lastRecord->height && $firstRecord->height
                ? round($lastRecord->height - $firstRecord->height, 2) : null,
            'avg_weight' => round($weights->avg(), 2),
            'avg_height' => $heights->isNotEmpty() ? round($heights->avg(), 2) : null,
            'min_weight' => $weights->min(),
            'max_weight' => $weights->max(),
            'first_record_date' => $firstRecord->record_date->format('Y-m-d'),
            'last_record_date' => $lastRecord->record_date->format('Y-m-d'),
        ];
    }
}
