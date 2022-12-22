<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->type == "EM" || $user->type == "EC" || $user->type == "ED" || $user->type == "C";
    }

    public function view(User $user)
    {
        return $user->type == "EM";
    }

    public function create(User $user)
    {
        return $user->type == "EM";
    }

    public function update(User $user)
    {
        return $user->type == "EM";
    }

    public function delete(User $user)
    {
        return $user->type == "EM";
    }

    // public function restore(User $user, Product $product)
    // {
    //     //
    // }

    // public function forceDelete(User $user, Product $product)
    // {
    //     //
    // }
}
