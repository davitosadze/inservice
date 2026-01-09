<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LocationPolicy
{
    

    public function viewAny(User $user)
    {
        return ($user->can("ლოკაციის ნახვა") || $user->hasRole('director'));
    }

    public function view(User $user, Location $location)
    {
        return ($user->can("ლოკაციის ნახვა") || $user->hasRole('director'));
    }

    public function create(User $user)
    {
        return ($user->can("ლოკაციის შექმნა")) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    public function update(User $user, Location $location)
    {
        return (($user->can("ლოკაციის რედაქტირება") && $location->user->id == $user->id) || $user->hasRole('director'));
    }

    public function delete(User $user, Location $location)
    {
        return  Response::allow();
    }
}
