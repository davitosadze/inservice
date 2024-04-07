<?php

namespace App\Policies;

use App\Models\DeviceBrand;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DeviceBrandPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return ($user->can("მოწყობილობის ბრენდის ნახვა") || $user->hasRole('director'));
    }

    public function view(User $user, DeviceBrand $device_brand)
    {
        return ($user->can("მოწყობილობის ბრენდის ნახვა") || $user->hasRole('director'));
    }

    public function create(User $user)
    {
        return ($user->can("მოწყობილობის ბრენდის შექმნა")) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }

    public function update(User $user, DeviceBrand $device_brand)
    {
        return (($user->can("მოწყობილობის ბრენდის რედაქტირება") && $device_brand->user->id == $user->id) || $user->hasRole('director'));
    }

    public function delete(User $user, DeviceBrand $device_brand)
    {
        return (($user->can("მოწყობილობის ბრენდის წაშლა") && $device_brand->user->id == $user->id) || $user->hasRole('director')) ? Response::allow() : Response::deny('არ გაქვთ ნებართვა!');
    }
}
