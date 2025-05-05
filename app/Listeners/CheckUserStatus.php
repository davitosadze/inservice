<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CheckUserStatus
{
    public function handle(Attempting $event)
    {
        $credentials = $event->credentials;
        
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if ($user && $user->status === 0) {
            throw ValidationException::withMessages([
                'email' => ['თქვენი ანგარიში დეაქტივირებულია'],
            ]);
        }
    }
}