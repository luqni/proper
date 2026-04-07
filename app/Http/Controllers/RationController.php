<?php

namespace App\Http\Controllers;

use App\Models\Ration;
use App\Models\Feed;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rations = auth()->user()->farm->rations()
            ->with('feeds')
            ->latest()
            ->get();

        $feeds = auth()->user()->farm->feeds()->get();

        return Inertia::render('Rations/Index', [
            'rations' => $rations,
            'feeds' => $feeds
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
            'weight_kg' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'ingredients' => 'nullable|array',
            'ingredients.*.feed_id' => 'required|exists:feeds,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
        ]);

        $ration = auth()->user()->farm->rations()->create([
            'name' => $validated['name'],
            'price_per_kg' => $validated['price_per_kg'],
            'weight_kg' => $validated['weight_kg'],
            'notes' => $validated['notes'],
        ]);

        if (!empty($validated['ingredients'])) {
            $syncData = [];
            foreach ($validated['ingredients'] as $ingredient) {
                $syncData[$ingredient['feed_id']] = ['quantity' => $ingredient['quantity']];
            }
            $ration->feeds()->sync($syncData);
        }

        return redirect()->route('rations.index')->with('success', 'Ration added successfully.');
    }

    public function update(Request $request, Ration $ration)
    {
        $this->authorize('update', $ration);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
            'weight_kg' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'ingredients' => 'nullable|array',
            'ingredients.*.feed_id' => 'required|exists:feeds,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
        ]);

        $ration->update([
            'name' => $validated['name'],
            'price_per_kg' => $validated['price_per_kg'],
            'weight_kg' => $validated['weight_kg'],
            'notes' => $validated['notes'],
        ]);

        if (isset($validated['ingredients'])) {
            $syncData = [];
            foreach ($validated['ingredients'] as $ingredient) {
                $syncData[$ingredient['feed_id']] = ['quantity' => $ingredient['quantity']];
            }
            $ration->feeds()->sync($syncData);
        }

        return redirect()->route('rations.index')->with('success', 'Ration updated successfully.');
    }

    public function mix(Request $request, Ration $ration)
    {
        $this->authorize('update', $ration);

        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $multiplier = $validated['quantity']; // This is how many "units" of the recipe we are mixing
        // Actually, let's treat the ingredients.quantity as the exact amount for a 1kg mix, or just a ratio?
        // Let's assume the recipe total weight is the "batch unit".
        
        $recipeTotalWeight = $ration->feeds->sum('pivot.quantity');
        if ($recipeTotalWeight <= 0) {
            return back()->with('error', 'Recipe has no ingredients or total weight is zero.');
        }

        $scalingFactor = $validated['quantity'] / $recipeTotalWeight;

        DB::transaction(function () use ($ration, $multiplier, $scalingFactor, $validated) {
            foreach ($ration->feeds as $feed) {
                $requiredAmount = $feed->pivot->quantity * $scalingFactor;
                $feed->decrement('stock', $requiredAmount);
            }

            $ration->increment('weight_kg', $validated['quantity']);
        });

        return redirect()->route('rations.index')->with('success', 'Batch mixed successfully.');
    }

    public function destroy(Ration $ration)
    {
        $this->authorize('delete', $ration);
        $ration->delete();

        return redirect()->route('rations.index')->with('success', 'Ration deleted successfully.');
    }
}
