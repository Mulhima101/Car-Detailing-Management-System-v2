@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center page-title">
    <h2>All Services</h2>
    <a href="{{ route('service.create') }}" class="btn btn-yellow">
        <i class="fas fa-plus-circle me-1"></i> New Service Request
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filters</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.services') }}" method="GET" class="row align-items-end">
            <div class="col-md-3 mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-5 mb-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="Order ID, Customer, Car..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2 mb-3">
                <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Service Records</h5>
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
                        <th>Status</th>
                        <th>Dates</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($services ?? []) > 0)
                        @foreach($services as $service)
                            <tr>
                                <td>{{ $service->order_id }}</td>
                                <td>
                                    <div>{{ $service->customer->name }}</div>
                                    <small class="text-muted">{{ $service->customer->email }}</small>
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
                                <td>
                                    <span class="status-badge status-{{ $service->status }}">
                                        {{ ucfirst($service->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div><strong>Created:</strong> {{ $service->created_at->format('M d, Y') }}</div>
                                    @if($service->start_date)
                                        <div><strong>Started:</strong> {{ $service->start_date->format('M d, Y') }}</div>
                                    @endif
                                    @if($service->completion_date)
                                        <div><strong>Completed:</strong> {{ $service->completion_date->format('M d, Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.service.details', $service->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#updateStatusModal-{{ $service->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
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
                            <td colspan="7" class="text-center py-4">No services found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection