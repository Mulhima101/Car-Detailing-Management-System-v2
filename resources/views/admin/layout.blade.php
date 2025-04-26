<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoX Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --autox-yellow: #FFDD00;
            --autox-dark: #212529;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            width: 225px;
            background-color: var(--autox-dark);
            color: white;
            flex-shrink: 0;
            position: fixed;
            height: 100vh;
            z-index: 100;
            left: 0;
            top: 0;
            overflow-y: auto;
        }
        
        .brand {
            padding: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .brand h4 {
            color: white;
            margin-bottom: 0;
        }
        
        .brand h4 span {
            color: var(--autox-yellow);
        }
        
        .sidebar .nav {
            display: flex;
            flex-direction: column;
            padding: 0;
            margin: 0;
        }
        
        .sidebar .nav-item {
            width: 100%;
            margin: 2px 0;
        }
        
        .sidebar .nav-link {
            padding: 12px 20px;
            color: rgba(255,255,255,0.7);
            display: flex;
            align-items: center;
            transition: all 0.3s;
            white-space: nowrap;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--autox-yellow);
            color: var(--autox-dark);
            font-weight: 600;
        }
        
        .content-wrapper {
            flex-grow: 1;
            margin-left: 225px;
            padding: 20px 30px;
            width: calc(100% - 225px);
        }
        
        .page-title {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .logout-item {
            position: absolute;
            bottom: 20px;
            width: 100%;
            padding: 0 15px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            padding: 15px 20px;
        }
        
        .btn-yellow {
            background-color: var(--autox-yellow);
            color: var(--autox-dark);
            font-weight: 500;
        }
        
        .btn-yellow:hover {
            background-color: #e6c700;
            color: var(--autox-dark);
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        
        .status-in-progress {
            background-color: #0d6efd;
            color: #fff;
        }
        
        .status-completed {
            background-color: #198754;
            color: #fff;
        }
        
        .form-switch .form-check-input {
            height: 1.25rem;
            width: 2.5rem;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            body {
                flex-direction: column;
            }
            
            .logout-item {
                position: relative;
                bottom: auto;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
        <img src="{{ asset('public/images/autox-logo.png') }}" alt="AutoX" class="img-fluid" style="max-height: 50px;">
        <p class="text-white-50 small">Admin Dashboard</p>
        </div>
        
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.customers') ? 'active' : '' }}" href="{{ route('admin.customers') }}">
                    <i class="fas fa-users"></i> Customers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.services') ? 'active' : '' }}" href="{{ route('admin.services') }}">
                    <i class="fas fa-car"></i> All Services
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.completed-services') ? 'active' : '' }}" href="{{ route('admin.completed-services') }}">
                    <i class="fas fa-check-circle"></i> Completed Services
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
        </ul>
        
        <div class="logout-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>