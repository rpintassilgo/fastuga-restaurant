<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->type == "EM"; // costumer também?
    }

    public function view(User $user, OrderItem $orderItem)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, OrderItem $orderItem)
    {
        //
    }

    public function delete(User $user, OrderItem $orderItem)
    {
        //
    }

    public function restore(User $user, OrderItem $orderItem)
    {
        //
    }

    public function forceDelete(User $user, OrderItem $orderItem)
    {
        //
    }
}
