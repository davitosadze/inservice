<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

use App\Models\System;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemPolicy
{
    

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
        return ($user->can("სისტემის ნახვა") || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\System  $system
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, System $system)
    {
        return ($user->can("სისტემის ნახვა") || $user->hasRole('director'));
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
        return ($user->can("სისტემის შექმნა")) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\System  $system
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, System $system)
    {
        //
        return ($user->can("სისტემის რედაქტირება") || $user->hasRole('director')) ? Response::allow() : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\System  $system
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, System $system)
    {
        //
        return ($user->can("სისტემის წაშლა") || $user->hasRole('director')) ? Response::allow() : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\System  $system
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, System $system)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\System  $system
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, System $system)
    {
        //
    }
}
