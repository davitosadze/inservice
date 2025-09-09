<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Purchaser;
use App\Models\Response;
use App\Notifications\NewResponseNotification;
use App\Models\ServiceNotifiable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Validator;

class ResponseController extends Controller
{

    public function index() {
    
        $client = Auth::user()->getClient();
        $search = request()->get('search');

        $query = Response::with(['user', 'purchaser', 'region', 'performer'])
        ->orderBy('id', 'desc')
        ->where('status', '!=', 3)
        ->whereDate('created_at', '>=', Carbon::parse('2025-01-01'));

        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('subject_name', 'LIKE', "%{$search}%")
                  ->orWhere('subject_address', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }

        $allResponses = $query->get();
    
        $clientPurchasers = json_decode($client->purchaser, true) ?: [];
        
        $filtered = $allResponses->filter(function ($response) use ($clientPurchasers) {
            return in_array($response->formatted_name, $clientPurchasers);
        });
        
        // Manual pagination
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginated = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
            
        return response($paginated);


    }

    public function doneResponses(Request $request) {
        $client = Auth::user()->getClient();
        $search = request()->get('search');

        $query = Response::with(['user', 'purchaser', 'region', 'performer'])
            ->orderBy('id', 'desc')
            ->where('status', 3) 
            ->whereDate('created_at', '>=', Carbon::parse('first day of January'));

        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('subject_name', 'LIKE', "%{$search}%")
                  ->orWhere('subject_address', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }

        $allResponses = $query->get();
        
        $clientPurchasers = json_decode($client->purchaser, true) ?: [];
        
        $filtered = $allResponses->filter(function ($response) use ($clientPurchasers) {
            return in_array($response->formatted_name, $clientPurchasers);
        });
        
        // Manual pagination
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginated = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return response($paginated);
    }

    public function show($id)
    {

        $response = Response::with(['user', 'purchaser', 'region', 'performer', 'act'])->find($id);
        $purchaser = $response->purchaser;
         
        $lastService = $purchaser->services()->where('status', 3)->orderBy('id', 'desc')->first();
        // Get the previous response before the current one
        $lastResponse = $purchaser->responses()
            ->where('status', 3)
            ->where('id', '<', $response->id)
            ->orderBy('id', 'desc')
            ->first();
        $lastResponseDate = $lastResponse ? $lastResponse->created_at : null;   
        $lastServiceDate = $lastService ? $lastService->created_at : null;
        $additionalData = [
            'last_service_date' => $lastServiceDate,
            'last_response_date' => $lastResponseDate,
            'last_response_content' => $lastResponse ? $lastResponse->act?->note : null,
            'last_response_job_description' => $lastResponse ? $lastResponse->content : null,
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

 
            $user = $response->user;
            $serviceNotifiable = new ServiceNotifiable();
            $serviceNotifiable->notify(new NewResponseNotification($user,$response));
 
 

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

 
            $serviceNotifiable = new ServiceNotifiable();
            $serviceNotifiable->notify(new NewResponseNotification($user,$response));
 
    

        return response()->json([
            "success" => true,
            "message" => "Response created successfully"
        ], 200);
    }

    public function storeAsAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required_if:type,1|exists:purchasers,id',
            'subj_name' => 'required_if:type,2',
            'subj_address' => 'required_if:type,2',
            'description' => 'required|string|max:1000',
            'type' => 'required|in:1,2',
            'user_id' => 'required|exists:users,id',
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
            // For type 2, we need to create a generic purchaser since admin doesn't have a specific client
            $purchaser = Purchaser::create([
                "name" => "ადმინისგან შექმნილი",
                "subj_name" => $request->get('subj_name'),
                "subj_address" => $request->get('subj_address'),
                "identification_num" => "000000000",
                "single" => 1,
            ]);
        }

        $response = Response::create([
            "subject_name" => $purchaser->subj_name,
            "subject_address" => $purchaser->subj_address,
            "name" => $purchaser->name,
            "identification_num" => $purchaser->identification_num,
            "by_client" => 1,  // Keep client status as 1 as requested
            "purchaser_id" => $purchaser->id,
            "content" => $request->get('description'),
            "status" => 9,
            "user_id" => $request->get('user_id'),  // Selected user
            "type" => $type,
        ]);

        $user = auth()->user();

        $serviceNotifiable = new ServiceNotifiable();
        $serviceNotifiable->notify(new NewResponseNotification($user, $response));

        return response()->json([
            "success" => true,
            "message" => "Admin client order created successfully"
        ], 200);
    }
}
