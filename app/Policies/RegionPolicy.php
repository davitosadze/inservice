<?php

namespace App\Policies;

use App\Models\Region;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
        return ($user->can("რეგიონის ნახვა") || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Region $region)
    {
        //
        return ($user->can("რეგიონის ნახვა") || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
        return ($user->can("რეგიონის შექმნა")) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Region $region)
    {
        //
        return (($user->can("რეგიონის რედაქტირება") && $region->user->id == $user->id) || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Region $region)
    {
        //
        return (($user->can("რეგიონის წაშლა")) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Region $region)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Region $region)
    {
        //
    }
}
