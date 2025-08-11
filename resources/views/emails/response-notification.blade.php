<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InService - შეკვეთა</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button.green {
            background-color: #28a745;
        }
        .button-container {
            margin: 20px 0;
        }
        .info-line {
            margin: 10px 0;
            padding: 5px 0;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>InService</h1>
    </div>
    
    <div class="content">
        @if($status === 'new')
            <h2>შეკვეთა #QR{{ $id }} მიღებულია</h2>
            
            <div class="info-line">
                <span class="label">სახელი:</span> {{ $response?->user?->name }}
            </div>
            
            <div class="info-line">
                <span class="label">კომპანიის სახელი:</span> {{ $response->name }}
            </div>
            
            <div class="info-line">
                <span class="label">დამატებითი სახელი:</span> {{ $response?->subject_name }}
            </div>
            
            <div class="info-line">
                <span class="label">მისამართი:</span> {{ $response?->subject_address }}
            </div>

            <div class="info-line">
                <span class="label">შეკვეთის გაფორმების დრო:</span> {{ $response?->created_at }}
            </div>

            <div class="info-line">
                <span class="label">შინაარსი:</span><br>
                {{ $response?->content }}
            </div>
            
            <p>დეტალების გასაცნობად ეწვიეთ შეკვეთების გვერდს.</p>
            
            <a href="{{ url('https://mondo.inservice.ge/orders/' . $id) }}" class="button">ნახეთ შეკვეთა</a>
            
        @elseif($status === 'completed')
            <h2>თქვენი შეკვეთა #QR{{ $id }} დასრულებულია</h2>
            
            <div class="info-line">
                <span class="label">სახელი:</span> {{ $response?->user?->name }}
            </div>
            
            <div class="info-line">
                <span class="label">კომპანიის სახელი:</span> {{ $response->name }}
            </div>
            
            <div class="info-line">
                <span class="label">დამატებითი სახელი:</span> {{ $response?->subject_name }}
            </div>
            
            <div class="info-line">
                <span class="label">მისამართი:</span> {{ $response?->subject_address }}
            </div>
            
            <div class="info-line">
                <span class="label">შეკვეთის გაფორმების დრო:</span> {{ $response?->created_at }}
            </div>
            
            <div class="info-line">
                <span class="label">ადგილზე მისვლის დრო ფაქტიური:</span> {{ $response?->time }}
            </div>
            
            <div class="info-line">
                <span class="label">რეაგირების დასრულების დრო:</span> {{ $response?->end_time }}
            </div>
            
            <div class="info-line">
                <span class="label">სამუშაოს აღწერა:</span> {{ $response?->act?->note ?? 'არ არის მითითებული' }}
            </div>
            
            <div class="info-line">
                <span class="label">შინაარსი:</span><br>
                {{ $response?->content }}
            </div>


            <p><small>დეტალები:</small></p>
            
            @if($response?->chat())
                <div class="button-container">
                    <a href="{{ url('/chats/history/' . $response->chat()->id . '/pdf') }}" class="button green">
                        📄 ჩატის ისტორიის ნახვა
                    </a>
                </div>
            @endif
            
            <a href="{{ url('https://mondo.inservice.ge/orders/' . $id) }}" class="button">ნახეთ შეკვეთა</a>
        @endif
    </div>
</body>
</html>
