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
            ->with(['animal', 'ration', 'feed'])
            ->latest()
            ->get();
            
        $animals = auth()->user()->farm->animals()->get();
        $rations = auth()->user()->farm->rations()->get();
        $feeds = auth()->user()->farm->feeds()->get();

        return Inertia::render('Feedings/Index', [
            'feedings' => $feedings,
            'animals' => $animals,
            'rations' => $rations,
            'feeds' => $feeds,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'ration_id' => 'nullable|exists:rations,id',
            'feed_id' => 'nullable|exists:feeds,id',
            'quantity' => 'required|numeric|min:0',
            'fed_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $feeding = auth()->user()->farm->feedings()->create($validated);

        // Deduct stock
        if ($feeding->feed_id) {
            $feeding->feed->decrement('stock', $feeding->quantity);
        } elseif ($feeding->ration_id) {
            $feeding->ration->decrement('weight_kg', $feeding->quantity);
        }

        return redirect()->route('feedings.index')->with('success', 'Feeding record added successfully.');
    }

    public function destroy(Feeding $feeding)
    {
        $this->authorize('delete', $feeding);

        // Restore stock
        if ($feeding->feed_id) {
            $feeding->feed->increment('stock', $feeding->quantity);
        } elseif ($feeding->ration_id) {
            $feeding->ration->increment('weight_kg', $feeding->quantity);
        }

        $feeding->delete();

        return redirect()->route('feedings.index')->with('success', 'Feeding record deleted successfully.');
    }
}
