<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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

        body {
            font-family: DejaVu Sans, sans-serif, 'BPG WEB 001 Caps';


            color: black;
            background: #FFFFFF;
            font-size: 0.7em;
        }

        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        .s1 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 7.5pt;
        }

        .s2 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 7.5pt;
        }

        .s3 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 5pt;
        }

        .s4 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 5pt;
        }

        .s5 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 8pt;
        }

        .s6 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 8pt;
        }

        .s7 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 4.5pt;
        }

        .s8 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 4.5pt;
        }

        .s9 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 5pt;
        }

        .s10 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 5pt;
        }

        p {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 4pt;
            margin: 0pt;
        }

        .s11 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 4pt;
        }

        .s12 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 1pt;
        }

        table,
        tbody {
            vertical-align: top;
            overflow: visible;
        }

        .inner-text {
            padding-top: 3pt;
            padding-left: 2pt;
            text-indent: 0pt;
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: space-between;
        }

        .left,
        .right {
            width: 45%;
            /* Adjust width as needed */
            border: 1px solid #ccc;
            /* Just for visualization */
            padding: 10px;
        }
    </style>
</head>

<body>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <table style="width:100%;border-collapse:collapse;padding:5pt" cellspacing="0">
        <tr style="height:13pt">
            <td style="width:194pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:2pt"
                colspan="4">
                <p class="s1" style="padding-left: 55pt;text-indent: 0pt;text-align: left;">შპს <span
                        class="s2">&quot;</span>ინსერვისი<span class="s2">&quot; </span>აქტი <span
                        class="s2">№ {{ $model->uuid }}</span></p>
            </td>
            <td
                style="width:54pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    თარიღი<span class="s4">:</span></p>
            </td>
            <td
                style="width:53pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:1pt">
                <p style="font-size:15px; padding-top:0px;" class="inner-text">
                    {{ \Carbon\Carbon::parse($model->created_at)->day }}
                </p>
            </td>
            <td
                style="width:62pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:1pt">
                <p style="font-size:15px; padding-top:0px;" class="inner-text">

                    {{ \Carbon\Carbon::parse($model->created_at)->month }}
                </p>
            </td>
            <td
                style="width:52pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:2pt">
                <p class="s5" style="padding-left: 13pt;text-indent: 0pt;text-align: left;">
                    {{ \Carbon\Carbon::parse($model->created_at)->year }} <span class="s6">წ</span>.</p>
            </td>
        </tr>
        <tr style="height:14pt">
            <td style="width:102pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="2">
                <p class="s3" style="padding-top: 2pt;padding-left: 18pt;text-indent: 0pt;text-align: left;">
                    მომსახურების დანიშნულება</p>
            </td>
            <td style="width:92pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="2" rowspan="2">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
                <p class="s3" style="text-indent: 0pt;text-align: center;">მოწყობილობის დასახელება</p>
            </td>
            <td
                style="width:54pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    ობიექტი<span class="s4">:</span></p>
            </td>
            <td style="width:167pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="3">
                <p class="inner-text">
                    {{ $model->response?->name }}
                </p>
            </td>
        </tr>
        <tr style="height:14pt">
            <td
                style="width:89pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    რეაგირება</p>
            </td>
            <td
                style="width:13pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt">
                <p style="font-size:20px; padding-top:0px;" class="inner-text">
                    {{ $model->response?->user?->manager_type == 1 ? '✓' : '' }}
                </p>
            </td>
            <td
                style="width:54pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    მისამართი<span class="s4">:</span></p>
            </td>
            <td style="width:167pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="3">
                <p class="inner-text">
                    {{ $model->response?->subject_address }}
                </p>
            </td>
        </tr>
        <tr style="height:14pt">
            <td
                style="width:89pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    სარემონტო სამუშაო</p>
            </td>
            <td
                style="width:13pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt">
                <p style="font-size:20px; padding-top:0px;" class="inner-text">
                    {{ $model->response?->user?->manager_type == 2 ? '✓' : '' }}
                </p>
            </td>
            <td
                style="width:80pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">
                    კონდიცირება</p>
            </td>
            <td
                style="width:12pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt">
                <p style="font-size:20px; padding-top:0px;" class="inner-text">
                    {{ $model->response?->device_type == 1 ? '✓' : '' }}
                </p>
            </td>
            <td
                style="width:54pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    რეაგირების დრო</p>
            </td>
            <td
                style="width:53pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="inner-text">
                    {{ $model->created_at }}
                </p>
            </td>
            <td
                style="width:62pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s7"
                    style="padding-left: 17pt;padding-right: 11pt;text-indent: -4pt;line-height: 6pt;text-align: left;">
                    სამუშაოს ხანგრძ<span class="s8">. </span>სამუშაო დღე</p>
            </td>
            <td
                style="width:52pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
            </td>
        </tr>
        <tr style="height:14pt">
            <td
                style="width:89pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">გეგმიური
                    სასერვისო სამუშაო</p>
            </td>
            <td
                style="width:13pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt">
                <p style="font-size:20px; padding-top:0px;" class="inner-text">
                    {{ $model->response?->user?->manager_type == 3 ? '✓' : '' }}
                </p>
            </td>
            <td
                style="width:80pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">
                    ვენტილაცია</p>
            </td>
            <td
                style="width:12pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt">
                <p style="font-size:20px; padding-top:0px;" class="inner-text">
                    {{ $model->response?->device_type == 2 ? '✓' : '' }}
                </p>
            </td>
            <td
                style="width:54pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    მდებარეობა</p>
            </td>
            <td style="width:167pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:2pt"
                colspan="3">
                <p class="inner-text">
                    {{ $model->location?->name }}
                </p>
            </td>
        </tr>
        <tr style="height:13pt">
            <td
                style="width:89pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">სხვა
                    ტიპის სამუშაო</p>
            </td>
            <td
                style="width:13pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:2pt">
                <p style="font-size:20px; padding-top:0px;" class="inner-text">
                    {{ $model->response?->user?->manager_type == 4 ? '✓' : '' }}
                </p>
            </td>
            <td
                style="width:80pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">
                    გათბობა</p>
            </td>
            <td
                style="width:12pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:2pt">
                <p style="font-size:20px; padding-top:0px;" class="inner-text">
                    {{ $model->response?->device_type == 3 ? '✓' : '' }}
                </p>
            </td>
            <td style="width:221pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="4">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    შენიშვნა<span class="s4">: {{ $model->note }}</span></p>
            </td>
        </tr>
        <tr style="height:14pt">
            <td
                style="width:89pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    მოწყობილობის სახეობა</p>
            </td>
            <td style="width:105pt;border-top-style:solid;border-top-width:2pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="3">
                <p class="inner-text">
                    {{ $model->deviceType->name }}
                </p>
            </td>
            <td style="width:221pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="4">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
            </td>
        </tr>
        <tr style="height:14pt">
            <td
                style="width:89pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">ბრენდი
                </p>
            </td>
            <td style="width:105pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="3">
                <p class="inner-text">
                    {{ $model->deviceBrand->name }}
                </p>
            </td>
            <td style="width:221pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="4">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
            </td>
        </tr>
        <tr style="height:14pt">
            <td
                style="width:89pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    მოდელი<span class="s4">/</span>სერიული <span class="s4">№</span></p>
            </td>
            <td style="width:105pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="3">
                <p class="inner-text">
                    {{ $model->device_model }}
                </p>
            </td>
            <td style="width:221pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:2pt"
                colspan="4">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
            </td>
        </tr>
        <tr style="height:14pt">
            <td
                style="width:89pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:1pt">
                <p class="s3" style="padding-top: 3pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    საინვენტარო კოდი</p>
            </td>
            <td style="width:105pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:2pt"
                colspan="3">
                <p class="inner-text">
                    {{ $model->inventory_code }}
                </p>
            </td>
            <td style="width:221pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:2pt;border-bottom-style:solid;border-bottom-width:2pt;border-right-style:solid;border-right-width:2pt"
                colspan="4">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
            </td>
        </tr>
    </table>

    <table style="padding:10pt;width: 100%">

        <tr>
            <td>დამკვეთი: <b>{{ $model->response?->subject_name }}</b></td>
            <td style="text-align: right">შემსრულებელი: <b>{{ $model->response?->performer?->name }}</b></td>
        </tr>
        <tr>
            <td><b>{{ $model->position . ' , ' . $model->client_name }}</b></td>
            <td style="text-align: right">ხელმოწერა:</td>

        </tr>
        <tr>
            <td>ხელმოწერა:</td>

        </tr>
        <tr>
            <td><img src="{{ $model->signature }}" width="250px" alt=""></td>
            <td style="text-align: right">{{ $model->response?->performer?->signature() }}</td>
        </tr>

    </table>

</body>

</html>
