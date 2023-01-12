<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;


class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user)
    {
        return true;
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
}