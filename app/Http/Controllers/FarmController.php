<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FarmController extends Controller
{
    public function update(Request $request, Farm $farm)
    {
        $user = auth()->user();
        if ($farm->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $farm->update($validated);

        return back()->with('success', 'Farm settings updated.');
    }

    public function updateCover(Request $request, Farm $farm)
    {
        if (auth()->user()->role !== 'owner' || auth()->user()->farm_id !== $farm->id) {
            abort(403);
        }

        $request->validate([
            'cover_photo' => 'required|image|max:2048', // 2MB max
        ]);

        if ($farm->cover_photo) {
            Storage::disk('public')->delete($farm->cover_photo);
        }

        $path = $request->file('cover_photo')->store('farm_covers', 'public');
        $farm->update(['cover_photo' => $path]);

        return back()->with('success', 'Cover photo updated.');
    }
}
