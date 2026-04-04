<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Event $event): bool
    {
        return $user->farm_id === $event->farm_id;
    }

    public function create(User $user): bool
    {
        return $user->farm_id !== null;
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->farm_id === $event->farm_id;
    }
}
