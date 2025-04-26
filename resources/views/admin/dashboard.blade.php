<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AutoX</title>
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
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            background-color: white;
            height: 100%;
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--autox-yellow);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
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
                <a class="nav-link active" href="{{ route('admin.dashboard') }}">
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
            <h2>Dashboard</h2>
            <a href="{{ route('service.create') }}" class="btn btn-yellow">
                <i class="fas fa-plus-circle me-1"></i> New Service Request
            </a>
        </div>
        
        <!-- Dashboard Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="stat-number">{{ $stats['total_services'] }}</div>
                    <div class="stat-label">Total Services</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number">{{ $stats['in_progress_services'] }}</div>
                    <div class="stat-label">In Progress</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number">{{ $stats['completed_services'] }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">{{ $stats['total_customers'] }}</div>
                    <div class="stat-label">Customers</div>
                </div>
            </div>
        </div>

        <!-- Alerts for success/error messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- In-Progress Services Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Current In-Progress Services</h5>
                <a href="{{ route('admin.services') }}" class="btn btn-sm btn-outline-primary">View All Services</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Services</th>
                                <th>Started</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($inProgressServices) > 0)
                                @foreach($inProgressServices as $service)
                                    <tr>
                                        <td>{{ $service->order_id }}</td>
                                        <td>
                                            <div>{{ $service->customer->name }}</div>
                                            <small class="text-muted">{{ $service->customer->phone }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $service->car_brand }} {{ $service->car_model }}</div>
                                            <small class="text-muted">{{ $service->license_plate }}</small>
                                        </td>
                                        <td>
                                            @foreach($service->services as $serviceItem)
                                                <span class="badge bg-secondary">{{ is_string($serviceItem) && strpos($serviceItem, ' - ') !== false ? substr($serviceItem, 0, strpos($serviceItem, ' - ')) : $serviceItem }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $service->start_date ? $service->start_date->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $service->status }}">
                                                {{ ucfirst($service->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewServiceModal-{{ $service->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#updateStatusModal-{{ $service->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                            
                                            <!-- View Service Modal -->
                                            <div class="modal fade" id="viewServiceModal-{{ $service->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Service Details - {{ $service->order_id }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h6>Customer Information</h6>
                                                                    <p><strong>Name:</strong> {{ $service->customer->name }}</p>
                                                                    <p><strong>Phone:</strong> {{ $service->customer->phone }}</p>
                                                                    <p><strong>Email:</strong> {{ $service->customer->email }}</p>
                                                                    <p><strong>Address:</strong> {{ $service->customer->address }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6>Vehicle Information</h6>
                                                                    <p><strong>Brand & Model:</strong> {{ $service->car_brand }} {{ $service->car_model }}</p>
                                                                    <p><strong>Registration:</strong> {{ $service->license_plate }} {{ $service->registration_state ? '('.$service->registration_state.')' : '' }}</p>
                                                                    <p><strong>Color:</strong> {{ $service->color ?? 'N/A' }}</p>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h6>Service Details</h6>
                                                                    <p><strong>Services:</strong></p>
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
                                                                    <p class="mt-3"><strong>Notes:</strong> {{ $service->notes ?? 'N/A' }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6>Status Information</h6>
                                                                    <p><strong>Current Status:</strong> <span class="status-badge status-{{ $service->status }}">{{ ucfirst($service->status) }}</span></p>
                                                                    <p><strong>Started:</strong> {{ $service->start_date ? $service->start_date->format('M d, Y, h:i A') : 'Not started' }}</p>
                                                                    @if($service->completion_date)
                                                                        <p><strong>Completed:</strong> {{ $service->completion_date->format('M d, Y, h:i A') }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Update Status Modal -->
                                            <div class="modal fade" id="updateStatusModal-{{ $service->id }}" tabindex="-1" aria-hidden="true">
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
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="mb-0 text-muted">No in-progress services found.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>