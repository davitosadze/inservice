<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function arrived($id)
    {
        $response = Service::find($id);
        $response->time = Carbon::now();
        $response->status = 10;
        $response->save();
        return response()->json(["success" => true, "time" => Carbon::now()], 200);
    }
}
