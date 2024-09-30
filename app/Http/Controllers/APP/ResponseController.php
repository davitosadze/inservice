<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function arrived($id)
    {
        $response = Response::find($id);
        $response->time = Carbon::now();
        $response->status = 10;
        $response->save();
        return response()->json(["success" => true, "time" => Carbon::now()], 200);
    }
}
