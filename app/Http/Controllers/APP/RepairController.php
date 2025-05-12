<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\Purchaser;
use App\Models\Repair;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class RepairController extends Controller
{

    public function index(Request $request)
    {
        $repairs = Repair::with(['user', 'purchaser', 'region', 'performer', 'response'])
        ->whereHas('response', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->orderBy('id', 'desc')
        ->get();
    
        return response($repairs->toArray());
    }

    public function arrived($id)
    {
        $repair = Repair::find($id);
        $repair->time = Carbon::now();
        $repair->status = 10;
        $repair->save();
        return response()->json(["success" => true, "time" => Carbon::now()], 200);
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

 
        Repair::create([
            "subject_name" => $purchaser->subj_name,
            "subject_address" => $purchaser->subj_address,
            "name" => $purchaser->name,
            "purchaser_id" => $purchaser->id,
            "job_description" => $request->get('description'),
            "status" => 10,
            "user_id" => Auth::user()->id,
            "type" => $type,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Repair created successfully"
        ], 200);
    }
}
