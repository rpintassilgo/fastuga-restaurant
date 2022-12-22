<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->type == "EM";

    }

    public function view(User $user)
    {
        $id = request()->route()->parameter('id');
        return $user->type == "EM" || $user->id == $id;
    }

    public function update(User $user)
    {
        $id = request()->route()->parameter('id');
        return $user->type == "EM" || $user->id == $id;
    }

    public function delete(User $user)
    {
        return $user->type == "EM";
    }

    // fazer função block_Customer
}
