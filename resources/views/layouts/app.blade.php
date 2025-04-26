<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
