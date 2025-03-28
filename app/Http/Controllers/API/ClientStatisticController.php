<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchaser;
use App\Models\Region;
use App\Models\Response;
use App\Models\Service;
use App\Models\System;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientStatisticController extends Controller
{
    public function index(Request $request)
    {


        $from = Carbon::parse($request->get('from'));
        $to = Carbon::parse($request->get('to'))->endOfDay();

        if (!$from || !$to) {
            return response()->json(['error' => 'Invalid date range'], 400);
        }

        $dates = collect();
        $from->toPeriod($to)->forEach(fn($date) => $dates->push($date->toDateString()));


        $client = auth()->user()->client;

        $branchs = Purchaser::get()->filter(function ($purchaser) use ($client) {
            return $purchaser->formatted_name === $client->purchaser;
        })->map(function ($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'subj_name' => $branch->subj_name,
                'subj_address' => $branch->subj_address,
            ];
        })->values();


        $purchasers = $request->get("branchs") ? $request->get("branchs") : $branchs->pluck('id');


        $responsesDaily = $this->getDailyResponsesCount($dates, $purchasers);
        $responsesByName = $this->getResponsesByName($from, $to, $purchasers);
        $responsesBySphere = $this->getResponsesBySphere($from, $to, $purchasers);
        $responsesByRegion = $this->getResponsesByRegion($from, $to, $purchasers);
        $calendar = $this->calendar($from, $to, $purchasers);


        $data = [
            "responsesDaily" => $responsesDaily,
            "responsesByName" => $responsesByName,
            "responsesBySphere" => $responsesBySphere,
            "responsesByRegion" => $responsesByRegion,
            "calendar" => $calendar,

            "branchs" => $branchs
        ];

        return response()->json($data, 200);
    }


    private function getDailyResponsesCount($dates, $purchasers)
    {
        return $dates->map(function ($date) use ($purchasers) {
            $approvedCount = Response::whereDate('created_at', $date)->whereIn("purchaser_id", $purchasers)->where('status', 3)->count();
            $onRepairCount = Response::whereDate('created_at', $date)->whereIn("purchaser_id", $purchasers)->where('on_repair', 1)->count();


            return [
                "date" => $date,
                "approvedCount" => $approvedCount,
                "onRepairCount" => $onRepairCount,
            ];
        })->toArray();
    }

    private function getResponsesByName($from, $to, $purchasers)
    {
        $purchasers = Purchaser::whereIn("id", $purchasers)->select("id", "name", "subj_name", "subj_address")->get();
        foreach ($purchasers as $purchaser) {
            $purchaser["responses_count"] = Response::whereBetween('created_at', [$from, $to])
                ->where('purchaser_id', $purchaser->id)
                ->count();
        }

        return $purchasers;
    }


    private function getResponsesBySphere($from, $to, $purchasers)
    {
        $responsesBySphere = [];
        $systems = System::where('parent_id', NULL)->get();

        foreach ($systems as $system) {
            $responseCount = Response::whereBetween('created_at', [$from, $to])
                ->where('system_one', $system->id)
                ->whereIn('purchaser_id', $purchasers)
                ->count();


            if ($responseCount) {
                $responsesBySphere[] = [
                    "name" => $system->name,
                    "total_responses" => $responseCount,
                ];
            }
        }

        return $responsesBySphere;
    }

    private function getResponsesByRegion($from, $to, $purchasers)
    {
        $regions = [
            "აღმოსავლეთ საქართველო" => "east",
            "დასავლეთ საქართველო" => "west"
        ];

        $responsesByRegion = [];
        foreach ($regions as $location => $key) {
            $regionIds = Region::where('location', $location)->pluck('id');

            $responseCount = Response::whereBetween('created_at', [$from, $to])
                ->whereIn('region_id', $regionIds)
                ->whereIn('purchaser_id', $purchasers)
                ->count();


            $responsesByRegion[] = [
                "name" => $location,
                "count" => $responseCount
            ];
        }

        return $responsesByRegion;
    }

    private function calendar($from, $to, $purchasers)
    {
        $responses = Response::whereIn("purchaser_id", $purchasers)->with('act')->whereBetween('created_at', [$from, $to])->with('purchaser')->get();
        $services = Service::whereIn("purchaser_id", $purchasers)->with('act')->whereBetween('created_at', [$from, $to])->with('purchaser')->get();

        $data["responses"] = $responses;
        $data["services"] = $services;

        return $data;
    }
}
