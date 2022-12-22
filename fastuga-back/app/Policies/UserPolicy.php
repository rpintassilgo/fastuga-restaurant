<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function create(User $user)
    {
        return true;
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

    // public function restore(User $user, User $model)
    // {
    //     //
    // }

    // public function forceDelete(User $user, User $model)
    // {
    //     //
    // }


    public function showMe()
    {
        return Auth::check();
    }

    public function blockOrUnblockUser(User $user)
    {
        return $user->type == "EM";
    }
}
