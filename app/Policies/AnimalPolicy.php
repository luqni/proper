<?php

namespace App\Policies;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnimalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Animal $animal): bool
    {
        return $user->farm_id === $animal->farm_id;
    }

    public function create(User $user): bool
    {
        return $user->farm_id !== null;
    }

    public function update(User $user, Animal $animal): bool
    {
        return $user->farm_id === $animal->farm_id;
    }

    public function delete(User $user, Animal $animal): bool
    {
        return $user->farm_id === $animal->farm_id;
    }

    public function restore(User $user, Animal $animal): bool
    {
        return $user->farm_id === $animal->farm_id;
    }

    public function forceDelete(User $user, Animal $animal): bool
    {
        return $user->farm_id === $animal->farm_id;
    }
}
