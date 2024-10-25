<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\User;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpoNotificationController extends Controller
{
    public function subscribe(Request $request)
    {

        $expo = Expo::driver('file');
        auth()->user()->expo_token = $request->get('expo_token');
        auth()->user()->save();

        $recipients = [
            $request->get('token')
        ];

        $channel = 'inservice-notifications';

        $expo->subscribe($channel, $recipients);

        return response()->json(["success" => true, "message" => "Subscribed Successfully"], 200);
    }
}
