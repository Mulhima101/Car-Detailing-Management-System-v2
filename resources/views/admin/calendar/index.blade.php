@extends('admin.layout')

@section('content')
<div class="page-title">
    <h2>Service Calendar</h2>
    
    <div>
        <a href="{{ route('service.create') }}" class="btn btn-yellow me-2">
            <i class="fas fa-plus-circle me-1"></i> New Service Request
        </a>
        <button type="button" class="btn btn-outline-primary" id="sync-events">
            <i class="fas fa-sync me-1"></i> Sync Services
        </button>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Calendar View</h5>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="add-event">
                    <i class="fas fa-plus me-1"></i> Add Event
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="pending-filter" checked>
                        <label class="form-check-label" for="pending-filter">
                            <span class="status-badge status-pending">Pending</span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="in-progress-filter" checked>
                        <label class="form-check-label" for="in-progress-filter">
                            <span class="status-badge status-in-progress">In Progress</span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="completed-filter" checked>
                        <label class="form-check-label" for="completed-filter">
                            <span class="status-badge status-completed">Completed</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div id="calendar"></div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Order ID:</strong> <span id="event-order-id"></span></p>
                <p><strong>Customer:</strong> <span id="event-customer"></span></p>
                <p><strong>Time:</strong> <span id="event-time"></span></p>
                <p><strong>Status:</strong> <span id="event-status"></span></p>
                <p><strong>Description:</strong> <span id="event-description"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="edit-event">Edit</button>
                <button type="button" class="btn btn-danger" id="delete-event">Delete</button>
                <a href="#" id="view-service-link" class="btn btn-info">View Service</a>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Event Modal -->
