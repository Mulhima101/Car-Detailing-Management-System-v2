<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AutoX Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .login-container {
            background-color: #212529; /* Dark background to match your theme */
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: white;
        }
        .brand-logo {
            margin-bottom: 25px;
        }
        .brand-logo img {
            max-width: 150px;
            height: auto;
        }
        .subtitle {
            color: #adb5bd;
            margin-bottom: 30px;
        }
        .form-control {
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border-color: #FFCE00;
            box-shadow: 0 0 0 0.25rem rgba(255, 206, 0, 0.25);
        }
        .btn-signin {
            background-color: #FFCE00;
            color: #212529;
            border: none;
            border-radius: 4px;
            padding: 12px;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
        }
        .btn-signin:hover {
            background-color: #e6b800;
        }
        .footer {
            margin-top: 40px;
            font-size: 0.8rem;
            color: #adb5bd;
        }
        .field-label {
            text-align: left;
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #e9ecef;
        }
        .text-danger {
            color: #ff7d7d;
            font-size: 0.9rem;
            text-align: left;
            margin-top: -15px;
            margin-bottom: 15px;
        }
        /* Fix placeholder text color for dark background */
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-logo">
            <img src="{{ asset('public/images/autox-logo.png') }}" alt="AutoX Service">
        </div>
        <p class="subtitle">Management System</p>
        
        @if (session('status'))
            <div class="alert alert-success mb-3">
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="field-label">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                
                @error('email')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="field-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                
                @error('password')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-signin">Sign In</button>
        </form>
        
        <div class="footer">
            &copy; 2025 AutoX Studio. All rights reserved.
        </div>
    </div>
</body>
</html>