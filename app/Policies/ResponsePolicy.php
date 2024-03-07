<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response as AccessResponse; // Renaming imported Response to avoid conflict

use App\Models\Response as ResponseModel; // Renaming imported Response class to avoid conflict
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResponsePolicy
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
        return ($user->can("რეაგირების ნახვა") || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ResponseModel $response)
    {
        //
        return ($user->can("რეაგირების ნახვა") || $user->hasRole('director'));
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
        return ($user->can("რეაგირების შექმნა")) ? AccessResponse::allow() : AccessResponse::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ResponseModel $response)
    {
        //
        return (($user->can("რეაგირების რედაქტირება") && $response->user->id == $user->id) || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ResponseModel $response)
    {
        //
        return (($user->can("რეაგირების წაშლა") && $response->user->id == $user->id) || $user->hasRole('director')) ? AccessResponse::allow() : AccessResponse::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ResponseModel $response)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ResponseModel $response)
    {
        //
    }
}
