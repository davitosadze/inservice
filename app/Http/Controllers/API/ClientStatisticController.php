<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Purchaser;
use App\Models\Region;
use App\Models\Response;
use App\Models\Service;
use App\Models\System;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientStatisticController extends Controller
{

    public function me()
    {
        $user = auth()->user();
        $client = Client::whereJsonContains('user_ids', $user->id)->first() ?? null;

        if ($client) {
            $toggles = $client->toggles ?? [];

            $clientData = collect($client)->toArray();

            $filteredClient = collect($clientData)->filter(function ($value, $key) use ($toggles) {
                return !isset($toggles[$key]) || $toggles[$key] === true;
            });

            $userArray = $user->toArray();
            $userArray['client'] = $filteredClient;

            return response()->json([
                "user" => $userArray
            ], 200);
        }

        return response()->json(["user" => $user], 200);
    }

    public function index(Request $request)
    {


        $from = Carbon::parse($request->get('from'));
        $to = Carbon::parse($request->get('to'))->endOfDay();

        if (!$from || !$to) {
            return response()->json(['error' => 'Invalid date range'], 400);
        }

        $dates = collect();
        $from->toPeriod($to)->forEach(fn($date) => $dates->push($date->toDateString()));

        $user = auth()->user();
        $client = Client::whereJsonContains('user_ids', $user->id)->first() ?? null;

        $permissions = $client["toggles"];
        $branches = Purchaser::whereNot('single', 1)->get()
            ->filter(function ($purchaser) use ($client) {
                $clientPurchasers = json_decode($client->purchaser, true) ?: [];
                return in_array($purchaser->formatted_name, $clientPurchasers);

            })
            ->map(function ($branch) {
                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'subj_name' => $branch->subj_name,
                    'subj_address' => $branch->subj_address,
                ];
            })
            ->values();


        $purchasers = $request->get("branches") ? $request->get("branches") : $branches->pluck('id');


        $responsesDaily = $this->getDailyResponsesCount($dates, $purchasers);
        $responsesByName = $this->getResponsesByName($from, $to, $purchasers);
        $responsesBySphere = $this->getResponsesBySphere($from, $to, $purchasers);
        $responsesByRegion = $this->getResponsesByRegion($from, $to, $purchasers);
        $nonApproved = $this->getNonApprovedCount($from, $to, $purchasers);
        $calendar = $this->calendar($from, $to, $purchasers);


        $data = [
            "responsesDaily" => !empty($permissions["incidents_by_numbers"]) ? $responsesDaily : [],
            "responsesByName" => !empty($permissions["incidents_by_branches"]) ? $responsesByName : [],
            "responsesBySphere" => !empty($permissions["incidents_by_fields"]) ? $responsesBySphere : [],
            "responsesByRegion" => !empty($permissions["incidents_by_regions"]) ? $responsesByRegion : [],
            "nonApproved" => $nonApproved,
            "calendar" => $calendar,
            "branches" => $branches,

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

        $purchasers = Purchaser::whereIn("id", $purchasers)
            ->select("id", "name", "subj_name", "subj_address")
            ->get();
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
        $responses = Response::whereIn("purchaser_id", $purchasers)
            ->with(['act' => function ($query) {
                $query->select('response_id', 'note');
            }])
            ->whereBetween('created_at', [$from, $to])

            ->get();

        $services = Service::whereDate('created_at', '>=', Carbon::parse('2025-04-01'))->whereIn("purchaser_id", $purchasers)
            ->with(['act' => function ($query) {
                $query->select('service_id', 'note');
            }])
            ->whereBetween('created_at', [$from, $to])
            ->with('purchaser')
            ->get();
        $data["responses"] = $responses;
        $data["services"] = $services;

        return $data;
    }

    private function getNonApprovedCount($from, $to, $purchasers)
    {

        $responses = Response::whereIn("purchaser_id", $purchasers)
            ->whereBetween('created_at', [$from, $to])
            ->where('status', '!=', 3)
            ->count();

        $services = Service::whereIn("purchaser_id", $purchasers)
            ->whereBetween('created_at', [$from, $to])
            ->where('status', '!=', 3)
            ->count();

        $data["nonApprovedResponses"] = $responses;
        $data["nonApprovedServices"] = $services;

        return $data;
    }
}
