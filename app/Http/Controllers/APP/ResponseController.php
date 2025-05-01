<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\Purchaser;
use App\Models\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:purchasers,id',
            'description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $purchaser = Purchaser::find($request->get('branch_id'));

        Response::create([
            "subject_name" => $purchaser->subj_name,
            "subject_address" => $purchaser->subj_address,
            "name" => $purchaser->name,
            "identification_num" => $purchaser->identification_num,
        
            "purchaser_id" => $purchaser->id,
            "job_description" => $request->get('description'),
            "status" => 9,
            "user_id" => Auth::user()->id
        ]);

        return response()->json([
            "success" => true,
            "message" => "Response created successfully"
        ], 200);
    }
}
