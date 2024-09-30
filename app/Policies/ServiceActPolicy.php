<?php

namespace App\Policies;

use App\Models\ServiceAct;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ServiceActPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return ($user->can("სერვისის აქტის ნახვა") || $user->hasRole('director'));
    }

    public function view(User $user, ServiceAct $service_act)
    {
        return ($user->can("სერვისის აქტის ნახვა") || $user->hasRole('director'));
    }

    public function create(User $user)
    {
        return ($user->can("სერვისის აქტის შექმნა")) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    public function update(User $user, ServiceAct $service_act)
    {
        return ($user->can("სერვისის აქტის რედაქტირება") || $user->hasRole('director'));
    }

    public function delete(User $user, ServiceAct $service_act)
    {
        return (($user->can("სერვისის აქტის წაშლა") && $service_act->user->id == $user->id) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }
}
