<?php

namespace App\Policies;

use App\Models\Financial;
use App\Models\User;

class FinancialPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Financial $financial): bool
    {
        return $user->farm_id === $financial->farm_id;
    }

    public function create(User $user): bool
    {
        return $user->farm_id !== null;
    }

    public function delete(User $user, Financial $financial): bool
    {
        return $user->farm_id === $financial->farm_id;
    }
}
