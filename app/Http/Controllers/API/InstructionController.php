<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Instruction;
use Illuminate\Http\Request;

class InstructionController extends Controller
{
    public function index()
    {
        $instructions = Instruction::orderBy('id', 'desc')->get();

        return response()->json(["data" => $instructions], 200);
    }

    public function show($slug)
    {
        $instruction = Instruction::where('slug', $slug)->with('media')->first();
        return response()->json(["data" => $instruction], 200);
    }
}
