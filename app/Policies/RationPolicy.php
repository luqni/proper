<?php

namespace App\Policies;

use App\Models\Ration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ration $ration): bool
    {
        return $user->farm_id === $ration->farm_id;
    }

    public function create(User $user): bool
    {
        return $user->farm_id !== null;
    }

    public function update(User $user, Ration $ration): bool
    {
        return $user->farm_id === $ration->farm_id;
    }

    public function delete(User $user, Ration $ration): bool
    {
        return $user->farm_id === $ration->farm_id;
    }

    public function restore(User $user, Ration $ration): bool
    {
        return $user->farm->id === $ration->farm_id;
    }

    public function forceDelete(User $user, Ration $ration): bool
    {
        return $user->farm->id === $ration->farm_id;
    }
}