<div class="modal fade" id="addEditEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditModalTitle">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <input type="hidden" id="event-id">
                    <div class="mb-3">
                        <label for="event-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="event-title" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-start" class="form-label">Start Date & Time</label>
                        <input type="datetime-local" class="form-control" id="event-start" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-end" class="form-label">End Date & Time</label>
                        <input type="datetime-local" class="form-control" id="event-end">
                    </div>
                    <div class="mb-3">
                        <label for="event-color" class="form-label">Color</label>
                        <input type="color" class="form-control" id="event-color" value="#ffcc00">
                    </div>
                    <div class="mb-3">
                        <label for="event-description-input" class="form-label">Description</label>
                        <textarea class="form-control" id="event-description-input" rows="3"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="event-all-day">
                        <label class="form-check-label" for="event-all-day">All Day Event</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-event">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentEvent = null;
        
        // Initialize FullCalendar
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: "{{ route('admin.calendar.events') }}",
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            editable: true,
            selectable: true,
            nowIndicator: true,
            dayMaxEvents: true,
            // Event click handler
            eventClick: function(info) {
                // Store current event
                currentEvent = info.event;
                
                // Populate modal with event details
                document.getElementById('event-order-id').textContent = 
                    info.event.extendedProps.order_id || 'N/A';
                document.getElementById('event-customer').textContent = 
                    info.event.extendedProps.customer_name || 'N/A';
                document.getElementById('event-time').textContent = 
                    formatDateTime(info.event.start) + (info.event.end ? ' - ' + formatDateTime(info.event.end) : '');
                
                // Set status with badge
                const statusElement = document.getElementById('event-status');
                const status = info.event.extendedProps.status || 'N/A';
                statusElement.innerHTML = '';
                
                const statusBadge = document.createElement('span');
                statusBadge.classList.add('status-badge');
                statusBadge.classList.add('status-' + status);
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                statusElement.appendChild(statusBadge);
                
                // Description
                document.getElementById('event-description').textContent = 
                    info.event.extendedProps.description || info.event.description || 'No description';
                
                // Set link for view service details
                const viewServiceLink = document.getElementById('view-service-link');
                if (info.event.extendedProps.car_service_id) {
                    viewServiceLink.href = "/admin/service/" + info.event.extendedProps.car_service_id;
                    viewServiceLink.style.display = 'inline-block';
                } else {
                    viewServiceLink.style.display = 'none';
                }
                
                // Show modal
                const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                eventModal.show();
            },
            
            // Select date range handler for adding new events
            select: function(info) {
                document.getElementById('addEditModalTitle').textContent = 'Add Event';
                document.getElementById('event-id').value = '';
                document.getElementById('event-title').value = '';
                document.getElementById('event-start').value = formatDateTimeForInput(info.start);
                document.getElementById('event-end').value = formatDateTimeForInput(info.end);
                document.getElementById('event-color').value = '#ffcc00';
                document.getElementById('event-description-input').value = '';
                document.getElementById('event-all-day').checked = info.allDay;
                
                const addEditModal = new bootstrap.Modal(document.getElementById('addEditEventModal'));
                addEditModal.show();
            },
            
            // Event drag handler
            eventDrop: function(info) {
                updateEvent(info.event);
            },
            
            // Event resize handler
            eventResize: function(info) {
                updateEvent(info.event);
            }
        });
        
        calendar.render();
        
        // Filters
        document.getElementById('pending-filter').addEventListener('change', function() {
            filterEvents();
        });
        
        document.getElementById('in-progress-filter').addEventListener('change', function() {
            filterEvents();
        });
        
        document.getElementById('completed-filter').addEventListener('change', function() {
            filterEvents();
        });
        
        function filterEvents() {
            const pendingFilter = document.getElementById('pending-filter').checked;
            const inProgressFilter = document.getElementById('in-progress-filter').checked;
            const completedFilter = document.getElementById('completed-filter').checked;
            
            calendar.getEvents().forEach(event => {
                const status = event.extendedProps.status;
                let visible = true;
                
                if (status === 'pending' && !pendingFilter) {
                    visible = false;
                } else if (status === 'in-progress' && !inProgressFilter) {
                    visible = false;
                } else if (status === 'completed' && !completedFilter) {
                    visible = false;
                }
                
                event.setProp('display', visible ? 'auto' : 'none');
            });
        }
        
        // Edit event button click handler
        document.getElementById('edit-event').addEventListener('click', function() {
            if (!currentEvent) return;
            
            // Hide event details modal
            bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
            
            // Populate edit form
            document.getElementById('addEditModalTitle').textContent = 'Edit Event';
            document.getElementById('event-id').value = currentEvent.id;
            document.getElementById('event-title').value = currentEvent.title;
            document.getElementById('event-start').value = formatDateTimeForInput(currentEvent.start);
            document.getElementById('event-end').value = currentEvent.end ? 
                formatDateTimeForInput(currentEvent.end) : '';
            document.getElementById('event-color').value = currentEvent.backgroundColor || '#ffcc00';
            document.getElementById('event-description-input').value = 
                currentEvent.extendedProps.description || currentEvent.description || '';
            document.getElementById('event-all-day').checked = currentEvent.allDay;
            
            // Show edit modal
            const addEditModal = new bootstrap.Modal(document.getElementById('addEditEventModal'));
            addEditModal.show();
        });
        
        // Delete event button click handler
        document.getElementById('delete-event').addEventListener('click', function() {
            if (!currentEvent) return;
            
            if (confirm('Are you sure you want to delete this event?')) {
                // Send delete request
                fetch(`{{ url('admin/calendar/events') }}/${currentEvent.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Remove event from calendar
                    currentEvent.remove();
                    
                    // Hide modal
                    bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
                    
                    // Show success message
                    alert('Event deleted successfully');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting event');
                });
            }
        });
        
        // Add event button click handler
        document.getElementById('add-event').addEventListener('click', function() {
            document.getElementById('addEditModalTitle').textContent = 'Add Event';
            document.getElementById('event-id').value = '';
            document.getElementById('event-title').value = '';
            document.getElementById('event-start').value = '';
            document.getElementById('event-end').value = '';
            document.getElementById('event-color').value = '#ffcc00';
            document.getElementById('event-description-input').value = '';
            document.getElementById('event-all-day').checked = false;
            
            const addEditModal = new bootstrap.Modal(document.getElementById('addEditEventModal'));
            addEditModal.show();
        });
        
        // Save event button click handler
        document.getElementById('save-event').addEventListener('click', function() {
            const eventId = document.getElementById('event-id').value;
            const title = document.getElementById('event-title').value;
            const start = document.getElementById('event-start').value;
            const end = document.getElementById('event-end').value;
            const color = document.getElementById('event-color').value;
            const description = document.getElementById('event-description-input').value;
            const allDay = document.getElementById('event-all-day').checked;
            
            if (!title || !start) {
                alert('Title and Start time are required');
                return;
            }
            
            const eventData = {
                title,
                start,
                end: end || null,
                color,
                description,
                all_day: allDay
            };
            
            // Determine if create or update
            let url = "{{ route('admin.calendar.events.store') }}";
            let method = 'POST';
            
            if (eventId) {
                url = `{{ url('admin/calendar/events') }}/${eventId}`;
                method = 'PUT';
            }
            
            // Send request
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(eventData)
            })
            .then(response => response.json())
            .then(data => {
                // Refresh calendar
                calendar.refetchEvents();
                
                // Hide modal
                bootstrap.Modal.getInstance(document.getElementById('addEditEventModal')).hide();
                
                // Show success message
                alert(eventId ? 'Event updated successfully' : 'Event added successfully');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving event');
            });
        });
        
        // Sync events button click handler
        document.getElementById('sync-events').addEventListener('click', function() {
            if (confirm('Sync all car services that don\'t have calendar events yet?')) {
                // Send sync request
                fetch("{{ route('admin.calendar.sync') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    // Refresh calendar
                    calendar.refetchEvents();
                    
                    // Reload page to show success message
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error syncing events');
                });
            }
        });
        
        // Helper function to format date time for display
        function formatDateTime(date) {
            if (!date) return '';
            
            const options = { 
                weekday: 'short',
                month: 'short', 
                day: 'numeric',
                hour: 'numeric', 
                minute: '2-digit'
            };
            
            return new Date(date).toLocaleString(undefined, options);
        }
        
        // Helper function to format date time for input fields
        function formatDateTimeForInput(date) {
            if (!date) return '';
            
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            const hours = String(d.getHours()).padStart(2, '0');
            const minutes = String(d.getMinutes()).padStart(2, '0');
            
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }
        
        // Function to update event after drag or resize
        function updateEvent(event) {
            const eventData = {
                title: event.title,
                start: event.start.toISOString(),
                end: event.end ? event.end.toISOString() : null,
                color: event.backgroundColor,
                all_day: event.allDay
            };
            
            fetch(`{{ url('admin/calendar/events') }}/${event.id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(eventData)
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating event');
                calendar.refetchEvents(); // Revert changes on error
            });
        }
    });
</script>
<style>
    #calendar {
        height: 700px;
    }
    .fc-event {
        cursor: pointer;
    }
    .fc-event-title {
        font-weight: bold;
    }
    .fc-daygrid-event {
        white-space: normal;
    }
</style>
@endsection