<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
       return $user->type == "EM" || $user->type == "EC" || $user->type == "ED" || $user->type == "C";
    }

    public function update(User $user)
    {
        return $user->type == "EM" || $user->type == "EC";
    }
}
