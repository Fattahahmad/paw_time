<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    /**
     * Store a newly created pet.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_name' => 'required|string|max:100',
            'species' => 'required|string|max:50',
            'breed' => 'nullable|string|max:50',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|before_or_equal:today',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pets', 'public');
        }

        $pet = $request->user()->pets()->create([
            'pet_name' => $validated['pet_name'],
            'species' => $validated['species'],
            'breed' => $validated['breed'] ?? null,
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'],
            'color' => $validated['color'] ?? null,
            'description' => $validated['description'] ?? null,
            'image_url' => $imagePath,
        ]);

        // If weight/height provided, create initial growth record
        if (!empty($validated['weight']) || !empty($validated['height'])) {
            $pet->growthRecords()->create([
                'weight' => $validated['weight'] ?? null,
                'height' => $validated['height'] ?? null,
                'record_date' => now()->toDateString(),
                'notes' => 'Initial measurement',
            ]);
        }

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pet added successfully! 🎉',
                'pet' => $pet->load('latestGrowth'),
            ]);
        }

        return redirect()->route('user.dashboard')
            ->with('success', 'Pet added successfully! 🎉');
    }

    /**
     * Update the specified pet.
     */
    public function update(Request $request, Pet $pet)
    {
        // Ensure user owns this pet
        if ($pet->user_id !== $request->user()->id) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        $validated = $request->validate([
            'pet_name' => 'required|string|max:100',
            'species' => 'required|string|max:50',
            'breed' => 'nullable|string|max:50',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|before_or_equal:today',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('pets', 'public');
        }

        $pet->update($validated);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pet updated successfully!',
                'pet' => $pet->fresh()->load('latestGrowth'),
            ]);
        }

        return redirect()->back()->with('success', 'Pet updated successfully!');
    }

    /**
     * Remove the specified pet.
     */
    public function destroy(Request $request, Pet $pet)
    {
        // Ensure user owns this pet
        if ($pet->user_id !== $request->user()->id) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        $pet->delete();

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pet deleted successfully!',
            ]);
        }

        return redirect()->route('user.dashboard')
            ->with('success', 'Pet deleted successfully!');
    }
}
