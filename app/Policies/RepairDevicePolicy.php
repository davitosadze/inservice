<?php

namespace App\Policies;

use App\Models\RepairDevice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RepairDevicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return ($user->can("რემონტის მოწყობილობის ნახვა") || $user->hasRole('director'));
    }

    public function view(User $user, RepairDevice $repair_device)
    {
        return ($user->can("რემონტის მოწყობილობის ნახვა") || $user->hasRole('director'));
    }

    public function create(User $user)
    {
        return ($user->can("რემონტის მოწყობილობის შექმნა")) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    public function update(User $user, RepairDevice $repair_device)
    {
        return (($user->can("რემონტის მოწყობილობის რედაქტირება") && $repair_device->user->id == $user->id) || $user->hasRole('director'));
    }

    public function delete(User $user, RepairDevice $repair_device)
    {
        return  Response::allow();
    }
}
