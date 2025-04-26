<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoX Studio - Service Request Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('public/css/autox.css') }}" rel="stylesheet">
    <style>
        :root {
            --autox-yellow: #FFCE00;
            --autox-dark: #212529;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .header {
            background-color: var(--autox-dark);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
        }
        .header h1 {
            margin: 0;
        }
        .header .brand-yellow {
            color: var(--autox-yellow);
        }
        .form-section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
        }
        .section-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            background-color: var(--autox-yellow);
            color: var(--autox-dark);
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-weight: bold;
            margin-right: 10px;
        }
        .section-title {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 20px;
        }
        .service-option {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .service-option:hover {
            border-color: var(--autox-yellow);
        }
        .service-option.selected {
            border-color: var(--autox-yellow);
            background-color: rgba(255, 206, 0, 0.1);
        }
        .service-option.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .service-option h5 {
            margin-bottom: 5px;
        }
        .service-option p {
            color: #6c757d;
            margin-bottom: 0;
        }
        .sub-options {
            padding-left: 20px;
            margin-top: 10px;
            display: none;
        }
        .service-option.selected .sub-options {
            display: block;
        }
        .submit-btn {
            background-color: var(--autox-yellow);
            color: var(--autox-dark);
            font-weight: bold;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .submit-btn:hover {
            background-color: #e6b800;
        }
        .footer {
            background-color: var(--autox-dark);
            color: white;
            padding: 1.5rem 0;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <img src="{{ asset('public/images/autox-logo.png') }}" alt="AutoX Studio" class="img-fluid" style="max-height: 80px;">
        <p class="mt-2">Premium Car Detailing Services</p>
    </div>

    <div class="container mb-5">
        <h2 class="text-center mb-4">Service Request Form</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('service.store') }}" method="POST" id="serviceRequestForm">
            @csrf
            
            <!-- Customer Information Section -->
            <div class="form-section">
                <div class="section-title">
                    <span class="section-number">1</span>Customer Information
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="customer_name" value="{{ old('customer_name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phoneNumber" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phoneNumber" name="phone" value="{{ old('phone') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="emailAddress" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="emailAddress" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                    </div>
                </div>
            </div>
            
            <!-- Vehicle Information Section -->
            <div class="form-section">
                <div class="section-title">
                    <span class="section-number">2</span>Vehicle Information
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="carBrand" class="form-label">Car Manufacturer</label>
                        <select class="form-select" id="carBrand" name="car_brand" required>                            <option value="" selected disabled>Select Manufacturer</option>
                            <option value="Audi" {{ old('car_brand') == 'Audi' ? 'selected' : '' }}>Audi</option>
                            <option value="BMW" {{ old('car_brand') == 'BMW' ? 'selected' : '' }}>BMW</option>
                            <option value="Ford" {{ old('car_brand') == 'Ford' ? 'selected' : '' }}>Ford</option>
                            <option value="Honda" {{ old('car_brand') == 'Honda' ? 'selected' : '' }}>Honda</option>
                            <option value="Hyundai" {{ old('car_brand') == 'Hyundai' ? 'selected' : '' }}>Hyundai</option>
                            <option value="Kia" {{ old('car_brand') == 'Kia' ? 'selected' : '' }}>Kia</option>
                            <option value="Mazda" {{ old('car_brand') == 'Mazda' ? 'selected' : '' }}>Mazda</option>
                            <option value="Mercedes" {{ old('car_brand') == 'Mercedes' ? 'selected' : '' }}>Mercedes-Benz</option>
                            <option value="Mitsubishi" {{ old('car_brand') == 'Mitsubishi' ? 'selected' : '' }}>Mitsubishi</option>
                            <option value="Nissan" {{ old('car_brand') == 'Nissan' ? 'selected' : '' }}>Nissan</option>
                            <option value="Toyota" {{ old('car_brand') == 'Toyota' ? 'selected' : '' }}>Toyota</option>
                            <option value="Volkswagen" {{ old('car_brand') == 'Volkswagen' ? 'selected' : '' }}>Volkswagen</option>
                            <option value="Other" {{ old('car_brand') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="carModel" class="form-label">Car Model & Year</label>
                        <input type="text" class="form-control" id="carModel" name="car_model" value="{{ old('car_model') }}" placeholder="e.g., Camry 2022" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="registrationNumber" class="form-label">Registration</label>
                        <input type="text" class="form-control" id="registrationNumber" name="license_plate" value="{{ old('license_plate') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="registrationState" class="form-label">Registration State</label>
                        <select class="form-select" id="registrationState" name="registration_state">
                            <option value="" selected disabled>Select State/Territory</option>
                            <option value="VIC" {{ old('registration_state') == 'VIC' ? 'selected' : '' }}>VIC</option>
                            <option value="NSW" {{ old('registration_state') == 'NSW' ? 'selected' : '' }}>NSW</option>
                            <option value="NT" {{ old('registration_state') == 'NT' ? 'selected' : '' }}>NT</option>
                            <option value="ACT" {{ old('registration_state') == 'ACT' ? 'selected' : '' }}>ACT</option>
                            <option value="QLD" {{ old('registration_state') == 'QLD' ? 'selected' : '' }}>QLD</option>
                            <option value="SA" {{ old('registration_state') == 'SA' ? 'selected' : '' }}>SA</option>
                            <option value="TAS" {{ old('registration_state') == 'TAS' ? 'selected' : '' }}>TAS</option>
                            <option value="WA" {{ old('registration_state') == 'WA' ? 'selected' : '' }}>WA</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="color" class="form-label">Vehicle Color</label>
                        <input type="text" class="form-control" id="color" name="color" value="{{ old('color') }}">
                    </div>
                </div>
            </div>
            
            <!-- Services Required Section -->
            <div class="form-section">
                <div class="section-title">
                    <span class="section-number">3</span>Services Required
                </div>
                <p class="mb-3">Please select the services you're interested in:</p>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="service-option">
                            <div class="form-check">
                                <input class="form-check-input service-checkbox" type="checkbox" value="Full Detail" name="services[]" id="fullDetail" data-has-sub="false" {{ in_array('Full Detail', old('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="fullDetail">
                                    <h5>Full Detail</h5>
                                    <p>Complete interior and exterior detailing service</p>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="service-option">
                            <div class="form-check">
                                <input class="form-check-input service-checkbox" type="checkbox" value="Paint Correction" name="services[]" id="paintCorrection" data-has-sub="true" {{ in_array('Paint Correction', old('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="paintCorrection">
                                    <h5>Paint Correction</h5>
                                    <p>Remove swirl marks, scratches and imperfections</p>
                                </label>
                            </div>
                            <div class="sub-options">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paint_correction_type" id="singleStage" value="Single Stage (50-60% defects cleared)" {{ old('paint_correction_type') == 'Single Stage (50-60% defects cleared)' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="singleStage">
                                        Single Stage (50-60% defects cleared)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paint_correction_type" id="multiStage" value="Multi-Stage (80-90% defects cleared)" {{ old('paint_correction_type') == 'Multi-Stage (80-90% defects cleared)' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="multiStage">
                                        Multi-Stage (80-90% defects cleared)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paint_correction_type" id="restoration" value="Paint Restoration" {{ old('paint_correction_type') == 'Paint Restoration' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="restoration">
                                        Paint Restoration
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="service-option">
                            <div class="form-check">
                                <input class="form-check-input service-checkbox" type="checkbox" value="Ceramic Coating" name="services[]" id="ceramicCoating" data-has-sub="true" {{ in_array('Ceramic Coating', old('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ceramicCoating">
                                    <h5>Ceramic Coating</h5>
                                    <p>Premium coating that provides superior protection</p>
                                </label>
                            </div>
                            <div class="sub-options">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ceramic_coating_type" id="detailingX" value="DetailingX" {{ old('ceramic_coating_type') == 'DetailingX' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="detailingX">
                                        DetailingX
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ceramic_coating_type" id="csl" value="CSL" {{ old('ceramic_coating_type') == 'CSL' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="csl">
                                        CSL
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ceramic_coating_type" id="cslBlack" value="CSL Black" {{ old('ceramic_coating_type') == 'CSL Black' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cslBlack">
                                        CSL Black
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ceramic_coating_type" id="csu" value="CSU" {{ old('ceramic_coating_type') == 'CSU' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="csu">
                                        CSU
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ceramic_coating_type" id="csuBlack" value="CSU Black" {{ old('ceramic_coating_type') == 'CSU Black' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="csuBlack">
                                        CSU Black
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="service-option">
                            <div class="form-check">
                                <input class="form-check-input service-checkbox" type="checkbox" value="Interior Detail" name="services[]" id="interiorDetail" data-has-sub="false" {{ in_array('Interior Detail', old('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="interiorDetail">
                                    <h5>Interior Detail</h5>
                                    <p>Deep cleaning of your vehicle's interior</p>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="service-option">
                            <div class="form-check">
                                <input class="form-check-input service-checkbox" type="checkbox" value="Exterior Detail" name="services[]" id="exteriorDetail" data-has-sub="false" {{ in_array('Exterior Detail', old('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="exteriorDetail">
                                    <h5>Exterior Detail</h5>
                                    <p>Thorough cleaning and polishing of your vehicle's exterior</p>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="service-option">
                            <div class="form-check">
                                <input class="form-check-input service-checkbox" type="checkbox" value="Headlight Restoration" name="services[]" id="headlightRestoration" data-has-sub="false" {{ in_array('Headlight Restoration', old('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="headlightRestoration">
                                    <h5>Headlight Restoration</h5>
                                    <p>Restore foggy or yellowed headlights</p>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="service-option">
                            <div class="form-check">
                                <input class="form-check-input service-checkbox" type="checkbox" value="Leather Treatment" name="services[]" id="leatherTreatment" data-has-sub="false" {{ in_array('Leather Treatment', old('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="leatherTreatment">
                                    <h5>Leather Treatment</h5>
                                    <p>Clean, condition and protect leather surfaces</p>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="service-option">
                            <div class="form-check">
                                <input class="form-check-input service-checkbox" type="checkbox" value="Custom" name="services[]" id="customService" data-has-sub="true" {{ in_array('Custom', old('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="customService">
                                    <h5>Custom Service</h5>
                                    <p>Request a custom detailing service</p>
                                </label>
                            </div>
                            <div class="sub-options">
                                <div class="form-group">
                                    <label for="customServiceDescription">Please describe the custom service you require:</label>
                                    <textarea class="form-control" id="customServiceDescription" name="custom_service_description" rows="3">{{ old('custom_service_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Notes Section -->
            <div class="form-section">
                <div class="section-title">
                    <span class="section-number">4</span>Additional Notes
                </div>
                <div class="mb-3">
                    <label for="additionalNotes" class="form-label">Any specific requirements or concerns?</label>
                    <textarea class="form-control" id="additionalNotes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <button type="submit" class="submit-btn">Submit Service Request</button>
            </div>
        </form>
    </div>
    
    <div class="footer text-center">
        <p>© 2025 AutoX Studio. All rights reserved.</p>
        <p>Premium car detailing services in Australia</p>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script to handle service options
        document.addEventListener('DOMContentLoaded', function() {
            // Get the Full Detail checkbox
            const fullDetailCheckbox = document.getElementById('fullDetail');
            const allServiceCheckboxes = document.querySelectorAll('.service-checkbox');
            const serviceOptions = document.querySelectorAll('.service-option');
            
            // Function to toggle other services based on Full Detail selection
            function toggleOtherServices() {
                const isFullDetailChecked = fullDetailCheckbox.checked;
                
                // Loop through all service checkboxes
                allServiceCheckboxes.forEach(checkbox => {
                    // Skip the Full Detail checkbox itself
                    if (checkbox.id !== 'fullDetail') {
                        // Disable or enable based on Full Detail selection
                        checkbox.disabled = isFullDetailChecked;
                        
                        const serviceOption = checkbox.closest('.service-option');
                        
                        // Add or remove the disabled class for styling
                        if (isFullDetailChecked) {
                            serviceOption.classList.add('disabled');
                            // Uncheck the checkbox if Full Detail is selected
                            checkbox.checked = false;
                            serviceOption.classList.remove('selected');
                            
                            // Hide sub-options
                            const subOptions = serviceOption.querySelector('.sub-options');
                            if (subOptions) {
                                subOptions.style.display = 'none';
                                
                                // Clear radio selections
                                const radioInputs = subOptions.querySelectorAll('input[type="radio"]');
                                radioInputs.forEach(radio => radio.checked = false);
                                
                                // Clear text inputs
                                const textInputs = subOptions.querySelectorAll('textarea');
                                textInputs.forEach(input => input.value = '');
                            }
                        } else {
                            serviceOption.classList.remove('disabled');
                        }
                    }
                });
            }
            
            // Add event listener to Full Detail checkbox
            fullDetailCheckbox.addEventListener('change', toggleOtherServices);
            
            // Initialize options based on previous selection
            document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                const serviceOption = checkbox.closest('.service-option');
                serviceOption.classList.toggle('selected', checkbox.checked);
                
                // Show sub-options if parent is checked and has sub-options
                if (checkbox.checked && checkbox.dataset.hasSub === 'true') {
                    const subOptions = serviceOption.querySelector('.sub-options');
                    if (subOptions) {
                        subOptions.style.display = 'block';
                    }
                }
            });
            
            // Run initially to set correct state based on form load state
            toggleOtherServices();
            
            // Handle click on service options
            serviceOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    // Get the checkbox
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    
                    // If the option is disabled or clicking in sub-options, do nothing
                    if (checkbox.disabled || 
                        e.target.tagName === 'INPUT' || 
                        e.target.tagName === 'TEXTAREA' || 
                        e.target.tagName === 'LABEL' || 
                        e.target.closest('.sub-options')) {
                        return;
                    }
                    
                    // Toggle checkbox
                    checkbox.checked = !checkbox.checked;
                    this.classList.toggle('selected', checkbox.checked);
                    
                    // If this is Full Detail, update other checkboxes
                    if (checkbox.id === 'fullDetail') {
                        toggleOtherServices();
                    }
                    
                    // Show/hide sub-options
                    if (checkbox.dataset.hasSub === 'true') {
                        const subOptions = this.querySelector('.sub-options');
                        if (subOptions) {
                            subOptions.style.display = checkbox.checked ? 'block' : 'none';
                        }
                    }
                    
                    // If unchecking, clear radio buttons
                    if (!checkbox.checked) {
                        const subRadios = this.querySelectorAll('.sub-options input[type="radio"]');
                        subRadios.forEach(radio => radio.checked = false);
                    }
                });
            });
            
            // Prevent double click issues on checkboxes
            allServiceCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    // Skip if disabled
                    if (this.disabled) {
                        return;
                    }
                    
                    const serviceOption = this.closest('.service-option');
                    serviceOption.classList.toggle('selected', this.checked);
                    
                    // If this is the Full Detail checkbox, toggle other services
                    if (this.id === 'fullDetail') {
                        toggleOtherServices();
                    }
                    
                    // Show/hide sub-options
                    if (this.dataset.hasSub === 'true') {
                        const subOptions = serviceOption.querySelector('.sub-options');
                        if (subOptions) {
                            subOptions.style.display = this.checked ? 'block' : 'none';
                        }
                    }
                    
                    // If unchecking, clear radio buttons
                    if (!this.checked) {
                        const subRadios = serviceOption.querySelectorAll('.sub-options input[type="radio"]');
                        subRadios.forEach(radio => radio.checked = false);
                    }
                });
            });
            
            // Prevent form elements in sub-options from triggering parent clicks
            document.querySelectorAll('.sub-options textarea, .sub-options input').forEach(element => {
                element.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
            
            // Form validation before submit
            document.getElementById('serviceRequestForm').addEventListener('submit', function(e) {
                // Check if any service is selected
                const anyServiceSelected = document.querySelectorAll('.service-checkbox:checked').length > 0;
                if (!anyServiceSelected) {
                    e.preventDefault();
                    alert('Please select at least one service.');
                    return;
                }
                
                // Check if sub-options are selected when parent is selected
                let valid = true;
                document.querySelectorAll('.service-checkbox[data-has-sub="true"]:checked').forEach(checkbox => {
                    const serviceOption = checkbox.closest('.service-option');
                    const serviceValue = checkbox.value;
                    
                    // Determine which radio group to check
                    let radioGroupName = '';
                    if (serviceValue === 'Ceramic Coating') {
                        radioGroupName = 'ceramic_coating_type';
                    } else if (serviceValue === 'Paint Correction') {
                        radioGroupName = 'paint_correction_type';
                    } else if (serviceValue === 'Custom') {
                        // For custom service, check if description is provided
                        const description = document.getElementById('customServiceDescription').value.trim();
                        if (!description) {
                            valid = false;
                            alert('Please provide a description for your custom service request.');
                        }
                        return;
                    }
                    
                    if (radioGroupName) {
                        const anyRadioSelected = serviceOption.querySelector(`input[name="${radioGroupName}"]:checked`);
                        if (!anyRadioSelected) {
                            valid = false;
                            alert(`Please select a specific option for ${serviceValue}.`);
                        }
                    }
                });
                
                if (!valid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>