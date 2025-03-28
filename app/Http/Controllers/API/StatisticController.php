<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Invoice;
use App\Models\Response;
use App\Models\Service;
use App\Models\System;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticController extends Controller
{
    public function statistics(Request $request)
    {



        $start_date = Carbon::parse($request->get('start_date'))->startOfDay();
        $end_date = Carbon::parse($request->get('end_date'))->endOfDay();



        $users = User::whereNotIn('email', ['user@gmail.com', 'g.zurabashvili@yahoo.com', 'giorgi@inservice.ge'])->get();
        $stats = array();
        foreach ($users as $user) {
            $userObject["name"] = $user->name;

            $invoices = $user->invoices()->whereBetween('created_at', [$start_date, $end_date])->get();
            $evaluations = $user->evaluations()->whereBetween('created_at', [$start_date, $end_date])->get();
            $user_evaluations_sum = 0;
            $user_invoices_sum = 0;


            foreach ($invoices as $invoice) {
                $user_invoices_sum +=  $this->getItemSum($invoice->id, "invoice");
            }

            foreach ($evaluations as $evaluation) {

                $user_evaluations_sum += $this->getItemSum($evaluation->id, "evaluation");
            }
            $userObject["invoices"] = (int)($user_invoices_sum);
            $userObject["evaluations"] = (int)($user_evaluations_sum);
            $stats["customers"][] = $userObject;
        }

        $responses = Response::select(
            DB::raw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, ' ', ''), '\"', ''), '.', ''), '''', ''), ',', ''), '“', ''), '„', '') as nameFormatted"),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('created_at', [$start_date, $end_date])
            ->groupBy('nameFormatted')
            ->get();


        $responsesPercentage = $responses->map(function ($response) {
            $responsesDistincted = Response::select(
                DB::raw("DISTINCT name"),
                DB::raw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, ' ', ''), '\"', ''), '.', ''), '''', ''), ',', ''), '“', ''), '„', '') as nameFormatted")
            )->where(DB::raw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, ' ', ''), '\"', ''), '.', ''), '''', ''), ',', ''), '“', ''), '„', '')"), $response->nameFormatted)
                ->count();

            $systems = System::whereNull("parent_id")->orderBy('id', 'desc')->get();

            $data = [$response->nameFormatted];

            foreach ($systems as $system) {
                $count = Response::where('system_one', $system->id)
                    ->where(DB::raw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, ' ', ''), '\"', ''), '.', ''), '''', ''), ',', ''), '“', ''), '„', '')"), $response->nameFormatted)
                    ->count();

                $percentage = $responsesDistincted > 0 ? number_format(($count / $responsesDistincted) * 100, 0) . "%" : "Null";

                $data[] = $percentage;
            }

            return $data;
        });


        $systems = System::where("parent_id", NULL)->orderBy('id', 'desc')->get();


        $approvedServices = Service::where('status', 3)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->count();

        $approvedResponses = Response::where('status', 3)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->count();

        $invoices = Invoice::with(['category_attributes'])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->get()
            ->toArray();


        $fullPrice = 0;
        foreach ($invoices as $model) {
            foreach ($model["category_attributes"] as $key => $item) {
                $fullPrice += number_format($item['pivot']['calc'], 2, '.', '');
            }
        }


        $stats["approvedServices"] = $approvedServices;
        $stats["approvedResponses"] = $approvedResponses;
        $stats["invoiceFullPrice"] = $fullPrice;

        $stats["responses"] = $responses;
        $stats["responsesPercentage"] = $responsesPercentage;
        $stats["systems"] = $systems;
        return response()->json($stats, 200);
    }


    public function getItemSum($id, $type)
    {
        if ($type == "invoice") {
            $model = Invoice::with(['purchaser', 'category_attributes.category'])->firstOrNew(['id' => $id])->toArray();
        } else {
            $model = Evaluation::with(['purchaser', 'category_attributes.category'])->firstOrNew(['id' => $id])->toArray();
        }

        $agr = ['prices' => 0, 'calc' => 0, 'service_prices' => 0];

        if (isset($model['category_attributes'])) {
            if ($model["type"] == "invoice") {
                $agr = collect($model['category_attributes'])->reduce(function ($result, $item) {
                    if ($result === null) {
                        $result = [
                            'prices' => 0,
                            'calc' => 0,
                            'service_prices' => 0,
                        ];
                    }
                    $result['prices'] += isset($item['pivot']['price']) ? $item['pivot']['price'] : 0;
                    $result['calc'] += isset($item['pivot']['calc']) ? $item['pivot']['calc'] : 0;
                    $result['service_prices'] += isset($item['pivot']['service_price']) ? $item['pivot']['service_price'] : 0;

                    return $result;
                });
            } else {
                $agr = collect($model['category_attributes'])->reduce(function ($result, $item) {
                    if ($result === null) {
                        $result = [
                            'prices' => 0,
                            'calc' => 0,
                            'service_prices' => 0,
                        ];
                    }
                    $result['prices'] += isset($item['pivot']['evaluation_price']) ? $item['pivot']['evaluation_price'] : 0;
                    $result['calc'] += isset($item['pivot']['evaluation_calc']) ? $item['pivot']['evaluation_calc'] : 0;
                    $result['service_prices'] += isset($item['pivot']['evaluation_service_price']) ? $item['pivot']['evaluation_service_price'] : 0;

                    return $result;
                });
            }
        }
        $titles = [['title' => 'მასალის ტრანსპორტირების ჯამი :', 'key' => 'p1'], ['title' => 'ზედნადები ხარჯი :', 'key' => 'p2'], ['title' => 'მოგება :', 'key' => 'p3'], ['title' => 'გაუთველისწინებელი ხარჯი :', 'key' => 'p4'], ['title' => 'დღგ :', 'key' => 'p5']];
        $initReporteValuesRes = $this->initReporteValues($titles, $model, 'p');
        $starter = isset($agr) ? $agr['calc'] : [];
        $index = 0;

        $calculate = isset($agr) ? $this->recurcive($initReporteValuesRes, $starter, $titles, $index) : [];

        return number_format(isset($agr) ? end($calculate)['percenters']['p2'] : 0, 2, '.', '');
    }
    public function initReporteValues($arr, $model, $indexer)
    {
        $i = 0;
        return array_reduce(
            array_fill(0, count($arr), []),
            function ($carry, $item) use ($arr, $model, $indexer, &$i) {
                $item['name'] = $arr[$i]['title'];
                $item['inputName'] = $indexer . (string) ($i + 1);
                $item['value'] = isset($model[$item['inputName']]) ? $model[$item['inputName']] : 0;
                $i++;

                array_push($carry, $item);

                return $carry;
            },
            [],
        );
    }

    function recurcive($initReporteValuesRes, $starter, &$titles, $index)
    {
        if (isset($initReporteValuesRes[$index])) {
            $percentes = [
                'p1' => ($initReporteValuesRes[$index]['value'] * $starter) / 100,
                'p2' => $starter + ($initReporteValuesRes[$index]['value'] * $starter) / 100,
            ];

            $titles[$index]['percenters'] = $percentes;
            $nextPrice = $percentes['p2'];

            $index = $index + 1;

            return $this->recurcive($initReporteValuesRes, $nextPrice, $titles, $index);
        } else {
            return $titles;
        }
    }
}
