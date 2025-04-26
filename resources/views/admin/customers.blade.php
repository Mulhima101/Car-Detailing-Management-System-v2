<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - AutoX</title>
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
                <a class="nav-link active" href="{{ route('admin.customers') }}">
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
            <h2>Customers</h2>
            <a href="{{ route('service.create') }}" class="btn btn-yellow">
                <i class="fas fa-plus-circle me-1"></i> New Service Request
            </a>
        </div>

        <!-- Alerts for success/error messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.customers') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Search by name, email, phone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.customers') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Customer Database</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact Information</th>
                                <th>Address</th>
                                <th>Total Services</th>
                                <th>First Service</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($customers) > 0)
                                @foreach($customers as $customer)
                                    <tr>
                                        <td><strong>{{ $customer->name }}</strong></td>
                                        <td>
                                            <p class="mb-1">{{ $customer->phone }}</p>
                                            <small class="text-muted">{{ $customer->email ?: 'No email provided' }}</small>
                                        </td>
                                        <td>{{ $customer->address }}</td>
                                        <td class="text-center">{{ $customer->carServices_count ?? 0 }}</td>
                                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewCustomerModal-{{ $customer->id }}">
                                                <i class="fas fa-eye me-1"></i> View
                                            </button>
                                            
                                            <!-- View Customer Modal -->
                                            <div class="modal fade" id="viewCustomerModal-{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Customer Details - {{ $customer->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-4">
                                                                <div class="col-md-6">
                                                                    <h6>Contact Information</h6>
                                                                    <p><strong>Name:</strong> {{ $customer->name }}</p>
                                                                    <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                                                                    <p><strong>Email:</strong> {{ $customer->email ?: 'No email provided' }}</p>
                                                                    <p><strong>Address:</strong> {{ $customer->address }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6>Customer Summary</h6>
                                                                    <p><strong>Total Services:</strong> {{ $customer->carServices_count ?? 0 }}</p>
                                                                    <p><strong>Customer Since:</strong> {{ $customer->created_at->format('M d, Y') }}</p>
                                                                    <p><strong>Last Service:</strong> 
                                                                        @if($customer->carServices && $customer->carServices->count() > 0)
                                                                            {{ $customer->carServices->sortByDesc('created_at')->first()->created_at->format('M d, Y') }}
                                                                        @else
                                                                            N/A
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            
                                                            <h6>Service History</h6>
                                                            @if($customer->carServices && $customer->carServices->count() > 0)
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-bordered">
                                                                        <thead>
                                                                            <tr class="table-light">
                                                                                <th>Order ID</th>
                                                                                <th>Vehicle</th>
                                                                                <th>Services</th>
                                                                                <th>Date</th>
                                                                                <th>Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($customer->carServices as $service)
                                                                                <tr>
                                                                                    <td>{{ $service->order_id }}</td>
                                                                                    <td>{{ $service->car_brand }} {{ $service->car_model }}</td>
                                                                                    <td>
                                                                                        @foreach($service->services as $serviceItem)
                                                                                            <span class="badge bg-secondary">{{ is_string($serviceItem) && strpos($serviceItem, ' - ') !== false ? substr($serviceItem, 0, strpos($serviceItem, ' - ')) : $serviceItem }}</span>
                                                                                        @endforeach
                                                                                    </td>
                                                                                    <td>{{ $service->created_at->format('M d, Y') }}</td>
                                                                                    <td>
                                                                                        <span class="status-badge status-{{ $service->status }}">
                                                                                            {{ ucfirst($service->status) }}
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <p class="text-muted">No services recorded for this customer.</p>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No customers found.</td>
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