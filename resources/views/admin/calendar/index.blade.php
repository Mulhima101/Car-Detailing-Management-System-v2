@extends('layouts.admin')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<style>
    :root {
        --autox-yellow: #FFC107;
        --autox-dark: #343a40;
        --autox-primary: #007bff;
    }
    
    #calendar {
        margin-top: 20px;
        background-color: #fff;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .fc-event {
        cursor: pointer;
    }
    
    .fc-toolbar-title {
        color: var(--autox-dark);
    }
    
    .fc-button-primary {
        background-color: var(--autox-yellow) !important;
        border-color: var(--autox-yellow) !important;
        color: #212529 !important;
    }
    
    .fc-button-primary:hover {
        background-color: #e0a800 !important;
        border-color: #e0a800 !important;
    }
    
    .modal-header {
        background-color: var(--autox-yellow);
        color: #212529;
    }
    
    .event-details-table td {
        padding: 5px 10px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Service Calendar</h5>
                    <div>
                        <button class="btn btn-warning btn-sm" id="sync-events">Sync Services</button>
                        <button class="btn btn-warning btn-sm" id="add-event">Add Event</button>
                    </div>
                </div>

                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table event-details-table">
                    <tr>
                        <td><strong>Title:</strong></td>
                        <td id="event-title"></td>
                    </tr>
                    <tr>
                        <td><strong>Start:</strong></td>
                        <td id="event-start"></td>
                    </tr>
                    <tr>
                        <td><strong>End:</strong></td>
                        <td id="event-end"></td>
                    </tr>
                    <tr>
                        <td><strong>Description:</strong></td>
                        <td id="event-description"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="edit-event">Edit</button>
                <button type="button" class="btn btn-danger" id="delete-event">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Event Modal -->
<div class="modal fade" id="addEditEventModal" tabindex="-1" aria-labelledby="addEditEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditEventModalLabel">Add Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <input type="hidden" id="event-id">
                    <div class="form-group">
                        <label for="event-form-title">Title</label>
                        <input type="text" class="form-control" id="event-form-title" required>
                    </div>
                    <div class="form-group">
                        <label for="car-service-id">Car Service</label>
                        <select class="form-control" id="car-service-id">
                            <option value="">None (Custom Event)</option>
                            <!-- Will be populated with AJAX -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="event-form-start">Start Date & Time</label>
                        <input type="datetime-local" class="form-control" id="event-form-start" required>
                    </div>
                    <div class="form-group">
                        <label for="event-form-end">End Date & Time</label>
                        <input type="datetime-local" class="form-control" id="event-form-end" required>
                    </div>
                    <div class="form-group">
                        <label for="event-form-color">Color</label>
                        <input type="color" class="form-control" id="event-form-color" value="#FFC107">
                    </div>
                    <div class="form-group">
                        <label for="event-form-description">Description</label>
                        <textarea class="form-control" id="event-form-description" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="event-form-all-day">
                        <label class="form-check-label" for="event-form-all-day">All Day Event</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-event">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
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
            themeSystem: 'bootstrap',
            events: '{{ route("admin.calendar.events") }}',
            editable: true,
            selectable: true,
            businessHours: {
                daysOfWeek: [ 1, 2, 3, 4, 5 ], // Monday - Friday
                startTime: '08:00',
                endTime: '18:00',
            },
            eventClick: function(info) {
                // Show event details in modal
                currentEvent = info.event;
                document.getElementById('event-title').textContent = info.event.title;
                document.getElementById('event-start').textContent = info.event.start.toLocaleString();
                document.getElementById('event-end').textContent = info.event.end ? info.event.end.toLocaleString() : 'Not specified';
                document.getElementById('event-description').textContent = info.event.extendedProps.description || 'No description';
                
                $('#eventModal').modal('show');
            },
            select: function(info) {
                // Pre-fill the form for a new event
                document.getElementById('addEditEventModalLabel').textContent = 'Add Event';
                document.getElementById('event-id').value = '';
                document.getElementById('event-form-title').value = '';
                document.getElementById('event-form-start').value = formatDateTimeForInput(info.start);
                document.getElementById('event-form-end').value = formatDateTimeForInput(info.end);
                document.getElementById('event-form-color').value = '#FFC107'; // Default yellow color
                document.getElementById('event-form-description').value = '';
                document.getElementById('event-form-all-day').checked = info.allDay;
                
                loadCarServices();
                $('#addEditEventModal').modal('show');
            },
            eventDrop: function(info) {
                // Update event dates when dragged
                updateEvent(info.event);
            },
            eventResize: function(info) {
                // Update event when resized
                updateEvent(info.event);
            }
        });
        
        calendar.render();
        
        // Add event button click
        document.getElementById('add-event').addEventListener('click', function() {
            document.getElementById('addEditEventModalLabel').textContent = 'Add Event';
            document.getElementById('event-id').value = '';
            document.getElementById('event-form-title').value = '';
            document.getElementById('event-form-start').value = '';
            document.getElementById('event-form-end').value = '';
            document.getElementById('event-form-color').value = '#FFC107';
            document.getElementById('event-form-description').value = '';
            document.getElementById('event-form-all-day').checked = false;
            
            loadCarServices();
            $('#addEditEventModal').modal('show');
        });
        
        // Edit event button click
        document.getElementById('edit-event').addEventListener('click', function() {
            if (!currentEvent) return;
            
            document.getElementById('addEditEventModalLabel').textContent = 'Edit Event';
            document.getElementById('event-id').value = currentEvent.id;
            document.getElementById('event-form-title').value = currentEvent.title;
            document.getElementById('event-form-start').value = formatDateTimeForInput(currentEvent.start);
            document.getElementById('event-form-end').value = currentEvent.end ? formatDateTimeForInput(currentEvent.end) : '';
            document.getElementById('event-form-color').value = currentEvent.backgroundColor || '#FFC107';
            document.getElementById('event-form-description').value = currentEvent.extendedProps.description || '';
            document.getElementById('event-form-all-day').checked = currentEvent.allDay;
            document.getElementById('car-service-id').value = currentEvent.extendedProps.car_service_id || '';
            
            $('#eventModal').modal('hide');
            loadCarServices();
            $('#addEditEventModal').modal('show');
        });
        
        // Delete event button click
        document.getElementById('delete-event').addEventListener('click', function() {
            if (!currentEvent || !confirm('Are you sure you want to delete this event?')) return;
            
            fetch(`{{ url('admin/calendar/events') }}/${currentEvent.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                currentEvent.remove();
                $('#eventModal').modal('hide');
                alert('Event deleted successfully');
            })
            .catch(error => console.error('Error:', error));
        });
        
        // Save event button click
        document.getElementById('save-event').addEventListener('click', function() {
            const eventId = document.getElementById('event-id').value;
            const title = document.getElementById('event-form-title').value;
            const carServiceId = document.getElementById('car-service-id').value;
            const start = document.getElementById('event-form-start').value;
            const end = document.getElementById('event-form-end').value;
            const color = document.getElementById('event-form-color').value;
            const description = document.getElementById('event-form-description').value;
            const allDay = document.getElementById('event-form-all-day').checked;
            
            if (!title || !start || !end) {
                alert('Please fill in all required fields');
                return;
            }
            
            const eventData = {
                title,
                start,
                end,
                color,
                description,
                all_day: allDay,
                car_service_id: carServiceId || null
            };
            
            const url = eventId 
                ? `{{ url('admin/calendar/events') }}/${eventId}`
                : '{{ route('admin.calendar.events.store') }}';
                
            const method = eventId ? 'PUT' : 'POST';
            
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
                calendar.refetchEvents();
                $('#addEditEventModal').modal('hide');
                alert(eventId ? 'Event updated successfully' : 'Event added successfully');
            })
            .catch(error => console.error('Error:', error));
        });
        
        // Sync events button click
        document.getElementById('sync-events').addEventListener('click', function() {
            if (!confirm('Sync all car services that don\'t have calendar events yet?')) return;
            
            fetch('{{ route('admin.calendar.sync') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                calendar.refetchEvents();
                alert('Calendar events synchronized successfully');
            })
            .catch(error => console.error('Error:', error));
        });
        
        // Helper function to format date for datetime-local input
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
        
        // Update event after drag or resize
        function updateEvent(event) {
            const eventData = {
                title: event.title,
                start: event.start.toISOString(),
                end: event.end ? event.end.toISOString() : null,
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
            .then(response => response.json())
            .catch(error => console.error('Error:', error));
        }
        
        // Load car services for dropdown
        function loadCarServices() {
            fetch('{{ url('api/car-services') }}')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('car-service-id');
                    // Clear existing options except the first one
                    while (select.options.length > 1) {
                        select.remove(1);
                    }
                    
                    // Add new options
                    data.forEach(service => {
                        const option = document.createElement('option');
                        option.value = service.id;
                        option.textContent = `${service.car_make} ${service.car_model} - ${service.customer_name}`;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading car services:', error));
        }
    });
</script>
@endsection