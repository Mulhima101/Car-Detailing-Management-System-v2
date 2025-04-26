<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoX Studio - Service Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --autox-yellow: #FFDD00;
        }
        body {
            background-color: #f8f9fa;
        }
        .bg-autox-yellow {
            background-color: var(--autox-yellow);
        }
        .btn-autox {
            background-color: var(--autox-yellow);
            color: #000;
            border: none;
            font-weight: bold;
        }
        .btn-autox:hover {
            background-color: #e6c700;
            color: #000;
        }
        .header {
            background-color: #212529;
            padding: 20px 0;
            margin-bottom: 40px;
        }
        .header img {
            max-height: 60px;
        }
        .form-section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        .service-check {
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            transition: all 0.3s;
        }
        .service-check:hover {
            border-color: var(--autox-yellow);
            box-shadow: 0 0 10px rgba(255, 221, 0, 0.3);
        }
        .service-description {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="text-white mb-0">
                        <span class="text-white">Auto</span><span style="color: #FFDD00">X</span> 
                        <span class="text-white">Studio</span>
                    </h1>
                    <p class="text-white-50 mt-2">Premium Car Detailing Services</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="text-center mb-4">Service Request Form</h2>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('service.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-section">
                        <h4 class="mb-4"><span class="badge bg-autox-yellow text-dark me-2">1</span> Customer Information</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h4 class="mb-4"><span class="badge bg-autox-yellow text-dark me-2">2</span> Vehicle Information</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="car_brand" class="form-label">Car Brand</label>
                                <select class="form-select @error('car_brand') is-invalid @enderror" id="car_brand" name="car_brand" required>
                                    <option value="" selected disabled>Select Brand</option>
                                    @foreach($carBrands as $brand)
                                        <option value="{{ $brand }}" {{ old('car_brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                    @endforeach
                                </select>
                                @error('car_brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="car_model" class="form-label">Car Model & Year</label>
                                <input type="text" class="form-control @error('car_model') is-invalid @enderror" id="car_model" name="car_model" placeholder="e.g., Camry 2022" value="{{ old('car_model') }}" required>
                                @error('car_model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h4 class="mb-4"><span class="badge bg-autox-yellow text-dark me-2">3</span> Services Required</h4>
                        <p class="text-muted mb-4">Please select the services you're interested in:</p>
                        
                        <div class="row">
                            @foreach($services as $service => $description)
                                <div class="col-md-6 mb-3">
                                    <div class="service-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="services_requested[]" value="{{ $service }}" id="service_{{ $loop->index }}" {{ is_array(old('services_requested')) && in_array($service, old('services_requested')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="service_{{ $loop->index }}">
                                                <strong>{{ $service }}</strong>
                                            </label>
                                            <p class="service-description mt-1 mb-0">{{ $description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            <label for="service_description" class="form-label">Additional Service Details</label>
                            <textarea class="form-control @error('service_description') is-invalid @enderror" id="service_description" name="service_description" rows="3" placeholder="Please provide any specific requirements or details about the services you need...">{{ old('service_description') }}</textarea>
                            @error('service_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-autox btn-lg px-5 py-3">Submit Service Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>AutoX Studio</h5>
                    <p>Premium car detailing services in Australia</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 AutoX Studio. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>