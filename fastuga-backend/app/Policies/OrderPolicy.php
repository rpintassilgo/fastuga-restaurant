<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->type == "EM" || $user->type == "ED";
    }

    public function view(User $user)
    {
        $id = request()->route()->parameter('id');
        return (($user->type == "EM") || ($user->type == "C" && $user->id == $id)) || ($user->type == "ED"); // o customer autenticado apenas pode ver as suas orders, não as orders de outros customers
    }


    public function create(User $user)
    {
        return $user->type == "C";
    }

    public function update(User $user)
    {
        return $user->type != "C";
    }

    public function updateCancelOrder(User $user)
    {
        $id = request()->route()->parameter('id');
        return $user->type == "EM";
    }
}
