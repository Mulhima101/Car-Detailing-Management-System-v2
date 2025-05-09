<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\CarService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('admin.calendar.index');
    }
    
    public function getEvents()
    {
        // Get all calendar events
        $events = CalendarEvent::with('carService.customer')->get()->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start->format('Y-m-d H:i:s'),
                'end' => $event->end ? $event->end->format('Y-m-d H:i:s') : null,
                'color' => $event->color,
                'description' => $event->description,
                'allDay' => $event->all_day,
                'extendedProps' => [
                    'car_service_id' => $event->car_service_id,
                    'order_id' => $event->carService->order_id ?? '',
                    'customer_name' => $event->carService->customer->name ?? '',
                    'status' => $event->carService->status ?? ''
                ]
            ];
        });
        
        return response()->json($events);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'car_service_id' => 'nullable|exists:car_services,id',
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'all_day' => 'boolean'
        ]);
        
        $event = CalendarEvent::create([
            'car_service_id' => $request->car_service_id,
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'color' => $request->color,
            'description' => $request->description,
            'all_day' => $request->all_day ?? false
        ]);
        
        return response()->json($event);
    }
    
    public function update(Request $request, $id)
    {
        $event = CalendarEvent::findOrFail($id);
        
        $request->validate([
            'title' => 'string',
            'start' => 'date',
            'end' => 'nullable|date',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'all_day' => 'boolean'
        ]);
        
        $event->update($request->all());
        return response()->json($event);
    }
    
    public function destroy($id)
    {
        $event = CalendarEvent::findOrFail($id);
        $event->delete();
        
        return response()->json(['message' => 'Event deleted successfully']);
    }
    
    // Method to create calendar events from car service records
    public function syncCarServices()
    {
        $carServices = CarService::whereDoesntHave('calendarEvent')->get();
        $count = 0;
        
        foreach ($carServices as $service) {
            // Get appropriate color based on status
            $color = $this->getStatusColor($service->status);
            
            // Use appointment date if available, otherwise fallback to created date
            $startDate = $service->start_date ?: $service->created_at;
            
            // Set end time (2 hours after start time if completion date not set)
            $endDate = $service->completion_date ?: (clone $startDate)->addHours(2);
            
            CalendarEvent::create([
                'car_service_id' => $service->id,
                'title' => ($service->car_brand ?? 'Vehicle') . ' - ' . $service->customer->name,
                'start' => $startDate,
                'end' => $endDate,
                'color' => $color,
                'description' => 'Order ID: ' . $service->order_id . "\n" . 
                                'Services: ' . implode(', ', $service->services),
                'all_day' => false,
            ]);
            
            $count++;
        }
        
        return redirect()->back()->with('success', $count . ' calendar events synchronized successfully.');
    }
    
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return '#ffcc00'; // Yellow
            case 'in-progress':
                return '#3498db'; // Blue
            case 'completed':
                return '#2ecc71'; // Green
            default:
                return '#95a5a6'; // Grey
        }
    }
}