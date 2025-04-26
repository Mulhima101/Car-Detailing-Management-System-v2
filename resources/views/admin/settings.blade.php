@extends('admin.layout')

@section('content')
<div class="page-title">
    <h2>Settings</h2>
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

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-cog me-2"></i> Account Settings
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.profile') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bell me-2"></i> Notification Settings
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.notifications') }}" method="POST">
                    @csrf
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="statusUpdates" name="notification_status_updates" 
                            {{ isset($settings['notification_status_updates']) && $settings['notification_status_updates'] ? 'checked' : '' }}>
                        <label class="form-check-label" for="statusUpdates">Service Status Updates</label>
                        <div class="text-muted small mt-1">Receive notifications when a service status changes</div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="completionReminders" name="notification_completion_reminders" 
                            {{ isset($settings['notification_completion_reminders']) && $settings['notification_completion_reminders'] ? 'checked' : '' }}>
                        <label class="form-check-label" for="completionReminders">Service Completion Reminders</label>
                        <div class="text-muted small mt-1">Receive reminders for services that have been in progress for a long time</div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="marketingEmails" name="notification_marketing_emails"
                            {{ isset($settings['notification_marketing_emails']) && $settings['notification_marketing_emails'] ? 'checked' : '' }}>
                        <label class="form-check-label" for="marketingEmails">Marketing Emails</label>
                        <div class="text-muted small mt-1">Receive promotional emails and service updates</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Notification Settings</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-lock me-2"></i> Change Password
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.password') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        @error('password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-cogs me-2"></i> System Settings
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.system') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="companyName" name="company_name" value="{{ $settings['company_name'] ?? 'AutoX Studio' }}">
                        @error('company_name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="companyEmail" class="form-label">Company Email</label>
                        <input type="email" class="form-control" id="companyEmail" name="company_email" value="{{ $settings['company_email'] ?? 'info@autoxstudio.com.au' }}">
                        @error('company_email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="companyPhone" class="form-label">Company Phone</label>
                        <input type="text" class="form-control" id="companyPhone" name="company_phone" value="{{ $settings['company_phone'] ?? '(02) 1234 5678' }}">
                        @error('company_phone')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="reminderDays" class="form-label">Send Reminders (Days before completion)</label>
                        <input type="number" class="form-control" id="reminderDays" name="reminder_days" value="{{ $settings['reminder_days'] ?? 3 }}" min="1" max="14">
                        <div class="text-muted small mt-1">Number of days a service must be in progress before sending reminder</div>
                        @error('reminder_days')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Save System Settings</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-envelope me-2"></i> Email Processing
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        @php
                            $isWorkerRunning = app(\App\Http\Controllers\Admin\SettingsController::class)->isQueueWorkerRunning();
                            $pendingJobs = DB::table('jobs')->count();
                        @endphp
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="me-2">Notification Service Status:</span> 
                            @if($isWorkerRunning)
                                <span class="badge bg-success">Running</span>
                            @else
                                <span class="badge bg-danger">Stopped</span>
                            @endif
                        </div>
                        
                        <p class="text-muted mb-2">Pending Emails: {{ $pendingJobs }}</p>
                        <p>You can manually process emails or start/stop the automatic notification service.</p>
                    </div>
                    <div class="col-md-4">
                        <div class="d-grid gap-2">
                            <form action="{{ route('admin.settings.process-queue') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-paper-plane me-2"></i> Process Emails Now
                                </button>
                            </form>
                            
                            @if($isWorkerRunning)
                                <form action="{{ route('admin.settings.stop-queue') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-stop-circle me-2"></i> Stop Notification Service
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.settings.start-queue') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-play-circle me-2"></i> Start Notification Service
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-envelope me-2"></i> Email Templates
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Email templates are used for automated notifications sent to customers and admins.
                </div>
                
                <ul class="nav nav-tabs" id="emailTemplatesTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="service-status-tab" data-bs-toggle="tab" data-bs-target="#service-status" type="button" role="tab" aria-controls="service-status" aria-selected="true">Service Status</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="service-reminder-tab" data-bs-toggle="tab" data-bs-target="#service-reminder" type="button" role="tab" aria-controls="service-reminder" aria-selected="false">Service Reminder</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="admin-alert-tab" data-bs-toggle="tab" data-bs-target="#admin-alert" type="button" role="tab" aria-controls="admin-alert" aria-selected="false">Admin Alert</button>
                    </li>
                </ul>
                
                <div class="tab-content p-3 border border-top-0 rounded-bottom" id="emailTemplatesTabContent">
                    <div class="tab-pane fade show active" id="service-status" role="tabpanel" aria-labelledby="service-status-tab">
                        <h5 class="mb-3">Service Status Update Email</h5>
                        <p class="text-muted">This email is sent to customers when their service status changes.</p>
                        
                        <form action="{{ route('admin.settings.email-templates') }}" method="POST">
                            @csrf
                            <input type="hidden" name="template_type" value="service_status">
                            <div class="mb-3">
                                <label for="statusSubject" class="form-label">Email Subject</label>
                                <input type="text" class="form-control" id="statusSubject" name="subject" value="{{ $settings['email_service_status_subject'] ?? 'AutoX Studio: Service Status Update' }}">
                            </div>
                            <div class="mb-3">
                                <label for="statusTemplate" class="form-label">Email Template</label>
                                <textarea class="form-control" id="statusTemplate" name="template" rows="10">{{ $settings['email_service_status_template'] ?? "Hello {customer_name},\n\nYour service status has been updated.\n\nService Details:\nOrder ID: {order_id}\nVehicle: {car_brand} {car_model}\nStatus: {status}\n\nThank you for choosing AutoX Studio!" }}</textarea>
                            </div>
                            <div class="text-muted mb-3">
                                <strong>Available variables:</strong> {customer_name}, {order_id}, {car_brand}, {car_model}, {status}, {status_message}
                            </div>
                            <button type="submit" class="btn btn-primary">Save Template</button>
                        </form>
                    </div>
                    
                    <div class="tab-pane fade" id="service-reminder" role="tabpanel" aria-labelledby="service-reminder-tab">
                        <h5 class="mb-3">Service Reminder Email</h5>
                        <p class="text-muted">This email is sent to customers as a reminder for services that have been in progress for some time.</p>
                        
                        <form action="{{ route('admin.settings.email-templates') }}" method="POST">
                            @csrf
                            <input type="hidden" name="template_type" value="service_reminder">
                            <div class="mb-3">
                                <label for="reminderSubject" class="form-label">Email Subject</label>
                                <input type="text" class="form-control" id="reminderSubject" name="subject" value="{{ $settings['email_service_reminder_subject'] ?? 'AutoX Studio: Your Vehicle Will Be Ready Soon' }}">
                            </div>
                            <div class="mb-3">
                                <label for="reminderTemplate" class="form-label">Email Template</label>
                                <textarea class="form-control" id="reminderTemplate" name="template" rows="10">{{ $settings['email_service_reminder_template'] ?? "Hello {customer_name},\n\nWe wanted to let you know that your vehicle has been in our service center for a few days. We're working to complete your service as soon as possible.\n\nService Details:\nOrder ID: {order_id}\nVehicle: {car_brand} {car_model}\nStatus: {status}\nStarted: {start_date}\n\nThank you for your patience and for choosing AutoX Studio!" }}</textarea>
                            </div>
                            <div class="text-muted mb-3">
                                <strong>Available variables:</strong> {customer_name}, {order_id}, {car_brand}, {car_model}, {status}, {start_date}
                            </div>
                            <button type="submit" class="btn btn-primary">Save Template</button>
                        </form>
                    </div>
                    
                    <div class="tab-pane fade" id="admin-alert" role="tabpanel" aria-labelledby="admin-alert-tab">
                        <h5 class="mb-3">Admin Alert Email</h5>
                        <p class="text-muted">This email is sent to administrators for important service alerts.</p>
                        
                        <form action="{{ route('admin.settings.email-templates') }}" method="POST">
                            @csrf
                            <input type="hidden" name="template_type" value="admin_alert">
                            <div class="mb-3">
                                <label for="alertSubject" class="form-label">Email Subject</label>
                                <input type="text" class="form-control" id="alertSubject" name="subject" value="{{ $settings['email_admin_alert_subject'] ?? 'AutoX Studio: Admin Service Alert' }}">
                            </div>
                            <div class="mb-3">
                                <label for="alertTemplate" class="form-label">Email Template</label>
                                <textarea class="form-control" id="alertTemplate" name="template" rows="10">{{ $settings['email_admin_alert_template'] ?? "Hello Admin,\n\n{alert_message}\n\nService Details:\nOrder ID: {order_id}\nCustomer: {customer_name}\nVehicle: {car_brand} {car_model}\n\nThank you for using AutoX Studio Management System!" }}</textarea>
                            </div>
                            <div class="text-muted mb-3">
                                <strong>Available variables:</strong> {alert_message}, {alert_type}, {order_id}, {customer_name}, {car_brand}, {car_model}, {status}
                            </div>
                            <button type="submit" class="btn btn-primary">Save Template</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection