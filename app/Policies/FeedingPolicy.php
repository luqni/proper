<?php

namespace App\Policies;

use App\Models\Feeding;
use App\Models\User;

class FeedingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Feeding $feeding): bool
    {
        return $user->farm_id === $feeding->farm_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Feeding $feeding): bool
    {
        return $user->farm_id === $feeding->farm_id;
    }
}
