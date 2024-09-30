<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Http\Requests\APP\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->get('email'))
            ->first();


        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return response(['error_message' => 'Incorrect Credentials, Try Again'], 403);
        }

        $token = $user->createToken('Api Token', ['resource:customer'])->plainTextToken;
        return response(['user' => $user, 'token' => $token], 200);
    }
}
