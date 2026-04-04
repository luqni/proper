<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Farm;
use App\Models\Animal;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Farm Owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
        ]);

        $farm = Farm::create([
            'user_id' => $user->id,
            'name' => 'Sunny Side Farm',
            'location' => 'Spring Valley',
        ]);

        Animal::create([
            'farm_id' => $farm->id,
            'name_or_tag' => 'Bessie',
            'species' => 'Cow',
            'breed' => 'Holstein',
            'sex' => 'female',
            'birth_date' => '2021-05-10',
            'weight' => 600,
            'status' => 'active',
        ]);

        Animal::create([
            'farm_id' => $farm->id,
            'name_or_tag' => 'TAG-101',
            'species' => 'Sheep',
            'sex' => 'male',
            'birth_date' => '2023-01-15',
            'weight' => 50,
            'status' => 'active',
        ]);

        Task::create([
            'farm_id' => $farm->id,
            'assigned_to' => $user->id,
            'title' => 'Vaccinate Sheep',
            'description' => 'Administer annual vaccines to the sheep flock.',
            'due_date' => now()->addDays(2),
            'status' => 'pending',
        ]);
    }
}
