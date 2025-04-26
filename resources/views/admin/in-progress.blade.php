@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>In-Progress Services</h2>
    <a href="{{ route('service.form') }}" class="btn btn-outline-dark" target="_blank">
        <i class="bi bi-plus-circle me-2"></i>New Service Request
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-autox-yellow">
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
                    @forelse($carServices as $service)
                        <tr>
                            <td>{{ $service->order_id }}</td>
                            <td>
                                <strong>{{ $service->customer->name }}</strong><br>
                                <small class="text-muted">{{ $service->customer->phone }}</small>
                            </td>
                            <td>{{ $service->car_brand }} {{ $service->car_model }}</td>
                            <td>
                                @foreach(json_decode($service->services_requested) as $requestedService)
                                    <span class="badge bg-secondary mb-1">{{ $requestedService }}</span><br>
                                @endforeach
                            </td>
                            <td>{{ $service->service_started_date ? \Carbon\Carbon::parse($service->service_started_date)->format('M d, Y H:i') : 'Not started' }}</td>
                            <td>
                                @if($service->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($service->status === 'in_progress')
                                    <span class="badge bg-primary">In Progress</span>
                                @else
                                    <span class="badge bg-success">Completed</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal{{ $service->id }}">
                                    <i class="bi bi-pencil-square"></i> Update
                                </button>
                                
                                <!-- Update Modal -->
                                <div class="modal fade" id="updateModal{{ $service->id }}" tabindex="-1" aria-labelledby="updateModalLabel{{ $service->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('service.update', $service) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title" id="updateModalLabel{{ $service->id }}">Update Service: {{ $service->order_id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select class="form-select" id="status" name="status" required>
                                                            <option value="pending" {{ $service->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="in_progress" {{ $service->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                            <option value="completed" {{ $service->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="notes" class="form-label">Notes</label>
                                                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ $service->notes }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <p class="mb-1"><strong>Customer:</strong> {{ $service->customer->name }}</p>
                                                        <p class="mb-1"><strong>Phone:</strong> {{ $service->customer->phone }}</p>
                                                        <p class="mb-1"><strong>Vehicle:</strong> {{ $service->car_brand }} {{ $service->car_model }}</p>
                                                        <p class="mb-0"><strong>Request Date:</strong> {{ \Carbon\Carbon::parse($service->created_at)->format('M d, Y') }}</p>
                                                    </div>
                                                    
                                                    @if($service->notes)
                                                    <div class="alert alert-info mt-3">
                                                        <strong>Customer Service Description:</strong><br>
                                                        {{ $service->notes }}
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-autox">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="mb-0 text-muted">No in-progress services found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection