<?php

namespace App\Http\Controllers;

use App\Models\Feeding;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeedingController extends Controller
{
    public function index()
    {
        $feedings = auth()->user()->farm->feedings()
            ->with(['animal', 'ration'])
            ->latest()
            ->get();
            
        $animals = auth()->user()->farm->animals()->get();
        $rations = auth()->user()->farm->rations()->get();

        return Inertia::render('Feedings/Index', [
            'feedings' => $feedings,
            'animals' => $animals,
            'rations' => $rations
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'ration_id' => 'nullable|exists:rations,id',
            'quantity' => 'required|numeric|min:0',
            'fed_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->farm->feedings()->create($validated);

        return redirect()->route('feedings.index')->with('success', 'Feeding record added successfully.');
    }

    public function destroy(Feeding $feeding)
    {
        $this->authorize('delete', $feeding);
        $feeding->delete();

        return redirect()->route('feedings.index')->with('success', 'Feeding record deleted successfully.');
    }
}
