<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;

class PetController extends Controller
{
    /**
     * Display a listing of pets.
     */
    public function index(Request $request)
    {
        $query = Pet::with('user');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('pet_name', 'like', "%{$search}%")
                  ->orWhere('species', 'like', "%{$search}%")
                  ->orWhere('breed', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by species
        if ($species = $request->get('species')) {
            $query->where('species', $species);
        }

        $pets = $query->latest()->paginate(15);

        $stats = [
            'total' => Pet::count(),
            'cats' => Pet::where('species', 'Cat')->count(),
            'dogs' => Pet::where('species', 'Dog')->count(),
            'others' => Pet::whereNotIn('species', ['Cat', 'Dog'])->count(),
        ];

        return view('pages.admin.pets.index', compact('pets', 'stats'));
    }

    /**
     * Display the specified pet.
     */
    public function show(Pet $pet)
    {
        $pet->load(['user', 'growthRecords', 'reminders', 'healthChecks', 'appointments']);
        
        $petStats = [
            'remindersCount' => $pet->reminders->count(),
            'healthChecksCount' => $pet->healthChecks->count(),
            'growthRecordsCount' => $pet->growthRecords->count(),
            'appointmentsCount' => $pet->appointments->count(),
        ];

        return view('pages.admin.pets.show', compact('pet', 'petStats'));
    }

    /**
     * Show the form for creating a new pet.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('pages.admin.pets.create', compact('users'));
    }

    /**
     * Store a newly created pet.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pet_name' => 'required|string|max:100',
            'species' => 'required|string|max:50',
            'breed' => 'nullable|string|max:50',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|before_or_equal:today',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        Pet::create($validated);

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet created successfully!');
    }

    /**
     * Show the form for editing the specified pet.
     */
    public function edit(Pet $pet)
    {
        $pet->load(['user', 'growthRecords', 'reminders', 'healthChecks']);
        $users = User::orderBy('name')->get();

        $petStats = [
            'remindersCount' => $pet->reminders->count(),
            'healthChecksCount' => $pet->healthChecks->count(),
            'growthRecordsCount' => $pet->growthRecords->count(),
        ];

        return view('pages.admin.pets.edit', compact('pet', 'users', 'petStats'));
    }

    /**
     * Update the specified pet.
     */
    public function update(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pet_name' => 'required|string|max:100',
            'species' => 'required|string|max:50',
            'breed' => 'nullable|string|max:50',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|before_or_equal:today',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $pet->update($validated);

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet updated successfully!');
    }

    /**
     * Remove the specified pet.
     */
    public function destroy(Pet $pet)
    {
        $pet->delete();

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet deleted successfully!');
    }
}
