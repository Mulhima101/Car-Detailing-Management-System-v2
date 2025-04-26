<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarService;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\ServiceStatusUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomServiceEmail;

class DashboardController extends Controller
{
    public function index()
    {
        $inProgressServices = CarService::with('customer')
            ->where('status', 'in-progress')
            ->orderBy('start_date', 'desc')
            ->get();
        
        // Get service statistics
        $stats = [
            'total_services' => CarService::count(),
            'pending_services' => CarService::where('status', 'pending')->count(),
            'in_progress_services' => CarService::where('status', 'in-progress')->count(),
            'completed_services' => CarService::where('status', 'completed')->count(),
            'total_customers' => Customer::count()
        ];
        
        return view('admin.dashboard', compact('inProgressServices', 'stats'));
    }
    
    public function allServices(Request $request)
    {
        $query = CarService::with('customer');
        
        // Apply filters if present
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('car_brand', 'like', "%{$search}%")
                  ->orWhere('car_model', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }
        
        $services = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.all-services', compact('services'));
    }
    
    public function completedServices()
    {
        $completedServices = CarService::with('customer')
            ->where('status', 'completed')
            ->orderBy('completion_date', 'desc')
            ->get();
        
        return view('admin.completed-services', compact('completedServices'));
    }
    
    public function customers()
    {
        $customers = Customer::withCount('carServices')->get();
        return view('admin.customers', compact('customers'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $carService = CarService::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,in-progress,completed',
        ]);
        
        $oldStatus = $carService->status;
        $carService->status = $validated['status'];
        
        // If status is being changed to completed, set completion date
        if ($validated['status'] === 'completed' && !$carService->completion_date) {
            $carService->completion_date = Carbon::now();
        }
        
        // If status is being changed to in-progress and start_date is not set, set it
        if ($validated['status'] === 'in-progress' && $oldStatus === 'pending') {
            $carService->start_date = Carbon::now();
        }
        
        $carService->save();
        
        // Check settings to see if notifications are enabled
        $notificationsEnabled = config('autox.notification_status_updates', true);
        
        // Send notification to customer if email is available and notifications are enabled
        if ($notificationsEnabled && $carService->customer && $carService->customer->email) {
            try {
                $carService->customer->notify(new ServiceStatusUpdated($carService));
                // Log successful notification
                Log::info("Status update notification sent to {$carService->customer->email} for service {$carService->order_id}");
            } catch (\Exception $e) {
                // Log error but don't prevent the status update
                Log::error("Failed to send status update notification: {$e->getMessage()}");
            }
        }
        
        return redirect()->back()->with('success', 'Service status updated successfully.');
    }
    
    public function viewServiceDetails($id)
    {
        $service = CarService::with('customer')->findOrFail($id);
        return view('admin.service_details', compact('service'));
    }
    
    public function emailCustomer(Request $request, $id)
    {
        $carService = CarService::with('customer')->findOrFail($id);
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Check if customer has email
        if (!$carService->customer || !$carService->customer->email) {
            return redirect()->back()->with('error', 'Customer does not have a valid email address.');
        }
        
        try {
            // Send custom email
            Mail::to($carService->customer->email)
                ->send(new CustomServiceEmail($carService, $validated['subject'], $validated['message']));
                
            // Log the email
            Log::info("Custom email sent to {$carService->customer->email} for service {$carService->order_id}");
            
            return redirect()->back()->with('success', 'Email sent to customer successfully.');
        } catch (\Exception $e) {
            Log::error("Failed to send custom email: {$e->getMessage()}");
            return redirect()->back()->with('error', 'Failed to send email. Please try again later.');
        }
    }
    
    public function searchCustomers(Request $request)
    {
        $search = $request->input('search');
        
        $customers = Customer::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%")
            ->withCount('carServices')
            ->get();
        
        return view('admin.customers', compact('customers'));
    }
    
    public function dashboardStats()
    {
        // For AJAX requests to update dashboard stats
        $stats = [
            'total_services' => CarService::count(),
            'pending_services' => CarService::where('status', 'pending')->count(),
            'in_progress_services' => CarService::where('status', 'in-progress')->count(),
            'completed_services' => CarService::where('status', 'completed')->count(),
            'total_customers' => Customer::count(),
            'recent_services' => CarService::with('customer')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ];
        
        return response()->json($stats);
    }
}