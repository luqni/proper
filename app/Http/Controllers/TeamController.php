<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $team = auth()->user()->farm->users()
            ->select('id', 'name', 'email', 'role', 'created_at')
            ->latest()
            ->get();

        return Inertia::render('Teams/Index', [
            'team' => $team
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'owner') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:owner,staff',
        ]);

        $user->farm->users()->create([
            ...$validated,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('teams.index')->with('success', 'Team member added successfully.');
    }

    public function destroy(User $member)
    {
        $user = auth()->user();
        if ($user->role !== 'owner' || $member->id === $user->id) {
            abort(403);
        }

        if ($member->farm_id !== $user->farm_id) {
            abort(403);
        }

        $member->delete();

        return redirect()->route('teams.index')->with('success', 'Team member removed.');
    }
}
