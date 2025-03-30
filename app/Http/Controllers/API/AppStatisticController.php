<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Response;
use App\Models\Service;
use App\Models\System;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent\Builder;

class AppStatisticController extends Controller
{
    public function index(Request $request)
    {
        $from = Carbon::parse($request->get('from'));
        $to = Carbon::parse($request->get('to'))->endOfDay();
        $purchasers = $request->get('names');

        if (!$from || !$to) {
            return response()->json(['error' => 'Invalid date range'], 400);
        }

        $dates = collect();
        $from->toPeriod($to)->forEach(fn($date) => $dates->push($date->toDateString()));

        $arr = array();
        $responsesDaily = $this->getDailyResponsesCount($dates, $purchasers);
        $responsesByName = array_values($this->getResponsesByName($from, $to, $purchasers));
        $responsesBySphere = $this->getResponsesBySphere($from, $to, $purchasers);
        $responsesByRegion = $this->getResponsesByRegion($from, $to, $purchasers);
        $responsesByPerformer = $this->getResponsesByPerformer($from, $to, $purchasers);
        $responsesAndServicesCount = $this->getResponsesAndServicesCount($from, $to, $purchasers);
        $nonApproved = $this->getNonApprovedCount($from, $to);

        $data = [
            "responsesDaily" => $responsesDaily,
            "responsesByName" => $responsesByName,
            "responsesBySphere" => $responsesBySphere,
            "responsesByRegion" => $responsesByRegion,
            "responsesByPerformer" => $responsesByPerformer,
            "nonApproved" => $nonApproved,
            "responsesAndServicesCount" => $responsesAndServicesCount,
        ];

        return response()->json($data, 200);
    }

    private function getResponsesAndServicesCount($from, $to, $purchaser)
    {

        $responsesQuery = Response::whereBetween('created_at', [$from, $to])
            ->where('status', 3)->get();

        $responseCount = $purchaser ? $responsesQuery->where('formatted_name', $purchaser)->count() : $responsesQuery->count();


        $servicesQuery = Service::whereBetween('created_at', [$from, $to])
            ->where('status', 3)->get();

        $serviceCount = $purchaser ? $servicesQuery->where('formatted_name', $purchaser)->count() : $servicesQuery->count();

        return [
            "approvedResponses" => $responseCount,
            "approvedServices" => $serviceCount,
        ];
    }

    private function getDailyResponsesCount($dates, $purchasers)
    {
        return $dates->map(function ($date) use ($purchasers) {
            $approvedResponses = Response::whereDate('created_at', $date)->where('status', 3)->get();
            $onRepairResponses = Response::whereDate('created_at', $date)->where('on_repair', 1)->get();

            $approvedCount = $purchasers ? $approvedResponses->whereIn('formatted_name', $purchasers)->count() : $approvedResponses->count();
            $onRepairCount = $purchasers ? $onRepairResponses->whereIn('formatted_name', $purchasers)->count() : $onRepairResponses->count();

            return [
                "date" => $date,
                "approvedCount" => $approvedCount,
                "onRepairCount" => $onRepairCount,
            ];
        })->toArray();
    }




    private function getResponsesByName($from, $to, $purchasers)
    {
        $responsesByName = Response::select(
            DB::raw("REGEXP_REPLACE(name, '[\" .,\'“„]', '') as nameFormatted"),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('nameFormatted')
            ->get();
        $responsesByName = $responsesByName->toArray();

        // Filter by purchasers if necessary
        return $purchasers ? collect($responsesByName)->whereIn('nameFormatted', $purchasers)->toArray() : $responsesByName;
    }

    private function getResponsesBySphere($from, $to, $purchasers)
    {
        $responsesBySphere = [];
        $systems = System::where('parent_id', NULL)->get();

        foreach ($systems as $system) {
            $responsesQuery = Response::whereBetween('created_at', [$from, $to])
                ->where('system_one', $system->id)
                ->get();

            $responseCount = $purchasers ? $responsesQuery->whereIn('formatted_name', $purchasers)->count() : $responsesQuery->count();

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

            $responsesQuery = Response::whereBetween('created_at', [$from, $to])
                ->whereIn('region_id', $regionIds)
                ->get();

            $responseCount = $purchasers ? $responsesQuery->whereIn('formatted_name', $purchasers)->count() : $responsesQuery->count();

            $responsesByRegion[] = [
                "name" => $location,
                "count" => $responseCount
            ];
        }

        return $responsesByRegion;
    }

    private function getResponsesByPerformer($from, $to, $purchasers)
    {
        $performers = User::whereHas('roles', function (Builder $query) {
            $query->whereIn('name', ['ინჟინერი']);
        })->get();

        $responsesByPerformer = [];
        foreach ($performers as $performer) {
            $performerObject = [
                "id" => $performer->id,
                "name" => $performer->name,
                "email" => $performer->email,
                "profile_image" => $performer->profile_image,
            ];

            $nonApprovedResponsesCount = $this->getResponseCount($from, $to, $performer->id, '!=', 3, $purchasers);
            $approvedResponsesCount = $this->getResponseCount($from, $to, $performer->id, '=', 3, $purchasers);

            $performerObject["approved_responses_count"] = $approvedResponsesCount;
            $performerObject["non_approved_responses_count"] = $nonApprovedResponsesCount;

            $responsesByPerformer[] = $performerObject;
        }

        return $responsesByPerformer;
    }

    private function getResponseCount($from, $to, $performerId, $operator, $status, $purchasers)
    {
        $responsesQuery = Response::whereBetween('created_at', [$from, $to])
            ->where('performer_id', $performerId)
            ->where('status', $operator, $status)
            ->get();

        return $purchasers ? $responsesQuery->whereIn('formatted_name', $purchasers)->count() : $responsesQuery->count();
    }


    private function getNonApprovedCount($from, $to)
    {

        $responses = Response::whereBetween('created_at', [$from, $to])->where('status', '!=', 3)->count();
        $services = Service::whereBetween('created_at', [$from, $to])->where('status', '!=', 3)->count();

        $data["nonApprovedResponses"] = $responses;
        $data["nonApprovedServices"] = $services;

        return $data;
    }
}
