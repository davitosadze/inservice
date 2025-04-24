<?php

namespace App\Policies;

use App\Models\RepairAct;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RepairActPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return ($user->can("რემონტის აქტის ნახვა") || $user->hasRole('director'));
    }

    public function view(User $user, RepairAct $repair_act)
    {
        return ($user->can("რემონტის აქტის ნახვა") || $user->hasRole('director'));
    }

    public function create(User $user)
    {
        return ($user->can("რემონტის აქტის შექმნა")) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    public function update(User $user, RepairAct $repair_act)
    {
        return ($user->can("რემონტის აქტის რედაქტირება") || $user->hasRole('director'));
    }

    public function delete(User $user, RepairAct $repair_act)
    {
        return (($user->can("რემონტის აქტის წაშლა") && $repair_act->user->id == $user->id) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }
}
