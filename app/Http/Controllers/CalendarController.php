<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\CarService;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('admin.calendar.index');
    }
    
    public function getEvents()
    {
        $events = CalendarEvent::all();
        return response()->json($events);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'car_service_id' => 'required|exists:car_services,id',
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'all_day' => 'boolean'
        ]);
        
        $event = CalendarEvent::create($request->all());
        return response()->json($event);
    }
    
    public function update(Request $request, $id)
    {
        $event = CalendarEvent::findOrFail($id);
        
        $request->validate([
            'title' => 'string',
            'start' => 'date',
            'end' => 'date|after_or_equal:start',
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
        
        foreach ($carServices as $service) {
            // Estimate end time (2 hours after service time)
            $startTime = $service->service_date;
            $endTime = (clone $service->service_date)->addHours(2);
            
            CalendarEvent::create([
                'car_service_id' => $service->id,
                'title' => $service->car_make . ' ' . $service->car_model . ' - ' . $service->customer->name,
                'start' => $startTime,
                'end' => $endTime,
                'color' => '#FFC107', // Yellow color from AutoX branding
                'description' => 'Services: ' . $service->services,
                'all_day' => false,
            ]);
        }
        
        return redirect()->back()->with('success', 'Calendar events synchronized successfully.');
    }
}