<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Purchaser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaserPolicy
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
        return ($user->can("მყიდველის ნახვა") || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Purchaser  $purchaser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Purchaser $purchaser)
    {
        //
        return ($user->can("მყიდველის ნახვა") || $user->hasRole('director'));
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
        return ($user->can("მყიდველის შექმნა") || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Purchaser  $purchaser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Purchaser $purchaser)
    {
        //
        return (($user->can("მყიდველის რედაქტირება")) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Purchaser  $purchaser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Purchaser $purchaser)
    {
        //
        return (($user->can("მყიდველის წაშლა")) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Purchaser  $purchaser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Purchaser $purchaser)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Purchaser  $purchaser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Purchaser $purchaser)
    {
        //
    }
}
