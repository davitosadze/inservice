<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index()
    {
        $option = Option::first();
        return view('options.index', compact("option"));
    }

    public function store(Request $request)
    {
        $option = Option::first();
        if ($option) {
            if($request->get('price_increase')){
                $option->price_increase = $request->get("price_increase");
            }
            if($request->get('email')){
                $option->email = $request->get("email");
            }
             $option->save();
        } else {
            Option::create(
                [
                    "price_increase" => $request->get("price_increase"),
                    "email" => $request->get("email")
                ]
            );
        }

        return back();
    }
}
