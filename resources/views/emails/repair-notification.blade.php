<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InService - შეკვეთა</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .section-title {
            font-size: 22px;
            color: #007bff;
            margin: 0 0 20px 0;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }
        .info-line {
            margin: 15px 0;
            padding: 12px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            border-radius: 6px;
        }
        .label {
            font-weight: 600;
            color: #495057;
            display: inline-block;
            min-width: 120px;
        }
        .value {
            color: #333;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
            transform: translateY(-2px);
        }
        .attachment-notice {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .attachment-notice h4 {
            color: #1976d2;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .attachment-notice p {
            color: #424242;
            margin: 0;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>InService</h1>
        </div>
        
        <div class="content">
            @if($status === 'new')
                <h2 class="section-title">შეკვეთა #PR{{ $id }} მიღებულია</h2>
                
                <div class="info-line">
                    <span class="label">სახელი:</span>
                    <span class="value">{{ $repair?->user?->name }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">კომპანიის სახელი:</span>
                    <span class="value">{{ $user->getClient()?->client_name }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">დამატებითი სახელი:</span>
                    <span class="value">{{ $repair?->subject_name }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">მისამართი:</span>
                    <span class="value">{{ $repair?->subject_address }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">დეტალები:</span><br>
                    <div style="margin-top: 8px; padding: 10px; background: white; border-radius: 4px;">
                        {{ $repair?->content }}
                    </div>
                </div>

                <div class="attachment-notice">
                    <h4>📎 თანდართული დოკუმენტები</h4>
                    <p>ამ ელ-წერილთან ერთად თანდართულია ყველა შესაბამისი PDF დოკუმენტი (ინვოისი, რეპორტი, ჩატის ისტორია)</p>
                </div>
                
                <p>დეტალების გასაცნობად ეწვიეთ შეკვეთების გვერდს.</p>
                
                <div class="button-container">
                    <a href="{{ url('https://mondo.inservice.ge/repairs/' . $id) }}" class="button">ნახეთ შეკვეთა</a>
                </div>
                
            @elseif($status === 'completed')
                <h2 class="section-title">თქვენი შეკვეთა #PR{{ $id }} დასრულებულია</h2>
                
                <div class="info-line">
                    <span class="label">სახელი:</span>
                    <span class="value">{{ $repair?->user?->name }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">კომპანიის სახელი:</span>
                    <span class="value">{{ $user->getClient()?->client_name }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">დამატებითი სახელი:</span>
                    <span class="value">{{ $repair?->subject_name }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">მისამართი:</span>
                    <span class="value">{{ $repair?->subject_address }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">შეკვეთის გაფორმების დრო:</span>
                    <span class="value">{{ $repair?->created_at }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">ადგილზე მისვლის დრო ფაქტიური:</span>
                    <span class="value">{{ $repair?->time }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">რეაგირების დასრულების დრო:</span>
                    <span class="value">{{ $repair?->end_time }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">სამუშაოს აღწერა:</span>
                    <span class="value">{{ $repair?->job_description }}</span>
                </div>
                
                <div class="info-line">
                    <span class="label">შინაარსი:</span><br>
                    <div style="margin-top: 8px; padding: 10px; background: white; border-radius: 4px;">
                        {{ $repair?->content }}
                    </div>
                </div>

                <div class="attachment-notice">
                    <h4>📎 თანდართული დოკუმენტები</h4>
                    <p>ამ ელ-წერილთან ერთად თანდართულია ყველა შესაბამისი PDF დოკუმენტი (ინვოისი, რეპორტი, ჩატის ისტორია)</p>
                </div>
                
                <p>დეტალების გასაცნობად ეწვიეთ შეკვეთების გვერდს.</p>
                
                <div class="button-container">
                    <a href="{{ url('https://mondo.inservice.ge/repairs/' . $id) }}" class="button">ნახეთ შეკვეთა</a>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} InService. ყველა უფლება დაცულია.</p>
        </div>
    </div>
</body>
</html>
