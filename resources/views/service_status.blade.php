<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Status - AutoX Studio</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('public/css/autox.css') }}" rel="stylesheet">
    <style>
        .progress-track {
            position: relative;
            margin: 40px 0;
        }
        .progress-track .step {
            position: relative;
            padding-bottom: 45px;
            text-align: center;
            z-index: 10;
        }
        .progress-track .step .dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            margin: 0 auto 10px;
            position: relative;
            z-index: 10;
        }
        .progress-track .step .dot.active {
            background-color: var(--autox-yellow);
            border-color: var(--autox-yellow);
        }
        .progress-track .step .dot.complete {
            background-color: #198754;
            border-color: #198754;
        }
        .progress-track .step-label {
            font-weight: bold;
        }
        .progress-track .step-info {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .progress-track .track-line {
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #dee2e6;
            z-index: 1;
        }
        .progress-track .track-line .fill-line {
            position: absolute;
            top: 0;
            left: 0;
            height: 2px;
            background-color: var(--autox-yellow);
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
        <div class="header">
        <div class="container py-5">
        <div class="row">
            <div class="col-12 text-center">
                <img src="{{ asset('public/images/autox-logo.png') }}" alt="AutoX Studio" class="img-fluid" style="max-height: 80px;">
                <p class="text-white-50 mt-2">Premium Car Detailing Services</p>
            </div>
        </div>
    </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2>Service Status</h2>
                            <p class="lead mb-0">Order ID: {{ $carService->order_id }}</p>
                        </div>

                        <!-- Status Progress -->
                        @php
                            $progressPercent = 0;
                            if($carService->status == 'pending') {
                                $progressPercent = 0;
                            } elseif($carService->status == 'in-progress') {
                                $progressPercent = 50;
                            } elseif($carService->status == 'completed') {
                                $progressPercent = 100;
                            }
                        @endphp
                        <div class="progress-track">
                            <div class="track-line">
                                <div class="fill-line" style="width: <?php echo $progressPercent; ?>%;"></div>
                            </div>
                            <div class="row">
                                <div class="col step">
                                    <div class="dot {{ $carService->status != '' ? 'complete' : '' }}">
                                        @if($carService->status != '')
                                            <i class="fas fa-check text-white position-absolute top-50 start-50 translate-middle" style="font-size: 12px;"></i>
                                        @endif
                                    </div>
                                    <div class="step-label">Requested</div>
                                    <div class="step-info">{{ $carService->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="col step">
                                    <div class="dot {{ $carService->status == 'in-progress' || $carService->status == 'completed' ? 'complete' : ($carService->status == 'pending' ? 'active' : '') }}">
                                        @if($carService->status == 'in-progress' || $carService->status == 'completed')
                                            <i class="fas fa-check text-white position-absolute top-50 start-50 translate-middle" style="font-size: 12px;"></i>
                                        @endif
                                    </div>
                                    <div class="step-label">In Progress</div>
                                    <div class="step-info">{{ $carService->start_date ? $carService->start_date->format('M d, Y') : 'Pending' }}</div>
                                </div>
                                <div class="col step">
                                    <div class="dot {{ $carService->status == 'completed' ? 'complete' : ($carService->status == 'in-progress' ? 'active' : '') }}">
                                        @if($carService->status == 'completed')
                                            <i class="fas fa-check text-white position-absolute top-50 start-50 translate-middle" style="font-size: 12px;"></i>
                                        @endif
                                    </div>
                                    <div class="step-label">Completed</div>
                                    <div class="step-info">{{ $carService->completion_date ? $carService->completion_date->format('M d, Y') : 'Pending' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Service Information -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Service Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Status:</strong> <span class="status-badge status-{{ $carService->status }}">{{ ucfirst($carService->status) }}</span></p>
                                        <p><strong>Vehicle:</strong> {{ $carService->car_brand }} {{ $carService->car_model }}</p>
                                        <p><strong>Registration:</strong> {{ $carService->license_plate }} {{ $carService->registration_state ? '('.$carService->registration_state.')' : '' }}</p>
                                        <p><strong>Requested:</strong> {{ $carService->created_at->format('M d, Y') }}</p>
                                        @if($carService->completion_date)
                                            <p><strong>Completed:</strong> {{ $carService->completion_date->format('M d, Y') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Services Requested</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            @foreach($carService->services as $serviceItem)
                                                @php
                                                    $mainService = is_string($serviceItem) && strpos($serviceItem, ' - ') !== false 
                                                        ? substr($serviceItem, 0, strpos($serviceItem, ' - ')) 
                                                        : $serviceItem;
                                                    $subOption = is_string($serviceItem) && strpos($serviceItem, ' - ') !== false 
                                                        ? substr($serviceItem, strpos($serviceItem, ' - ') + 3) 
                                                        : null;
                                                @endphp
                                                <li class="list-group-item">
                                                    <strong>{{ $mainService }}</strong>
                                                    @if($subOption)
                                                        <div class="text-muted mt-1">
                                                            <small>Option: {{ $subOption }}</small>
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Support -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Need Assistance?</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>If you have any questions about your service, please don't hesitate to contact us.</p>
                                        
                                        <div class="mb-3">
                                            <h6><i class="fas fa-phone me-2 text-muted"></i> Phone</h6>
                                            <p>(02) 1234 5678</p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h6><i class="fas fa-envelope me-2 text-muted"></i> Email</h6>
                                            <p>info@autoxstudio.com.au</p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h6><i class="fas fa-clock me-2 text-muted"></i> Business Hours</h6>
                                            <p>Monday - Friday: 8:00 AM - 6:00 PM<br>
                                            Saturday: 9:00 AM - 5:00 PM<br>
                                            Sunday: Closed</p>
                                        </div>

                                        <div class="d-grid gap-2 mt-4">
                                            <a href="mailto:info@autoxstudio.com.au?subject=Inquiry about Order {{ $carService->order_id }}" class="btn btn-outline-primary">
                                                <i class="fas fa-envelope me-1"></i> Email Us
                                            </a>
                                            <a href="tel:0212345678" class="btn btn-outline-success">
                                                <i class="fas fa-phone me-1"></i> Call Us
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('service.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-car me-1"></i> Book Another Service
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>AutoX Studio</h5>
                    <p>Premium car detailing services in Australia</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 AutoX Studio. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>