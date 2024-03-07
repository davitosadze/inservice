<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Performer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PerformerPolicy
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
        return ($user->can("შემსრულებლის ნახვა") || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Performer  $performer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Performer $performer)
    {
        //
        return ($user->can("შემსრულებლის ნახვა") || $user->hasRole('director'));
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
        return ($user->can("შემსრულებლის შექმნა")) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Performer  $performer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Performer $performer)
    {
        //
        return (($user->can("შემსრულებლის რედაქტირება") && $performer->user->id == $user->id) || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Performer  $performer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Performer $performer)
    {
        //
        return (($user->can("შემსრულებლის წაშლა") && $performer->user->id == $user->id) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Performer  $performer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Performer $performer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Performer  $performer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Performer $performer)
    {
        //
    }
}
