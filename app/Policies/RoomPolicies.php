<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RoomPolicies
{
    use HandlesAuthorization;

    public function show(?User $user, Room $room): Response
    {
        if (!$room->is_public) {
            return $this->deny('You don\'t have access to this room.');
        }

        return $this->allow();
    }
}
