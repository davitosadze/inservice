<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\Purchaser;
use App\Models\Response;
use App\Notifications\NewResponseNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ResponseController extends Controller
{

    public function index() {
        $responses = Response::with(['user', 'purchaser', 'region', 'performer'])->orderBy('id', 'desc')->where("user_id", Auth::user()->id)->get();
        return response($responses->toArray());

    }

    public function show($id)
    {
        $response = Response::with(['user', 'purchaser', 'region', 'performer'])->find($id);
        $purchaser = $response->purchaser;

        
        $lastService = $purchaser->services()->orderBy('id', 'desc')->first();
        $lastResponse = $purchaser->responses()->orderBy('id', 'desc')->first();
        $lastResponseDate = $lastResponse ? $lastResponse->created_at : null;   
        $lastServiceDate = $lastService ? $lastService->created_at : null;
        $additionalData = [
            'last_service_date' => $lastServiceDate,
            'last_response_date' => $lastResponseDate,
            'last_service_content' => $lastService ? $lastService->content : null,
            'last_service_job_description' => $lastService ? $lastService->job_description : null,
        ];

        if (!$response) {
            return response()->json(["success" => false, "message" => "Response not found"], 404);
        }
    
        if ($response->user_id !== auth()->id()) {
            return response()->json(["success" => false, "message" => "Unauthorized access"], 403);
        }
    
        return response()->json([
            'response' => $response,
            'additional_data' => $additionalData,
        ]);    
    }

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

        $response = Response::create([
            "subject_name" => $purchaser->subj_name,
            "subject_address" => $purchaser->subj_address,
            "name" => $purchaser->name,
            "identification_num" => $purchaser->identification_num,
            "by_client" => 1,
            "purchaser_id" => $purchaser->id,
            "content" => $request->get('description'),
            "status" => 9,
            "user_id" => Auth::user()->id
        ]);

        // $user = auth()->user();
        // $user->notify(new NewResponseNotification($user,$response));
    

        return response()->json([
            "success" => true,
            "message" => "Response created successfully"
        ], 200);
    }
}
