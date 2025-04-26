<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - AutoX Studio</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('public/css/autox.css') }}" rel="stylesheet">
    <style>
        .brand-name {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .brand-x {
            color: #FFCE00;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <div class="brand-logo">
            <img src="{{ asset('public/images/autox-logo.png') }}" alt="AutoX Service">
        </div>
        <p class="mt-2">Premium Car Detailing Services</p>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#FFCE00" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </div>
                        <h2 class="mb-4">Thank You!</h2>
                        <p class="lead mb-4">Your service request has been submitted successfully.</p>
                        
                        <div class="alert alert-info">
                            <p class="mb-1"><strong>Order ID:</strong> {{ $carService->order_id }}</p>
                            <p class="mb-1"><strong>Vehicle:</strong> {{ $carService->car_brand }} {{ $carService->car_model }}</p>
                            <p class="mb-0"><strong>Services:</strong> {{ implode(', ', $carService->services) }}</p>
                        </div>
                        
                        <p>We'll contact you shortly to confirm your appointment details.</p>
                        
                        <div class="mt-4">
                            @if(auth()->check())
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                            @else
                                <a href="{{ route('service.create') }}" class="btn btn-outline-dark">Return to Service Form</a>
                                <a href="{{ route('login') }}" class="btn btn-primary ml-2">Login to Dashboard</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer text-center mt-5">
        <p>© 2025 AutoX Studio. All rights reserved.</p>
        <p>Premium car detailing services in Australia</p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>