<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthCheck;
use App\Models\Pet;
use App\Models\Reminder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Global search across all entities.
     *
     * GET /api/search
     * Query params: q (required), type (pets, reminders, health_checks, all), limit
     */
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $query = $request->q;
        $type = $request->get('type', 'all');
        $limit = $request->get('limit', 10);
        $user = $request->user();

        $results = [];

        // Search Pets
        if ($type === 'all' || $type === 'pets') {
            $pets = $user->pets()
                ->where(function ($q) use ($query) {
                    $q->where('pet_name', 'like', "%{$query}%")
                      ->orWhere('breed', 'like', "%{$query}%")
                      ->orWhere('species', 'like', "%{$query}%")
                      ->orWhere('color', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->limit($limit)
                ->get();

            $results['pets'] = $pets->map(function ($pet) {
                return [
                    'id' => $pet->id,
                    'pet_name' => $pet->pet_name,
                    'species' => $pet->species,
                    'breed' => $pet->breed,
                    'image_url' => $pet->image_url ? url($pet->image_url) : null,
                    'type' => 'pet',
                ];
            });
        }

        // Search Reminders
        if ($type === 'all' || $type === 'reminders') {
            $reminders = Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('category', 'like', "%{$query}%");
                })
                ->with('pet:id,pet_name')
                ->limit($limit)
                ->get();

            $results['reminders'] = $reminders->map(function ($reminder) {
                return [
                    'id' => $reminder->id,
                    'title' => $reminder->title,
                    'category' => $reminder->category,
                    'remind_date' => $reminder->remind_date->format('Y-m-d'),
                    'remind_time' => $reminder->remind_date->format('H:i'),
                    'status' => $reminder->status,
                    'pet_name' => $reminder->pet->pet_name,
                    'type' => 'reminder',
                ];
            });
        }

        // Search Health Checks
        if ($type === 'all' || $type === 'health_checks') {
            $healthChecks = HealthCheck::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
                ->where(function ($q) use ($query) {
                    $q->where('check_type', 'like', "%{$query}%")
                      ->orWhere('diagnosis', 'like', "%{$query}%")
                      ->orWhere('treatment', 'like', "%{$query}%")
                      ->orWhere('notes', 'like', "%{$query}%");
                })
                ->with('pet:id,pet_name')
                ->limit($limit)
                ->get();

            $results['health_checks'] = $healthChecks->map(function ($check) {
                return [
                    'id' => $check->id,
                    'check_type' => $check->check_type,
                    'check_date' => $check->check_date->format('Y-m-d'),
                    'diagnosis' => $check->diagnosis,
                    'pet_name' => $check->pet->pet_name,
                    'type' => 'health_check',
                ];
            });
        }

        // Calculate totals
        $totalResults = 0;
        foreach ($results as $category) {
            $totalResults += count($category);
        }

        return response()->json([
            'success' => true,
            'query' => $query,
            'total_results' => $totalResults,
            'data' => $results,
        ]);
    }

    /**
     * Quick search suggestions (for autocomplete).
     *
     * GET /api/search/suggestions
     * Query params: q (required)
     */
    public function suggestions(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1',
        ]);

        $query = $request->q;
        $user = $request->user();

        $suggestions = [];

        // Pet names
        $petNames = $user->pets()
            ->where('pet_name', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('pet_name')
            ->map(fn($name) => ['text' => $name, 'type' => 'pet']);

        // Reminder titles
        $reminderTitles = Reminder::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->where('title', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('title')
            ->map(fn($title) => ['text' => $title, 'type' => 'reminder']);

        $suggestions = $petNames->concat($reminderTitles)->unique('text')->take(10)->values();

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    }
}
