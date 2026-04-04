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
        return Inertia::render('Animals/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'nullable|string|max:255',
            'name_or_tag' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'sex' => 'required|in:male,female,other',
            'birth_date' => 'nullable|date',
            'entry_date' => 'nullable|date',
            'weight' => 'nullable|numeric',
            'initial_weight' => 'nullable|numeric',
            'status' => 'required|in:active,sold,dead',
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
            'animal' => $animal->load('weights'),
            'qrCode' => $qrCode,
        ]);
    }

    public function edit(Animal $animal)
    {
        $this->authorize('update', $animal);

        return Inertia::render('Animals/Edit', [
            'animal' => $animal
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
            'sex' => 'required|in:male,female,other',
            'birth_date' => 'nullable|date',
            'entry_date' => 'nullable|date',
            'weight' => 'nullable|numeric',
            'status' => 'required|in:active,sold,dead',
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

    public function scanner()
    {
        return Inertia::render('Animals/Scanner');
    }
}
