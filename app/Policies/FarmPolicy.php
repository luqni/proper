<?php

namespace App\Policies;

use App\Models\Farm;
use App\Models\User;

class FarmPolicy
{
    public function view(User $user, Farm $farm): bool
    {
        return $user->farm_id === $farm->id;
    }

    public function update(User $user, Farm $farm): bool
    {
        return $user->role === 'owner' && $user->farm_id === $farm->id;
    }

    public function delete(User $user, Farm $farm): bool
    {
        return $user->role === 'owner' && $user->farm_id === $farm->id;
    }
}
