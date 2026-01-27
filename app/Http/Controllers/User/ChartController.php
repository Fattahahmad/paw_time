<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetGrowth;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    /**
     * Display the chart page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $pets = $user->pets()->get();

        // Get selected pet or first pet
        $selectedPetId = $request->get('pet_id', $pets->first()?->id);
        $selectedPet = $pets->find($selectedPetId);

        $growthData = [];
        if ($selectedPet) {
            $growthData = PetGrowth::where('pet_id', $selectedPet->id)
                ->orderBy('record_date', 'asc')
                ->get();
        }

        return view('pages.user.chart', compact('pets', 'selectedPet', 'growthData'));
    }

    /**
     * Store a new growth entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'record_date' => 'required|date|before_or_equal:today',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'notes' => 'nullable|string',
        ]);

        // Verify user owns this pet
        $pet = $request->user()->pets()->findOrFail($validated['pet_id']);

        // Check if at least weight or height is provided
        if (empty($validated['weight']) && empty($validated['height'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide at least weight or height.',
                ], 422);
            }
            return redirect()->back()
                ->withErrors(['weight' => 'Please provide at least weight or height.'])
                ->withInput();
        }

        $growth = $pet->growthRecords()->create([
            'record_date' => $validated['record_date'],
            'weight' => $validated['weight'] ?? null,
            'height' => $validated['height'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Growth entry added successfully!',
                'growth' => $growth,
            ]);
        }

        return redirect()->route('user.chart', ['pet_id' => $pet->id])
            ->with('success', 'Growth entry added successfully!');
    }
}
