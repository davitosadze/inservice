<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\Chat;
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
    
        $client = Auth::user()->getClient();

        $responses = Response::with(['user', 'purchaser', 'region', 'performer'])
            ->orderBy('id', 'desc')
            ->whereDate('created_at', '>=', Carbon::parse('2025-01-01'))
            ->get()
            ->filter(function($response) use ($client) {
                return $response->formatted_name == $client->purchaser;
            });
        
        return response($responses->values()->toArray());


    }

    public function show($id)
    {

        $response = Response::with(['user', 'purchaser', 'region', 'performer', 'act'])->find($id);
        $purchaser = $response->purchaser;
         
        $lastService = $purchaser->services()->orderBy('id', 'desc')->first();
        $lastResponse = $purchaser->responses()->orderBy('id', 'desc')->first();
        $lastResponseDate = $lastResponse ? $lastResponse->created_at : null;   
        $lastServiceDate = $lastService ? $lastService->created_at : null;
        $additionalData = [
            'last_service_date' => $lastServiceDate,
            'last_response_date' => $lastResponseDate,
            'last_response_content' => $lastResponse ? $lastResponse->content : null,
            'last_response_job_description' => $lastResponse ? $lastResponse->job_description : null,
            'chat_id' => Chat::where('item_id', $response->id)->where('type', 'response')->first() ? Chat::where('item_id', $response->id)->where('type', 'response')->first()->id : null,
        ];

        if (!$response) {
            return response()->json(["success" => false, "message" => "Response not found"], 404);
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

    public function changeStatus(Request $request, Response $response) {
        $response->status = $request->get('status');
        $response->save();

        if($request->get('status') == 3) {
            $user = $response->user;
            // $user->notify(new NewResponseNotification($user,$response));
        }

        return response()->json(["success" => true], 200);
    }

    public function store(Request $request)
    {

 
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required_if:type,1|exists:purchasers,id',
            'subj_name' => 'required_if:type,2',
            'subj_address' => 'required_if:type,2',
            'description' => 'required|string|max:1000',
            'type' => 'required|in:1,2',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $type = $request->get('type');
        if($request->get('type') == 1) {
            $purchaser = Purchaser::find($request->get('branch_id'));

        } else {
            $purchaser = Purchaser::create([
                "name" => auth()->user()->getClient()->client_name,
                "subj_name" => $request->get('subj_name'),
                "subj_address" => $request->get('subj_address'),
                "identification_num" => auth()->user()->getClient()->client_identification_code,
                "signle" => 1,
            ]);
        }

        $response = Response::create([
            "subject_name" => $purchaser->subj_name,
            "subject_address" => $purchaser->subj_address,
            "name" => $purchaser->name,
            "identification_num" => $purchaser->identification_num,
            "by_client" => 1,
            "purchaser_id" => $purchaser->id,
            "content" => $request->get('description'),
            "status" => 9,
            "user_id" => Auth::user()->id,
            "type" => $type,

        ]);

        $user = auth()->user();
        // $user->notify(new NewResponseNotification($user,$response));
    

        return response()->json([
            "success" => true,
            "message" => "Response created successfully"
        ], 200);
    }
}
