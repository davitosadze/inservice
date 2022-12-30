<html>

    <style type="text/css"> table, th, td { border: 1px solid black; }</style>

    @php


        $agr = ['prices' => 0,'calc' => 0,'service_prices' => 0];

        if (isset($model['category_attributes'])) {

            $agr = collect($model['category_attributes'])->reduce(function ($result, $item) {
                if ($result === null) {

                    $result = [
                        'prices' => 0,
                        'calc' => 0,
                        'service_prices' => 0

                    ];
                }

                $result['prices']           +=  isset($item['pivot']['evaluation_price']) ? $item['pivot']['evaluation_price'] : 0;
                $result['calc']             +=  isset($item['pivot']['evaluation_calc']) ? $item['pivot']['evaluation_calc'] : 0;
                $result['service_prices']   +=  isset($item['pivot']['evaluation_service_price']) ? $item['pivot']['evaluation_service_price'] : 0;

                return $result;

            });
        };

        $titles = [
            ['title' => 'მასალის ტრანსპორტირების ჯამი :', 'key' => 'p1'],
            ['title' => 'ზედნადები ხარჯი :', 'key' => 'p2'],
            ['title' => 'მოგება :', 'key' => 'p3'],
            ['title' => 'გაუთველისწინებელი ხარჯი :', 'key' => 'p4'],
            ['title' => 'დღგ :', 'key' => 'p5']
        ];

        function initReporteValues ($arr, $model, $indexer) {
            $i = 0;
            return array_reduce(array_fill(0, count($arr), []), function ($carry, $item) use ($arr, $model, $indexer, &$i) {
                $item['name'] = $arr[$i]['title'];
                $item['inputName'] = $indexer . (string) ($i + 1);
                $item['value'] = isset($model[$item['inputName']]) ? isset($model[$item['inputName']]) : 0;
                $i++;

                array_push($carry, $item);

                return $carry;
            }, []);

        }

        function recurcive ($initReporteValuesRes, $starter, &$titles, $index) {
            if (isset($initReporteValuesRes[$index])) {

                $percentes = [
                    'p1' => ($initReporteValuesRes[$index]['value'] * $starter) / 100,
                    'p2' => $starter + ($initReporteValuesRes[$index]['value'] * $starter) / 100
                ];

                $titles[$index]['percenters'] = $percentes;
                $nextPrice = $percentes['p2'];

                $index = $index +1;

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



    <table style="width: 1000px;">
        <thead>
            <tr>
                <td width="59px" colspan="3" height="21px" valign="center" align="center"  ></td>
                <td data-margin="500px" colspan="5" height="100px">
                    <p>INSERVICE  LLC</p>
                    <p>Georgia, Tbilisi, Tsereteli ave. 115 a</p>
                    <p>Tel. : +995 322 242 12 12</p>
                    <p>Mail : service@inservice.ge</p>
                    <p>Web : support.inservice.ge</p>
                    <p>Trust The quality of the professionals</p>
                </td>
                <td></td>
                <td  colspan="5" valign="center">
                    <p>INSERVICE  LLC</p>
                    <p>Georgia, Tbilisi, Tsereteli ave. 115 a</p>
                    <p>Tel. : +995 322 242 12 12</p>
                    <p>Mail : service@inservice.ge</p>
                    <p>Web : support.inservice.ge</p>
                    <p>Trust The quality of the professionals</p>
                </td>
            </tr>
        </thead>
    </table>


    <table>
        <thead>
    
           <tr>
                <th colspan="8" align="center" valign="center" >დამკვეთი:</th>
           </tr>
        </thead>

        <tbody>
            <tr>
                <td colspan="8">
                    დასახელება : შუაგული 
                </td>
                <td></td>
                <td colspan="5" rowspan="4" valign="center"  align="center">ხარჯთაღრიცხვის # {{$model['uuid']}}</td>
                <td></td>
                <td></td>
                <td colspan="5" rowspan="4" valign="center"  align="center">თარიღი: {{$model['created_at']}}</td>
   
            </tr>

            <tr>
                <td colspan="8">
                    ს/კ " 206346685
                </td>
            </tr>

            <tr>
                <td colspan="8">
                    მომსახურე ბანკი : თიბისი ბანკი
                </td>
               
            </tr>

            <tr>
                <td colspan="8">
                    ბანკის კოდი : TBCBGE22
                </td>
               
            </tr>

            <tr>
                <td colspan="8">
                    ა/ა : GE96 TB54 2053 6020 1000 03
                </td>
               
            </tr>

        </tbody>

    </table>


    <table>
        <thead>
            <tr>
                <th valign="center" align="center" >N:</th>
                <th colspan="7" valign="center" align="center" >დასახელება</th>
                <th colspan="3" valign="center" align="center" >აღწერა</th>
                <th colspan="2" valign="center" align="center" >ერთეული</th>
                <th colspan="2" valign="center" align="center">რაოდენობა</th>
                <th colspan="2" valign="center" align="center">ფასი</th>
                <th colspan="2" valign="center" align="center">ხელ.ფასი</th>
                <th colspan="2" valign="center" align="center">ჯამი</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($model['category_attributes'] as $key => $item)

            <tr>
                <td align="center">{{$key+1}}</td>
                <td colspan="7">{{$item['pivot']['title']}}</td>
                <td colspan="3">{{$item['name']}}</td>
                <td colspan="2" data-format="0.00">{{$item['item']}}</td>
                <td colspan="2">{{$item['pivot']['qty']}}</td>
                <td colspan="2" data-format="0.00">{{$item['pivot']['evaluation_price']}}</td>
                <td colspan="2" data-format="0.00">{{$item['pivot']['evaluation_service_price']}}</td>
                <td colspan="2" data-format="0.00">{{$item['pivot']['evaluation_calc']}}</td>
            </tr>

            @endforeach

            <tr style="text-align:right;" class="calculator">
                <th style="text-align:left;" colspan="15">დაჯამება : </th>
                <th colspan="2" data-format="0.00">
                {{$agr['prices']}}
                </th>

                <th colspan="2" data-format="0.00">
                {{$agr['service_prices']}}
                </th>

                <th colspan="2" data-format="0.00">
                {{$agr['calc']}}
                </th>

            </tr>
        </tbody>
    </table>


    <table style="width:100%;">
      
        <tbody>

            @foreach ($calculate as $key => $item)
                <tr>
                    <td valign="center" colspan="11" rowspan="2" > {{$item['title']}} </td>
                    <td colspan="3"  rowspan="2" valign="center" align="center"  >
                    <b>{{number_format($initReporteValuesRes[$key]['value'], 2, '.', ''). ' %'}}</b>
                    </td>
                    <td colspan="7" data-format="0.00">{{number_format($item['percenters']['p1'], 2, '.', '')}}</td>
                    </tr>

                    <tr>
                    <td colspan="7" data-format="0.00">{{number_format($item['percenters']['p2'], 2, '.', '')}}</td>
                </tr>
            @endforeach
            


        </tbody>
    </table>
    
</html>