<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ჩატის ისტორია</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
            text-align: center;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        
        .company-info h1 {
            color: #007bff;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        
        .chat-info {
            background-color: #e9ecef;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        
        .chat-info h2 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 18px;
        }
        
        .info-row {
            margin-bottom: 12px;
        }
        
        .info-label {
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
            color: #495057;
        }
        
        .info-value {
            margin-left: 10px;
            color: #212529;
        }
        
        .messages-container {
            margin-top: 20px;
        }
        
        .message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        
        .message.user-message {
            background-color: #f8f9fa;
            border-left-color: #28a745;
        }
        
        .message.admin-message {
            background-color: #e3f2fd;
            border-left-color: #007bff;
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .message-user {
            color: #495057;
            font-size: 14px;
        }
        
        .message-time {
            color: #6c757d;
            font-size: 12px;
            font-weight: normal;
        }
        
        .message-content {
            color: #212529;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .message-images {
            margin-top: 10px;
            font-style: italic;
            color: #6c757d;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="company-info">
        <h1>INSERVICE LLC</h1>
 
    </div>

    <div class="header">
        <h1>ჩატის ისტორია</h1>
    </div>

    <div class="chat-info">
        <h2>ჩატის ინფორმაცია</h2>
        
        <div class="info-row">
            <span class="info-label">ჩატის ID:</span>
            <div class="info-value">#{{ $chat->id }}</div>
        </div>
        
        <div class="info-row">
            <span class="info-label">მომხმარებელი:</span>
            <div class="info-value">{{ $chat->user->name }}</div>
        </div>
        
        @if($relatedItem)
        <div class="info-row">
            <span class="info-label">{{ $relatedItemType }}:</span>
            <div class="info-value">#{{ $relatedItemType === 'რეაგირება' ? 'QR' : 'PR' }}{{ $relatedItem->id }} - {{ $relatedItem->name }}</div>
        </div>
        
        <div class="info-row">
            <span class="info-label">დამატებითი სახელი:</span>
            <div class="info-value">{{ $relatedItem->subject_name }}</div>
        </div>
        
        <div class="info-row">
            <span class="info-label">მისამართი:</span>
            <div class="info-value">{{ $relatedItem->subject_address }}</div>
        </div>
        @endif
        
        <div class="info-row">
            <span class="info-label">ჩატის შექმნის თარიღი:</span>
            <div class="info-value">{{ $chat->created_at->format('d/m/Y H:i') }}</div>
        </div>
        
        <div class="info-row">
            <span class="info-label">შეტყობინებების რაოდენობა:</span>
            <div class="info-value">{{ $chat->messages->count() }}</div>
        </div>
    </div>

    <div class="messages-container">
        <h2 style="border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px;">შეტყობინებები</h2>
        
        @forelse($chat->messages as $message)
            <div class="message {{ $message->is_admin ? 'admin-message' : 'user-message' }}">
                <div class="message-header">
                    <span class="message-user">
                        {{ $message->user->name }}{{ $message->is_admin ? ' (ადმინისტრატორი)' : ' (კლიენტი)' }}
                    </span>
                    <span class="message-time">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                </div>
                
                <div class="message-content">
                    {{ $message->message }}
                </div>
                
                @if($message->getMedia('chat_images')->count() > 0)
                    <div class="message-images">
                        <strong>სურათები:</strong> {{ $message->getMedia('chat_images')->count() }} ფაილი მიმაგრებული
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align: center; color: #6c757d; font-style: italic; padding: 40px;">
                არ არის შეტყობინებები
            </div>
        @endforelse
    </div>

    <div class="footer">
        <div><strong>InService LLC</strong></div>
        <div>ჩატის ისტორია გენერირებულია: {{ now()->format('d/m/Y H:i') }}</div>
 
    </div>
 
</body>
</html>
