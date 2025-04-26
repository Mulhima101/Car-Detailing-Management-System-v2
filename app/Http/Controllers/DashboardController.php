<?php

namespace App\Http\Controllers;

use App\Models\CarService;
use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_services' => CarService::count(),
            'in_progress' => CarService::where('status', 'in_progress')->count(),
            'completed' => CarService::where('status', 'completed')->count(),
            'total_customers' => Customer::count(),
        ];
        
        // Get recent services
        $recentServices = CarService::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recentServices'));
    }
}