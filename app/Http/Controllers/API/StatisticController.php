<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function statistics(Request $request)
    {

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        // $invoices_summary = 0;
        // $evaluations_summary = 0;

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
                // $invoices_summary += $user_invoices_sum;
            }

            foreach ($evaluations as $evaluation) {

                $user_evaluations_sum += $this->getItemSum($evaluation->id, "evaluation");
                // $evaluations_summary += $user_evaluations_sum;
            }
            $userObject["invoices"] = (int)($user_invoices_sum);
            $userObject["evaluations"] = (int)($user_evaluations_sum);
            $stats["customers"][] = $userObject;
        }
        // $stats["invoices_summary"] = $invoices_summary;
        // $stats["evaluations_summary"] = $evaluations_summary;
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
