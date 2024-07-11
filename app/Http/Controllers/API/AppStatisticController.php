<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppStatisticController extends Controller
{
    public function index(Request $request)
    {
        $from = Carbon::parse($request->get('from'));
        $to = Carbon::parse($request->get('to'));

        if (!$from || !$to) {
            return response()->json(['error' => 'Invalid date range'], 400);
        }

        $responsesDaily = [];
        $dates = collect();

        $from->toPeriod($to)->forEach(function ($date) use ($dates) {
            $dates->push($date->toDateString());
        });

        foreach ($dates as $date) {
            $responses = Response::whereDate('created_at', $date)->count();
            $responsesDaily[] = [
                "date" => $date,
                "count" => $responses
            ];
        }

        $responsesByName = Response::select(
            DB::raw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, ' ', ''), '\"', ''), '.', ''), '''', ''), ',', ''), '“', ''), '„', '') as nameFormatted"),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('nameFormatted')
            ->get();

        $responsesBySphere = Response::select('systems.name', DB::raw('count(responses.id) as total_responses'))
            ->join('systems', 'responses.system_one', '=', 'systems.id')
            ->whereBetween('responses.created_at', [$from, $to])
            ->groupBy('systems.name')
            ->get();

        $regions = [
            "აღმოსავლეთ საქართველო" => "east",
            "დასავლეთ საქართველო" => "west"
        ];

        $responsesByRegion = [];

        foreach ($regions as $location => $key) {
            $regionIds = Region::where('location', $location)->pluck('id');
            $responseCount = Response::whereBetween('created_at', [$from, $to])
                ->whereIn('region_id', $regionIds)
                ->count();

            $responsesByRegion[] = [
                "name" => $location,
                "count" => $responseCount
            ];
        }

        $data = [
            "responsesDaily" => $responsesDaily,
            "responsesByName" => $responsesByName,
            "responsesBySphere" => $responsesBySphere,
            "responsesByRegion" => $responsesByRegion
        ];

        return response()->json($data, 200);
    }
}
