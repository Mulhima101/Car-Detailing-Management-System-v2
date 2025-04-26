<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $customSubject }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
            @if($logoBase64)
                <img src="data:image/png;base64,{{ $logoBase64 }}" alt="AutoX Studio" style="max-height: 60px;">
            @else
                <h1 style="color: #212529;">
                    <span style="color: #212529;">Auto</span><span style="color: #FFCE00;">X</span> 
                    <span style="color: #212529;">Studio</span>
                </h1>
            @endif
        </div>
        
        <p>Hello {{ $carService->customer->name }},</p>
        
        <div style="margin: 20px 0;">
            {!! nl2br(e($customMessage)) !!}
        </div>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0;">Service Details</h3>
            <p><strong>Order ID:</strong> {{ $carService->order_id }}</p>
            <p><strong>Vehicle:</strong> {{ $carService->car_brand }} {{ $carService->car_model }}</p>
            <p><strong>Status:</strong> {{ ucfirst($carService->status) }}</p>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ url('/service/status/' . $carService->id) }}" style="display: inline-block; background-color: #FFCE00; color: #212529; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                View Service Details
            </a>
        </div>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #777; font-size: 12px;">
            <p>Thank you for choosing AutoX Studio.</p>
            <p>&copy; {{ date('Y') }} AutoX Studio. All rights reserved.</p>
        </div>
    </div>
</body>
</html>