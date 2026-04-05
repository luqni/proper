<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $farm = $user->farm;

        if (!$farm) {
            $farm = Farm::where('user_id', $user->id)->first();
            
            if (!$farm) {
                $farm = Farm::create([
                    'user_id' => $user->id,
                    'name' => $user->name . "'s Farm",
                ]);
            }
            
            $user->update([
                'farm_id' => $farm->id,
                'role' => 'owner'
            ]);
        }

        $stats = [
            'animals_count' => $farm->animals()->count(),
            'locations_count' => $farm->locations()->count(),
            'tasks_active_count' => $farm->tasks()->where('status', 'pending')->count(),
            'tasks_overdue_count' => $farm->tasks()->where('status', 'pending')->where('due_date', '<', Carbon::today())->count(),
            'notes_count' => $farm->notes()->count(),
            'events_today_count' => $farm->events()->whereDate('start_time', Carbon::today())->count(),
            'feedings_count' => $farm->feedings()->count(),
            'feeds_count' => $farm->feeds()->count(),
            'health_records_count' => $farm->healthRecords()->count(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'farm' => $farm->load('users')
        ]);
    }
}
