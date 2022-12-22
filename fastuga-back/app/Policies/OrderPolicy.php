<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->type == "EM";
    }

    public function view(User $user/*, Order $order*/)
    {
        $id = request()->route()->parameter('id');
        return $user->type == "EM" || $user->id == $id;
    }


    public function create(User $user)
    {
        return $user->type == "C";
    }

    public function update(User $user/*, Order $order*/)
    {
        return $user->type == "ED";
    }

    public function delete(User $user, Order $order)
    {
        //
    }

    // public function restore(User $user, Order $order)
    // {
    //     //
    // }

    // public function forceDelete(User $user, Order $order)
    // {
    //     //
    // }

    public function updateCancelOrder(User $user/*, Order $order*/)
    {
        return $user->type == "EM";
    }
}
