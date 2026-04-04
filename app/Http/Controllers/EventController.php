<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $events = auth()->user()->farm->events()->latest()->get();
        return Inertia::render('Events/Index', ['events' => $events]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'type' => 'required|string|max:255',
        ]);

        auth()->user()->farm->events()->create($validated);

        return redirect()->route('events.index')->with('success', 'Event added successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
