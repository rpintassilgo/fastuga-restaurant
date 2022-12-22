<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;


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
        return $user->type == "EM" || $user->id == $id; // user autenticado só pode ver o seu email e não o email dos outros users
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user)
    {
        $id = request()->route()->parameter('id');
        return $user->type == "EM" || $user->id == $id; // user apenas pode dar update ao seu perfil
    }

    public function delete(User $user)
    {
        return $user->type == "EM";
    }

    public function showMe()
    {
        return Auth::check();
    }

    public function blockOrUnblockUser(User $user)
    {
        return $user->type == "EM";
    }
}
