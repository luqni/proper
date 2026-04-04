<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HealthController extends Controller
{
    public function index()
    {
        $records = auth()->user()->farm->healthRecords()
            ->with('animal')
            ->latest()
            ->get();
            
        $animals = auth()->user()->farm->animals()->get();

        return Inertia::render('Health/Index', [
            'records' => $records,
            'animals' => $animals
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->farm->healthRecords()->create($validated);

        return redirect()->route('health.index')->with('success', 'Health record added successfully.');
    }

    public function destroy(HealthRecord $health)
    {
        $this->authorize('delete', $health);
        $health->delete();

        return redirect()->route('health.index')->with('success', 'Health record deleted successfully.');
    }
}
