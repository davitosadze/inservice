<html>

<style type="text/css">
    table,
    th,
    td {
        border: 1px solid black;
    }

    .unit {
        background-color: #DDDDDD;
    }

    .total {
        background-color: #A4CC65;
    }
</style>

@php

    $agr = ['prices' => 0, 'calc' => 0, 'service_prices' => 0];

    if (isset($model['category_attributes'])) {
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
    }

    $titles = [
        ['title' => 'მასალის ტრანსპორტირების ჯამი :', 'key' => 'p1'],
        ['title' => 'ზედნადები ხარჯი :', 'key' => 'p2'],
        ['title' => 'მოგება :', 'key' => 'p3'],
        ['title' => 'გაუთველისწინებელი ხარჯი :', 'key' => 'p4'],
        ['title' => 'დღგ :', 'key' => 'p5'],
    ];

    function initReporteValues($arr, $model, $indexer)
    {
        $i = 0;
        return array_reduce(
            array_fill(0, count($arr), []),
            function ($carry, $item) use ($arr, $model, $indexer, &$i) {
                $item['name'] = $arr[$i]['title'];
                $item['inputName'] = $indexer . (string) ($i + 1);
                $item['value'] = isset($model[$item['inputName']]) ? isset($model[$item['inputName']]) : 0;
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

            return recurcive($initReporteValuesRes, $nextPrice, $titles, $index);
        } else {
            return $titles;
        }
    }

    $initReporteValuesRes = initReporteValues($titles, $model, 'p');
    $starter = $agr['calc'];
    $index = 0;

    $calculate = recurcive($initReporteValuesRes, $starter, $titles, $index);

@endphp



<table style="width: 1200px;">
    <thead>
        <tr>
            <td valign="center" colspan="4" height="130px">
                <p>{{ $model['purchaser']['name'] }}</p>
                <p>{{ $model['purchaser']['subj_name'] }}</p>
                <p>{{ $model['purchaser']['identification_num'] }}</p>
                <p>{{ $model['purchaser']['subj_address'] }}</p>
            </td>
            <td style="text-align: right; font-weight:100;" colspan="10" valign="center">
                <p>მომწოდებელი: შპს " ინსერვისი "</p>
                <p>ს/კ: 206346685</p>
                <p>მისამართი: თბილისი, წერეთლის 115ა</p>
                <p>ანგ. # GE96 TB54 2053 6020 1000 03</p>
                <p>ანგ. # GE94 BG00 0000 0609 8583 91</p>
                <p>ანგ. # GE75 CD03 6000 0057 2676 23</p>
            </td>
        </tr>
    </thead>
</table>

<table style="width: 1200px;">
    <thead>
        <tr>
            <td colspan="2" height="100px">
                <p>დასახელება</p>
            </td>
            <td colspan="8" style="text-align:center" valign="center">
                <h1>ინვოისი {{ $model['uuid'] }}</h1>
            </td>
            <td colspan="4" valign="center">
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:left;" colspan="14">#</td>
        </tr>

    </tbody>
</table>



<table style="width: 1200px;">
    <thead>
        <tr>
            <th height="20" colspan="1" style="background-color: #A4CC65;" valign="center" align="center">#:</th>
            <th colspan="4" style="background-color: #d6d5d5;" valign="center" align="center">დასახელება</th>
            <th colspan="2" style="background-color: #d6d5d5;" valign="center" align="center">აღწერა</th>
            <th colspan="2" style="background-color: #d6d5d5;" valign="center" align="center">ერთეული</th>
            <th style="background-color: #c2c2c2;" class="unit" colspan="1" valign="center" align="center">
                რაოდენობა
            </th>
            <th style="background-color: #c2c2c2;" class="unit" colspan="1" valign="center" align="center">ფასი
            </th>
            <th style="background-color: #c2c2c2;" class="unit" colspan="2" valign="center" align="center">
                მომსახურება
            </th>
            <th style="background-color: #A4CC65;" colspan="1" valign="center" align="center">ჯამი</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($model['category_attributes'] as $key => $item)
            <tr>
                <td valign="center" style="background-color: #A4CC65;" height="20" colspan="1" align="center">
                    {{ $key + 1 }}</td>
                <td valign="center" style="background-color: #d6d5d5;" colspan="4">{{ $item['pivot']['title'] }}</td>
                <td valign="center" style="background-color: #d6d5d5;" colspan="2">{{ $item['name'] }}</td>
                <td valign="center" style="background-color: #d6d5d5;" colspan="2" data-format="0.00">
                    {{ $item['item'] }}</td>
                <td valign="center" style="background-color: #c2c2c2;" colspan="1">{{ $item['pivot']['qty'] }}</td>
                <td valign="center" style="background-color: #c2c2c2;" colspan="1" data-format="0.00">
                    {{ $item['pivot']['price'] }}</td>

                <td valign="center" style="background-color: #c2c2c2;" colspan="2" data-format="0.00">
                    {{ $item['pivot']['service_price'] }}
                </td>
                <td valign="center" style="background-color: #A4CC65;" colspan="1" data-format="0.00">
                    {{ $item['pivot']['calc'] }}</td>
            </tr>
        @endforeach

        <tr style="text-align:right;" class="calculator">
            <th style="text-align:right;" colspan="12">სულ : </th>

            <th colspan="2" data-format="0.00">
                {{ $agr['calc'] }}
            </th>

        </tr>
    </tbody>
</table>


{{-- <table style="width:100%;">

    <tbody>

        @foreach ($calculate as $key => $item)
            <tr>
                <td valign="center" colspan="11" rowspan="2"> {{ $item['title'] }} </td>
                <td colspan="3" rowspan="2" valign="center" align="center">
                    <b>{{ number_format($initReporteValuesRes[$key]['value'], 2, '.', '') . ' %' }}</b>
                </td>
                <td colspan="7" data-format="0.00">{{ number_format($item['percenters']['p1'], 2, '.', '') }}</td>
            </tr>

            <tr>
                <td colspan="7" data-format="0.00">{{ number_format($item['percenters']['p2'], 2, '.', '') }}</td>
            </tr>
        @endforeach



    </tbody>
</table> --}}

</html>
