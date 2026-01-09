<?php

namespace App\Policies;

use App\Models\DeviceType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DeviceTypePolicy
{
    

    public function viewAny(User $user)
    {
        return ($user->can("მოწყობილობის ტიპის ნახვა") || $user->hasRole('director'));
    }

    public function view(User $user, DeviceType $device_type)
    {
        return ($user->can("მოწყობილობის ტიპის ნახვა") || $user->hasRole('director'));
    }

    public function create(User $user)
    {
        return ($user->can("მოწყობილობის ტიპის შექმნა")) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    public function update(User $user, DeviceType $device_type)
    {
        return (($user->can("მოწყობილობის ტიპის რედაქტირება") && $device_type->user->id == $user->id) || $user->hasRole('director'));
    }

    public function delete(User $user, DeviceType $device_type)
    {
        return (($user->can("მოწყობილობის ტიპის წაშლა")) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }
}
