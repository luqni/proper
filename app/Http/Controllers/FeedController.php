<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FeedController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $feeds = auth()->user()->farm->feeds()
            ->with(['refills' => function($query) {
                $query->latest('refilled_at')->limit(1);
            }])
            ->latest()
            ->get();

        return Inertia::render('Feeds/Index', [
            'feeds' => $feeds,
        ]);
    }

    public function create()
    {
        return Inertia::render('Feeds/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'protein' => 'nullable|numeric|min:0',
            'fat' => 'nullable|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'tdn' => 'nullable|numeric|min:0',
            'dry_matter' => 'nullable|numeric|min:0',
            'price_per_kg' => 'nullable|numeric|min:0',
            'stock' => 'nullable|numeric|min:0',
        ]);

        auth()->user()->farm->feeds()->create($validated);

        return redirect()->route('feeds.index')->with('success', 'Feed ingredient added successfully.');
    }

    public function edit(Feed $feed)
    {
        $this->authorize('update', $feed);
        return Inertia::render('Feeds/Edit', [
            'feed' => $feed,
        ]);
    }

    public function update(Request $request, Feed $feed)
    {
        $this->authorize('update', $feed);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'protein' => 'nullable|numeric|min:0',
            'fat' => 'nullable|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'tdn' => 'nullable|numeric|min:0',
            'dry_matter' => 'nullable|numeric|min:0',
            'price_per_kg' => 'nullable|numeric|min:0',
            'stock' => 'nullable|numeric|min:0',
        ]);

        $feed->update($validated);

        return redirect()->route('feeds.index')->with('success', 'Feed ingredient updated successfully.');
    }

    public function refill(Request $request, Feed $feed)
    {
        $this->authorize('update', $feed);

        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'refilled_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $feed->refills()->create($validated);
        $feed->increment('stock', $validated['quantity']);

        return redirect()->back()->with('success', 'Stock refilled successfully.');
    }

    public function destroy(Feed $feed)
    {
        $this->authorize('delete', $feed);
        $feed->delete();

        return redirect()->route('feeds.index')->with('success', 'Feed ingredient deleted successfully.');
    }
}
