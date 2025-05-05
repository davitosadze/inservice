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

        Repair::create([
            "subject_name" => $purchaser->subj_name,
            "subject_address" => $purchaser->subj_address,
            "name" => $purchaser->name,
            "purchaser_id" => $purchaser->id,
            "job_description" => $request->get('description'),
            "status" => 10,
            "user_id" => Auth::user()->id
        ]);

        return response()->json([
            "success" => true,
            "message" => "Repair created successfully"
        ], 200);
    }
}
