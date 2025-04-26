<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Details - AutoX</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('public/css/autox.css') }}" rel="stylesheet">
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #212529;
            color: white;
            padding-top: 20px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .detail-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .detail-card .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }
        .timeline-item:before {
            content: '';
            position: absolute;
            top: 0;
            left: -23px;
            width: 2px;
            height: 100%;
            background-color: #dee2e6;
        }
        .timeline-item:last-child:before {
            height: 50%;
        }
        .timeline-dot {
            position: absolute;
            left: -30px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
        }
        .dot-pending {
            background-color: #ffc107;
        }
        .dot-in-progress {
            background-color: #0d6efd;
        }
        .dot-completed {
            background-color: #198754;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="px-3 mb-4">
        <img src="{{ asset('public/images/autox-logo.png') }}" alt="AutoX" class="img-fluid" style="max-height: 50px;">
            <p class="text-white-50 small">Admin Dashboard</p>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.customers') }}">
                    <i class="fas fa-users me-2"></i> Customers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.services') }}">
                    <i class="fas fa-car me-2"></i> All Services
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.completed-services') }}">
                    <i class="fas fa-check-circle me-2"></i> Completed Services
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.settings') }}">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
            </li>
            <li class="nav-item mt-5">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Service Details</h2>
                <p class="text-muted mb-0">Order ID: {{ $service->order_id }}</p>
            </div>
            <div>
                <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i> Back to Services
                </a>
                <button type="button" class="btn btn-yellow" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                    <i class="fas fa-edit me-1"></i> Update Status
                </button>
            </div>
        </div>

        <!-- Alerts for success/error messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <!-- Service Information Card -->
                <div class="card detail-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Service Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Status:</strong> <span class="status-badge status-{{ $service->status }}">{{ ucfirst($service->status) }}</span></p>
                                <p><strong>Created:</strong> {{ $service->created_at->format('M d, Y, h:i A') }}</p>
                                <p><strong>Started:</strong> {{ $service->start_date ? $service->start_date->format('M d, Y, h:i A') : 'Not started' }}</p>
                                @if($service->completion_date)
                                    <p><strong>Completed:</strong> {{ $service->completion_date->format('M d, Y, h:i A') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p><strong>Vehicle:</strong> {{ $service->car_brand }} {{ $service->car_model }}</p>
                                <p><strong>Registration:</strong> {{ $service->license_plate }} {{ $service->registration_state ? '('.$service->registration_state.')' : '' }}</p>
                                <p><strong>Color:</strong> {{ $service->color ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Card -->
                <div class="card detail-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tools me-2"></i> Services Requested</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($service->services as $serviceItem)
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

                        @if($service->notes)
                            <div class="mt-4">
                                <h6>Notes:</h6>
                                <p class="mb-0">{{ $service->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="card detail-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i> Service Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot dot-pending"></div>
                                <h6>Service Requested</h6>
                                <p class="text-muted">{{ $service->created_at->format('M d, Y, h:i A') }}</p>
                            </div>
                            
                            @if($service->status == 'in-progress' || $service->status == 'completed')
                                <div class="timeline-item">
                                    <div class="timeline-dot dot-in-progress"></div>
                                    <h6>Service Started</h6>
                                    <p class="text-muted">{{ $service->start_date ? $service->start_date->format('M d, Y, h:i A') : 'Not recorded' }}</p>
                                </div>
                            @endif
                            
                            @if($service->status == 'completed')
                                <div class="timeline-item">
                                    <div class="timeline-dot dot-completed"></div>
                                    <h6>Service Completed</h6>
                                    <p class="text-muted">{{ $service->completion_date ? $service->completion_date->format('M d, Y, h:i A') : 'Not recorded' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Customer Card -->
                <div class="card detail-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i> Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <h6>{{ $service->customer->name }}</h6>
                        <p class="mb-2"><i class="fas fa-phone me-2 text-muted"></i> {{ $service->customer->phone }}</p>
                        <p class="mb-2"><i class="fas fa-envelope me-2 text-muted"></i> {{ $service->customer->email ?: 'No email provided' }}</p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-muted"></i> {{ $service->customer->address }}</p>
                        <hr>
                        <p class="mb-1"><strong>Customer Since:</strong> {{ $service->customer->created_at->format('M d, Y') }}</p>
                        <p class="mb-0"><strong>Total Services:</strong> {{ $service->customer->carServices()->count() }}</p>
                        
                        <div class="mt-3">
                            <a href="{{ route('admin.customers') }}?search={{ urlencode($service->customer->name) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-history me-1"></i> View Customer History
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card detail-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#emailCustomerModal">
                                <i class="fas fa-envelope me-1"></i> Email Customer
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="fas fa-check-circle me-1"></i> Update Status
                            </button>
                            <a href="{{ route('service.create') }}?customer_id={{ $service->customer->id }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-plus-circle me-1"></i> New Service for This Customer
                            </a>
                            <!-- Print Service Details button removed -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Status Modal -->
        <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Status - {{ $service->order_id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.service.update-status', $service->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" {{ $service->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in-progress" {{ $service->status == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $service->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="alert alert-info">
                                <small><i class="fas fa-info-circle me-1"></i> Marking as "Completed" will automatically set the completion date to the current time.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Email Customer Modal -->
        <div class="modal fade" id="emailCustomerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Email Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="emailTo" class="form-label">To</label>
                                <input type="email" class="form-control" id="emailTo" value="{{ $service->customer->email }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="emailSubject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="emailSubject" name="subject" value="AutoX Studio - Regarding Your {{ $service->car_brand }} {{ $service->car_model }}">
                            </div>
                            <div class="mb-3">
                                <label for="emailBody" class="form-label">Message</label>
                                <textarea class="form-control" id="emailBody" name="message" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Send Email</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>