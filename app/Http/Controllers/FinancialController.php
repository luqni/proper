<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Financial;
use Inertia\Inertia;

class FinancialController extends Controller
{
    public function index()
    {
        $finances = auth()->user()->farm->financials()
            ->latest()
            ->get();

        return Inertia::render('Finances/Index', [
            'finances' => $finances
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->farm->financials()->create($validated);

        return redirect()->route('finances.index')->with('success', 'Transaction recorded successfully.');
    }

    public function destroy(Financial $financial)
    {
        $this->authorize('delete', $financial);

        $financial->delete();

        return redirect()->route('finances.index')->with('success', 'Transaction deleted successfully.');
    }
}
