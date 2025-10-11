<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Response;
use App\Models\System;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole("ინჟინერი")) {
            return redirect("/responses?type=pending");
        } else {
            return view('dashboard');
        }
    }

    public function test()
    {



        $responses = Response::select('name')->groupBy('name')->get();

        $systems = System::whereNull('parent_id')->select('name', 'id')->groupBy('name', 'id')->get()->keyBy('id');

        $wameba = array();
        foreach ($responses as $response) {
            $wameba[] = $response->name;
            $responseArray = [];

            foreach ($systems as $system) {
                $systemsCount = Response::where('name', $response->name)
                    ->where('system_one', $system->id)
                    ->count();

                $responseArray[] = [
                    "name" => $system->name,
                    "count" => $systemsCount
                ];
            }

            $response->systems = $responseArray;
        }
        return $wameba;


        $data = $responses->map(function ($response) {
            return [
                $response->name,
                $response->systems,
            ];
        });

        return $data;
        // return $responses;
    }
}
