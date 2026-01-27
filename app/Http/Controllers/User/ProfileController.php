<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the profile page with all pets.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $pets = Pet::forUser($user->id)
            ->with('latestGrowth')
            ->get();

        // Group pets by species for filtering
        $petsBySpecies = $pets->groupBy(function($pet) {
            return strtolower($pet->species);
        });

        return view('pages.user.profile', compact('user', 'pets', 'petsBySpecies'));
    }

    /**
     * Update user profile.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user = $request->user();
        $user->name = $validated['name'];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $imagePath;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'user' => $user,
        ]);
    }

    /**
     * Get pet detail data.
     */
    public function getPetDetail(Request $request, Pet $pet)
    {
        // Verify user owns this pet
        if ($pet->user_id !== $request->user()->id) {
            abort(403);
        }

        $pet->load('latestGrowth');

        return response()->json([
            'success' => true,
            'pet' => [
                'id' => $pet->id,
                'pet_name' => $pet->pet_name,
                'species' => $pet->species,
                'breed' => $pet->breed,
                'gender' => $pet->gender,
                'birth_date' => $pet->birth_date?->format('Y-m-d'),
                'color' => $pet->color,
                'description' => $pet->description,
                'image' => $pet->image,
                'age' => $pet->age,
                'current_weight' => $pet->current_weight,
                'current_height' => $pet->current_height,
            ],
        ]);
    }
}
