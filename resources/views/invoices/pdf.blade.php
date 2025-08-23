<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ isset($model['uuid']) ? $model['uuid'] : '' }}</title>
    <style type="text/css">
        @font-face {
            font-family: 'BPG WEB 001 Caps';
            src: url('/fonts/bpg-web-001-caps-webfont.eot');
            /* IE9 Compat Modes */
            src:
                url('/fonts/bpg-web-001-caps-webfont.eot?#iefix') format('embedded-opentype'),
                /* IE6-IE8 */
                url('/fonts/bpg-web-001-caps-webfont.woff2') format('woff2'),
                /* Super Modern Browsers */
                url('/fonts/bpg-web-001-caps-webfont.woff') format('woff'),
                /* Pretty Modern Browsers */
                url('/fonts/bpg-web-001-caps-webfont.ttf') format('truetype'),
                /* Safari, Android, iOS */
                url('/fonts/bpg-web-001-caps-webfont.svg#bpg_web_001_capsregular') format('svg');
            /* Legacy iOS */
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            font-family: DejaVu Sans, sans-serif, 'BPG WEB 001 Caps';


            color: #555555;
            background: #FFFFFF;
            font-size: 0.7em;
        }

        header {

            border-right: 5px solid #A4CC65;
            border-left: 5px solid #A4CC65;

            padding: 10px 0;
            margin-bottom: 20px;
            /*border-bottom: 1px solid #AAAAAA;*/
        }

        #logo {
            float: left;
            margin-top: 8px;
        }

        #logo img {
            height: 70px;
        }

        #company {
            float: right;
            text-align: right;
            /*padding-right: 5px;*/
        }


        #details {
            margin-top: 20px;
            margin-bottom: 50px;
        }

        #client {
            /*padding-left: 5px;*/
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.3em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            text-align: right;
        }

        #invoice h1 {
            text-align: center;
            /*color: #0087C3;*/
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            float: right;
            font-size: 1.1em;
            color: #777777;
        }

        #invoice .title {
            float: left;
            font-size: 1.1em;
            text-align: left;
            color: #777777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 0.4em;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            font-size: 1em;
            white-space: nowrap;
            font-weight: 7000;
        }

        table td {
            font-size: 0.9em;
            text-align: right;
        }

        table td h3 {
            color: #57B223;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #000;
            font-size: 1em;
            background: #A4CC65;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }

        table .qty {}

        table .total {
            background: #A4CC65;
            color: #000;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1em;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 10px 20px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.1em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            /*color: #57B223;*/
            font-size: 1.4em;
            /*border-top: 1px solid #57B223; */

        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 5px solid #A4CC65;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
        }

        .notices-container {
            display: flex;

        }

        .notices-container>.first {
            float: left;
            width: 45%;
        }

        .notices-container>.second {
            float: right;
            width: 45%;
            text-align: right;

        }

        .notices-container>.second img {
            align-self: flex-end;
        }



        @page {
            margin: 0cm 0cm;
        }
    </style>
</head>

<body style="padding:0.5cm;">

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
                $result['service_prices'] += isset($item['pivot']['service_price'])
                    ? $item['pivot']['service_price']
                    : 0;

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

                return recurcive($initReporteValuesRes, $nextPrice, $titles, $index);
            } else {
                return $titles;
            }
        }

        $initReporteValuesRes = initReporteValues($titles, $model, 'p');
        $starter = isset($agr) ? $agr['calc'] : [];
        $index = 0;

        $calculate = isset($agr) ? recurcive($initReporteValuesRes, $starter, $titles, $index) : [];

    @endphp

    <header class="clearfix">
        <div id="logo">
            <!--  <img src="logo.png"> -->
        </div>

        <div id="client" style="width:35%; word-wrap: break-word; border: 1px solid #fff;">
            <div style="padding: 0 0.5em">
                <h2 class="name">{{ isset($model['purchaser']) ? $model['purchaser']['name'] : '___' }}</h2>
                <div class="name">
                    {{ isset($model['purchaser']) ? $model['purchaser']['subj_name'] : '_________________' }}</div>
                <div class="name">
                    {{ isset($model['purchaser']) ? $model['purchaser']['identification_num'] : '_________________' }}
                </div>
                <div class="address">
                    {{ isset($model['purchaser']) ? $model['purchaser']['subj_address'] : '_________________' }}</div>
            </div>
        </div>



        <div id="company" style="width:65%; border: 1px solid #fff;">

            <div style="padding: 0 0.5em" class="clearfix">
                <div style="float: left; width: 23%; ">
                    <img style="margin-right:50px; max-width: 100%;" src="{{ url('/inservice-new.png') }}">
                </div>

                <div style="float:right; width: 77%;">
                    <h2 class="name"><b>მომწოდებელი:</b> შპს " ინსერვისი "</h2>
                    <div><b>ს/კ:</b> 206346685</div>
                    <div><b>მისამართი:</b> თბილისი, წერეთლის 115ა</div>
                    <div><b>თიბისი ბანკი.</b> # GE96 TB54 2053 6020 1000 03</div>
                    <div><b>საქართველოს ბანკი.</b> # GE94 BG00 0000 0609 8583 91</div>
                    <div><b>კრედო ბანკი.</b> # GE75 CD03 6000 0057 2676 23</div>

                </div>

            </div>
        </div>


    </header>
    <main>
        <div id="details" class="clearfix">

            <div id="invoice">
                <h1>ინვოისი: {{ $model['uuid'] }}</h1>

                <div class="title">
                    <span style="font-weight: 700;">დასახელება:</span>
                    <br />
                    {{ $model['title'] }}
                </div>

                <div class="date">
                    <span style="font-weight: 700;">შეკვეთის თარიღი:</span>
                    {{ date_format(date_create($model['created_at']), 'Y-m-d') }}
                    <br />

                    @if ($model['parent'])
                        <div class="notice">დაკოპირებულია: {{ $model['parent']['uuid'] }}</div>
                    @endif

                    <span style="font-weight: 700; color: green;">(ინვოისი ძალაშია 7 დღე)
                        <br>
                        საგარანტიო ვადა: {{ $model['warranty_period'] }} თვე

                </div>
                {{-- <div ><span style="font-weight: 700;">ინვოისი აქტიურია ერთი კვირის განმავლობაში</span></div> --}}

            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">

            <tr>
                <th class="no">#</th>
                <th style="width: 30%;" class="desc">დასახელება</th>
                <th style="width: 17%;" class="desc">აღწერა</th>
                <th class="desc">ერთეული</th>
                <th class="unit">რაოდენობა</th>
                <th class="unit">მასალის ფასი</th>
                <th class="unit">მომსახურების ფასი</th>
                <th class="total">ჯამი</th>
            </tr>

            <tbody>

                @foreach ($model['category_attributes'] as $key => $item)
                    <tr>
                        <td class="no" style="text-align:center;">{{ sprintf('%02d', $key + 1) }}</td>
                        <td class="desc">{{ $item['pivot']['title'] }}</td>
                        <td class="desc">{{ $item['name'] }}</td>
                        <td class="desc">{{ $item['item'] }}</td>
                        <td style="text-align:center" class="unit">{{ $item['pivot']['qty'] }}</td>
                        <td style="text-align:right;"class="unit">
                            {{ number_format($item['pivot']['price'], 2, '.', '') }}</td>
                        <td style="text-align:right"class="unit">
                            {{ number_format($item['pivot']['service_price'], 2, '.', '') }}</td>
                        <td style="text-align:center" class="total">
                            {{ number_format($item['pivot']['calc'], 2, '.', '') }} ლ</td>
                    </tr>
                @endforeach


            </tbody>


            <tfoot>

                <tr>
                    <td colspan="5"></td>
                    <td colspan="2">სულ:</td>
                    <td>{{ number_format(isset($agr) ? end($calculate)['percenters']['p2'] : 0, 2, '.', '') }} ლ</td>
                </tr>



            </tfoot>
        </table>
        <div id="thanks">მადლობა!</div>


        {{-- <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">Trust The quality of the professionals</div>
      </div> --}}

        <div class="notices-container">
            <div class="first">
                <div id="notices">
                    <div>
                        <h2 class="name">INSERVICE LLC</h2>
                        <div>Tel. : +995 322 242 12 12</div>
                        <div><a href="http://support.inservice.ge">Site : support.inservice.ge</a></div>
                        <div><a href="mailto:service@inservice.ge">Mail : service@inservice.ge</a></div>
                        <div>Georgia, Tbilisi, Chashnagiri st. 8 a</div>
                    </div>
                    <br />
                    <div class="notice">Trust The quality of the professionals</div>

                </div>
            </div>
            <div class="second">
                <div class="sign">
                    <div style="">
                        <img style="border-bottom: 1px solid #000; width:33%;  max-width: 55%;"
                            src="{{ $user->getFirstMediaUrl('credential') }}">
                    </div>

                    <div style="margin-top:10px;">
                        {{ $user->name }}</div>
                </div>
            </div>
        </div>




    </main>
    <div>


    </div>
</body>

</html>
