<?php

namespace App\Policies;

use App\Models\Act;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ActPolicy
{
    

    public function viewAny(User $user)
    {
        return ($user->can("აქტის ნახვა") || $user->hasRole('director'));
    }

    public function view(User $user, Act $act)
    {
        return ($user->can("აქტის ნახვა") || $user->hasRole('director'));
    }

    public function create(User $user)
    {
        return ($user->can("აქტის შექმნა")) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    public function update(User $user, Act $act)
    {
        return ($user->can("აქტის რედაქტირება") || $user->hasRole('director'));
    }

    public function delete(User $user, Act $act)
    {
        return (($user->can("აქტის წაშლა") && $act->user->id == $user->id) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }
}
