<?php

namespace App\Policies;

use App\Models\HealthRecord;
use App\Models\User;

class HealthRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, HealthRecord $healthRecord): bool
    {
        return $user->farm_id === $healthRecord->farm_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, HealthRecord $healthRecord): bool
    {
        return $user->farm_id === $healthRecord->farm_id;
    }
}
