<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

use Illuminate\Auth\Access\Response;

use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
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
        return ($user->can("კლიენტის ნახვა") || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Client $client)
    {
        //
        return ($user->can("კლიენტის ნახვა") || $user->hasRole('director'));
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
        return ($user->can("კლიენტის შექმნა") || $user->hasRole('director')) ? Response::allow() : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Client $client)
    {
        //
        return ($user->can("კლიენტის რედაქტირება") || $user->hasRole('director')) ? Response::allow() : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Client $client)
    {
        //
        return ($user->can("კლიენტის წაშლა") || $user->hasRole('director')) ? Response::allow() : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Client $client)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Client $client)
    {
        //
    }
}
