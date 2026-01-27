<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthCheck;
use App\Models\Pet;
use Illuminate\Http\Request;

class HealthCheckController extends Controller
{
    /**
     * Display a listing of health checks.
     */
    public function index(Request $request)
    {
        $query = HealthCheck::with(['pet.user']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('complaint', 'like', "%{$search}%")
                  ->orWhere('diagnosis', 'like', "%{$search}%")
                  ->orWhereHas('pet', function ($q) use ($search) {
                      $q->where('pet_name', 'like', "%{$search}%");
                  });
            });
        }

        $healthChecks = $query->orderBy('check_date', 'desc')->paginate(15);

        $stats = [
            'total' => HealthCheck::count(),
            'thisMonth' => HealthCheck::whereMonth('check_date', now()->month)->count(),
            'needFollowUp' => HealthCheck::whereNotNull('next_visit_date')
                ->where('next_visit_date', '>=', today())
                ->count(),
            'overdueFollowUp' => HealthCheck::whereNotNull('next_visit_date')
                ->where('next_visit_date', '<', today())
                ->count(),
        ];

        return view('pages.admin.health-checks.index', compact('healthChecks', 'stats'));
    }

    /**
     * Show the form for creating a new health check.
     */
    public function create(Request $request)
    {
        $pets = Pet::with('user')->orderBy('pet_name')->get();
        $selectedPetId = $request->get('pet_id');

        return view('pages.admin.health-checks.create', compact('pets', 'selectedPetId'));
    }

    /**
     * Store a newly created health check.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'check_date' => 'required|date',
            'complaint' => 'nullable|string|max:200',
            'diagnosis' => 'nullable|string|max:200',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'next_visit_date' => 'nullable|date|after:check_date',
        ]);

        HealthCheck::create($validated);

        return redirect()->route('admin.health-checks.index')
            ->with('success', 'Health check record created successfully!');
    }

    /**
     * Display the specified health check.
     */
    public function show(HealthCheck $healthCheck)
    {
        $healthCheck->load('pet.user');
        return view('pages.admin.health-checks.show', compact('healthCheck'));
    }

    /**
     * Show the form for editing the specified health check.
     */
    public function edit(HealthCheck $healthCheck)
    {
        $healthCheck->load('pet.user');
        $pets = Pet::with('user')->orderBy('pet_name')->get();

        return view('pages.admin.health-checks.edit', compact('healthCheck', 'pets'));
    }

    /**
     * Update the specified health check.
     */
    public function update(Request $request, HealthCheck $healthCheck)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'check_date' => 'required|date',
            'complaint' => 'nullable|string|max:200',
            'diagnosis' => 'nullable|string|max:200',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'next_visit_date' => 'nullable|date|after:check_date',
        ]);

        $healthCheck->update($validated);

        return redirect()->route('admin.health-checks.index')
            ->with('success', 'Health check record updated successfully!');
    }

    /**
     * Remove the specified health check.
     */
    public function destroy(HealthCheck $healthCheck)
    {
        $healthCheck->delete();

        return redirect()->route('admin.health-checks.index')
            ->with('success', 'Health check record deleted successfully!');
    }
}
