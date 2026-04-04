<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function index()
    {
        $locations = auth()->user()->farm->locations()->latest()->get();
        return Inertia::render('Locations/Index', ['locations' => $locations]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'area_size' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->farm->locations()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Location added successfully.');
    }

    public function update(Request $request, Location $location)
    {
        $this->authorize('update', $location);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'area_size' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $this->authorize('delete', $location);
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }
}
