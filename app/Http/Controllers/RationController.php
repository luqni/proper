<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rations = auth()->user()->farm->rations()
            ->latest()
            ->get();

        return Inertia::render('Rations/Index', [
            'rations' => $rations
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
            'weight_kg' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->farm->rations()->create($validated);

        return redirect()->route('rations.index')->with('success', 'Ration added successfully.');
    }

    public function update(Request $request, \App\Models\Ration $ration)
    {
        $this->authorize('update', $ration);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
            'weight_kg' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $ration->update($validated);

        return redirect()->route('rations.index')->with('success', 'Ration updated successfully.');
    }

    public function destroy(\App\Models\Ration $ration)
    {
        $this->authorize('delete', $ration);
        $ration->delete();

        return redirect()->route('rations.index')->with('success', 'Ration deleted successfully.');
    }
}
