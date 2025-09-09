<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Purchaser;
use App\Models\Service;
use App\Notifications\NewResponseNotification;
use App\Models\ServiceNotifiable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Validator;

class ServiceController extends Controller
{
    public function index() {
        $client = Auth::user()->getClient();
        $search = request()->get('search');

        $query = Service::with(['user', 'purchaser', 'region', 'performer'])
        ->orderBy('id', 'desc')
        ->where('status', '!=', 3)
        ->whereDate('created_at', '>=', Carbon::parse('2025-01-01'))
        ->whereNotNull('estimated_arrival_time');

        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('subject_name', 'LIKE', "%{$search}%")
                  ->orWhere('subject_address', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }

        $allServices = $query->get();
        
        // Manual pagination
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginated = new LengthAwarePaginator(
            $allServices->forPage($page, $perPage)->values(),
            $allServices->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
            
        return response($paginated);
    }

    public function doneServices(Request $request) {
        $client = Auth::user()->getClient();
        $search = request()->get('search');

        $query = Service::with(['user', 'purchaser', 'region', 'performer'])
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

        $allServices = $query->get();
        
        // Manual pagination
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginated = new LengthAwarePaginator(
            $allServices->forPage($page, $perPage)->values(),
            $allServices->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return response($paginated);
    }

    public function show($id)
    {
        $service = Service::with(['user', 'purchaser', 'region', 'performer', 'act'])->find($id);
        $purchaser = $service->purchaser;
         
        $lastService = $purchaser->services()
            ->where('status', 3)
            ->where('id', '<', $service->id)
            ->where('created_at', '<', $service->created_at)
            ->orderBy('id', 'desc')
            ->first();
        $lastResponse = $purchaser->responses()
            ->where('status', 3)
            ->where('created_at', '<', $service->created_at)
            ->orderBy('id', 'desc')
            ->first();
        $lastResponseDate = $lastResponse ? $lastResponse->created_at : null;   
        $lastServiceDate = $lastService ? $lastService->created_at : null;
        $additionalData = [
            'last_service_date' => $lastServiceDate,
            'last_response_date' => $lastResponseDate,
            'last_response_content' => $lastResponse ? $lastResponse->act?->note : null,
            'last_response_job_description' => $lastResponse ? $lastResponse->content : null,
            'chat_id' => Chat::where('item_id', $service->id)->where('type', 'service')->first() ? Chat::where('item_id', $service->id)->where('type', 'service')->first()->id : null,
        ];

        if (!$service) {
            return response()->json(["success" => false, "message" => "Service not found"], 404);
        }
    
        return response()->json([
            'service' => $service,
            'additional_data' => $additionalData,
        ]);
    }

    public function arrived($id)
    {
        $service = Service::find($id);
        $service->time = Carbon::now();
        $service->status = 10;
        $service->save();
        return response()->json(["success" => true, "time" => Carbon::now()], 200);
    }

    public function changeStatus(Request $request, Service $service) {
        $service->status = $request->get('status');
        $service->save();

        $user = $service->user;
        $serviceNotifiable = new ServiceNotifiable();
        $serviceNotifiable->notify(new NewResponseNotification($user,$service));

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

        $service = Service::create([
            "subject_name" => $purchaser->subj_name,
            "subject_address" => $purchaser->subj_address,
            "name" => $purchaser->name,
            "identification_num" => $purchaser->identification_num,
            "purchaser_id" => $purchaser->id,
            "content" => $request->get('description'),
            "status" => 9,
            "user_id" => Auth::user()->id,
            "type" => $type,
        ]);

        $user = auth()->user();
        $serviceNotifiable = new ServiceNotifiable();
        $serviceNotifiable->notify(new NewResponseNotification($user,$service));

        return response()->json([
            "success" => true,
            "message" => "Service created successfully"
        ], 200);
    }
}
