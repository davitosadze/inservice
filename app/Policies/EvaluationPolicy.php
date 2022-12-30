<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EvaluationPolicy
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
        return ($user->can("განფასების ნახვა") || $user->hasRole('director'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Evaluation $evaluation)
    {
        //
        return ($user->can("განფასების ნახვა") || $user->hasRole('director'));
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
        return ($user->can("განფასების შექმნა") || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Evaluation $evaluation)
    {
        //
        return (($user->can("განფასების რედაქტირება") && $evaluation->user->id == $user->id) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Evaluation $evaluation)
    {
        //
        return (($user->can("განფასების წაშლა") && $evaluation->user->id == $user->id) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Evaluation $evaluation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Evaluation $evaluation)
    {
        //
    }
}
