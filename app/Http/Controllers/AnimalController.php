<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AnimalController extends Controller
{
    public function index()
    {
        $animals = auth()->user()->farm->animals()
            ->with('location')
            ->latest()
            ->get();

        $grouped = $animals->groupBy('species');
        $speciesList = $animals->pluck('species')->unique()->values();

        return Inertia::render('Animals/Index', [
            'groupedAnimals' => $grouped,
            'speciesList' => $speciesList,
            'totalCount' => $animals->count()
        ]);
    }

    public function create()
    {
        $animals = auth()->user()->farm->animals()->orderBy('name_or_tag')->get();
        $locations = auth()->user()->farm->locations()->orderBy('name')->get();
        return Inertia::render('Animals/Create', [
            'animals' => $animals,
            'locations' => $locations
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'nullable|string|max:255',
            'name_or_tag' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'purpose' => 'required|in:breeding,fattening,milking,other',
            'sire_id' => 'nullable|exists:animals,id',
            'dam_id' => 'nullable|exists:animals,id',
            'sex' => 'required|in:male,female,other',
            'birth_date' => 'nullable|date',
            'entry_date' => 'nullable|date',
            'weight' => 'nullable|numeric',
            'initial_weight' => 'nullable|numeric',
            'status' => 'required|in:active,sold,dead',
            'location_id' => 'nullable|exists:locations,id',
            'condition_notes' => 'nullable|string',
        ]);

        $animal = auth()->user()->farm->animals()->create($validated);

        // Record initial weight if provided
        if ($request->weight || $request->initial_weight) {
            $animal->weights()->create([
                'weight' => $request->weight ?? $request->initial_weight,
                'measured_at' => $request->entry_date ?? now(),
                'notes' => 'Initial weight recording'
            ]);
        }

        return redirect()->route('animals.index')->with('success', 'Animal added successfully.');
    }

    public function show(Animal $animal)
    {
        $this->authorize('view', $animal);

        $qrCode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate(route('animals.show', $animal->id)));

        return Inertia::render('Animals/Show', [
            'animal' => $animal->load(['weights', 'sire', 'dam', 'offspring', 'productions' => function($q) {
                $q->orderBy('date', 'desc')->take(10);
            }]),
            'qrCode' => $qrCode,
        ]);
    }

    public function edit(Animal $animal)
    {
        $this->authorize('update', $animal);
        $animals = auth()->user()->farm->animals()->where('id', '!=', $animal->id)->orderBy('name_or_tag')->get();
        $locations = auth()->user()->farm->locations()->orderBy('name')->get();

        return Inertia::render('Animals/Edit', [
            'animal' => $animal,
            'animals' => $animals,
            'locations' => $locations
        ]);
    }

    public function update(Request $request, Animal $animal)
    {
        $this->authorize('update', $animal);

        $validated = $request->validate([
            'registration_number' => 'nullable|string|max:255',
            'name_or_tag' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'purpose' => 'required|in:breeding,fattening,milking,other',
            'sire_id' => 'nullable|exists:animals,id',
            'dam_id' => 'nullable|exists:animals,id',
            'sex' => 'required|in:male,female,other',
            'birth_date' => 'nullable|date',
            'entry_date' => 'nullable|date',
            'weight' => 'nullable|numeric',
            'status' => 'required|in:active,sold,dead',
            'location_id' => 'nullable|exists:locations,id',
            'condition_notes' => 'nullable|string',
        ]);

        $oldWeight = $animal->weight;
        $animal->update($validated);

        // If weight updated, record history
        if ($request->weight && $request->weight != $oldWeight) {
            $animal->weights()->create([
                'weight' => $request->weight,
                'measured_at' => now(),
                'notes' => 'Updated from main record'
            ]);
        }

        return redirect()->route('animals.show', $animal)->with('success', 'Animal updated successfully.');
    }

    public function destroy(Animal $animal)
    {
        $this->authorize('delete', $animal);
        $animal->delete();

        return redirect()->route('animals.index')->with('success', 'Animal deleted successfully.');
    }

    public function storeWeight(Request $request, Animal $animal)
    {
        $this->authorize('update', $animal);

        $validated = $request->validate([
            'weight' => 'required|numeric',
            'measured_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $animal->weights()->create($validated);
        $animal->update(['weight' => $request->weight]);

        return back()->with('success', 'Weight recorded successfully.');
    }

    public function storeProduction(Request $request, Animal $animal)
    {
        $this->authorize('update', $animal);

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'date' => 'required|date',
        ]);

        $animal->productions()->create(array_merge($validated, [
            'farm_id' => auth()->user()->farm_id,
        ]));

        return back()->with('success', 'Production recorded successfully.');
    }

    public function scanner()
    {
        return Inertia::render('Animals/Scanner');
    }

    public function checkInbreeding(Request $request)
    {
        $sireId = $request->query('sire_id');
        $damId = $request->query('dam_id');
        
        $risk = Animal::checkInbreeding($sireId, $damId);
        
        return response()->json(['risk' => $risk]);
    }
}
