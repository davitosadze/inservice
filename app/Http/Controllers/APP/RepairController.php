<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Purchaser;
use App\Models\Repair;
use App\Notifications\NewRepairNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Validator;
use Illuminate\Support\Facades\Auth;

class RepairController extends Controller
{

    public function index(Request $request)
    {
        $client = Auth::user()->getClient();

        $allRepairs = Repair::with(['user', 'purchaser', 'region', 'performer', 'response'])
            ->orderBy('id', 'desc')
            ->where('status', '!=', 3)
            ->whereDate('created_at', '>=', Carbon::parse('first day of January'))
            ->get();
        
        $clientPurchasers = json_decode($client->purchaser, true) ?: [];
        
        $filtered = $allRepairs->filter(function ($repair) use ($clientPurchasers) {
            return in_array($repair->formatted_name, $clientPurchasers);
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

    public function doneRepairs(Request $request){
        $client = Auth::user()->getClient();

        $allRepairs = Repair::with(['user', 'purchaser', 'region', 'performer', 'response'])
            ->orderBy('id', 'desc')
            ->where('status', 3) 
            ->whereDate('created_at', '>=', Carbon::parse('first day of January'))
            ->get();
        
        $clientPurchasers = json_decode($client->purchaser, true) ?: [];
        
        $filtered = $allRepairs->filter(function ($repair) use ($clientPurchasers) {
            return in_array($repair->formatted_name, $clientPurchasers);
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

        $repair = Repair::with(['user', 'purchaser', 'region', 'performer', 'act'])->find($id);
        $purchaser = $repair->purchaser;
         
        $lastService = $purchaser->services()->where('status', 3)->orderBy('id', 'desc')->first();
        $lastResponse = $purchaser->responses()->where('status', 3)->orderBy('id', 'desc')->first();
        $lastResponseDate = $lastResponse ? $lastResponse->created_at : null;   
        $lastServiceDate = $lastService ? $lastService->created_at : null;
        $additionalData = [
            'last_service_date' => $lastServiceDate,
            'last_response_date' => $lastResponseDate,
            'last_response_content' => $lastResponse ? $lastResponse->act?->note : null,
            'last_response_job_description' => $lastResponse ? $lastResponse->content : null,
            'chat_id' => Chat::where('item_id', $repair->id)->where('type', 'repair')->first() ? Chat::where('item_id', $repair->id)->where('type', 'repair')->first()->id : null,

        ];

        if (!$repair) {
            return response()->json(["success" => false, "message" => "Repair not found"], 404);
        }
    

    
        return response()->json([
            'repair' => $repair,
            'additional_data' => $additionalData,
        ]);
    }

    public function arrived($id)
    {
        $repair = Repair::find($id);
        $repair->time = Carbon::now();
        $repair->status = 10;
        $repair->save();
        return response()->json(["success" => true, "time" => Carbon::now()], 200);
    }

    public function changeStatus(Request $request, Repair $repair) {

        $repair->status = $request->get('status');
        $repair->save();

        if($request->get('status') == 3) {
            $user = $repair->user;
            $user->notify(new NewRepairNotification($user,$repair));
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

 
       $repair = Repair::create([
            "subject_name" => $purchaser->subj_name,
            "subject_address" => $purchaser->subj_address,
            "name" => $purchaser->name,
            "purchaser_id" => $purchaser->id,
            "job_description" => $request->get('description'),
            "status" => 10,
            "user_id" => Auth::user()->id,
            "type" => $type,
        ]);

        $user = auth()->user();
        $user->notify(new NewRepairNotification($user,$repair));

        return response()->json([
            "success" => true,
            "message" => "Repair created successfully"
        ], 200);
    }
}
