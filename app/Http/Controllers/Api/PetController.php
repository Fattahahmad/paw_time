<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Get all pets for authenticated user.
     *
     * GET /api/pets
     * Query params: species, gender, search
     */
    public function index(Request $request)
    {
        $query = $request->user()->pets();

        // Filter by species
        if ($request->has('species')) {
            $query->where('species', $request->species);
        }

        // Filter by gender
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('pet_name', 'like', '%' . $request->search . '%');
        }

        $pets = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $pets->map(function ($pet) {
                return $this->formatPet($pet);
            }),
        ]);
    }

    /**
     * Get a specific pet.
     *
     * GET /api/pets/{pet}
     */
    public function show(Request $request, Pet $pet)
    {
        // Verify ownership
        if ($pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatPet($pet, true),
        ]);
    }

    /**
     * Store a new pet.
     *
     * POST /api/pets
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_name' => 'required|string|max:100',
            'species' => 'required|string|max:50',
            'breed' => 'nullable|string|max:100',
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'birth_date' => $validated['birth_date'] ?? null,
            'color' => $validated['color'] ?? null,
            'description' => $validated['description'] ?? null,
            'image_url' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pet created successfully',
            'data' => $this->formatPet($pet),
        ], 201);
    }

    /**
     * Update a pet.
     *
     * PUT /api/pets/{pet}
     */
    public function update(Request $request, Pet $pet)
    {
        // Verify ownership
        if ($pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found',
            ], 404);
        }

        $validated = $request->validate([
            'pet_name' => 'sometimes|string|max:100',
            'species' => 'sometimes|string|max:50',
            'breed' => 'nullable|string|max:100',
            'gender' => 'sometimes|in:male,female',
            'birth_date' => 'nullable|date',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($pet->image_url) {
                $oldPath = str_replace('/storage/', '', $pet->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('pets', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $pet->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pet updated successfully',
            'data' => $this->formatPet($pet->fresh()),
        ]);
    }

    /**
     * Delete a pet.
     *
     * DELETE /api/pets/{pet}
     */
    public function destroy(Request $request, Pet $pet)
    {
        // Verify ownership
        if ($pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found',
            ], 404);
        }

        // Delete image if exists
        if ($pet->image_url) {
            Storage::disk('public')->delete($pet->image_url);
        }

        $pet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pet deleted successfully',
        ]);
    }

    /**
     * Format pet data for response.
     */
    private function formatPet(Pet $pet, $includeRelations = false)
    {
        $data = [
            'id' => $pet->id,
            'pet_name' => $pet->pet_name,
            'species' => $pet->species,
            'breed' => $pet->breed,
            'gender' => $pet->gender,
            'birth_date' => $pet->birth_date?->format('Y-m-d'),
            'color' => $pet->color,
            'description' => $pet->description,
            'image_url' => $pet->image_url ? asset('storage/' . $pet->image_url) : null,
            'age' => $pet->birth_date ? $this->calculateAge($pet->birth_date) : null,
            'created_at' => $pet->created_at,
            'updated_at' => $pet->updated_at,
        ];

        if ($includeRelations) {
            $data['growth_records_count'] = $pet->growthRecords()->count();
            $data['health_checks_count'] = $pet->healthChecks()->count();
        }

        return $data;
    }

    /**
     * Calculate pet age.
     */
    private function calculateAge($birthDate)
    {
        $birth = \Carbon\Carbon::parse($birthDate);
        $now = now();

        // Handle future birth date
        if ($birth->isFuture()) {
            return 'Not born yet';
        }

        $years = (int) $birth->diffInYears($now);
        $months = (int) $birth->copy()->addYears($years)->diffInMonths($now);

        if ($years > 0) {
            $result = $years . ' year' . ($years > 1 ? 's' : '');
            if ($months > 0) {
                $result .= ' ' . $months . ' month' . ($months > 1 ? 's' : '');
            }
            return $result;
        }

        if ($months > 0) {
            return $months . ' month' . ($months > 1 ? 's' : '');
        }

        // Less than a month old
        $days = (int) $birth->diffInDays($now);
        if ($days > 0) {
            return $days . ' day' . ($days > 1 ? 's' : '');
        }

        return 'Newborn';
    }
}
