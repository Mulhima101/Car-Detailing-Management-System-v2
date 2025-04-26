<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoX Studio - Premium Car Detailing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --autox-yellow: #FFDD00;
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
        .hero {
            background-color: #212529;
            color: white;
            padding: 100px 0;
        }
        .service-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .service-icon {
            font-size: 3rem;
            color: var(--autox-yellow);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('public/images/autox-logo.png') }}" alt="AutoX Studio" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('service.create') }}">Book Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container text-center">
            <h1 class="display-4">Premium Car Detailing Services</h1>
            <p class="lead">Experience the ultimate car care with AutoX Studio</p>
            <a href="{{ route('service.create') }}" class="btn btn-autox btn-lg mt-3">Book a Service</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5" id="services">
        <div class="container">
            <h2 class="text-center mb-5">Our Services</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">🧽</div>
                            <h5 class="card-title">Full Detail</h5>
                            <p class="card-text">Complete interior and exterior detailing service that leaves your car looking showroom fresh.</p>
                            <a href="{{ route('service.form') }}" class="btn btn-dark mt-3">Book Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">🛡️</div>
                            <h5 class="card-title">Paint Protection</h5>
                            <p class="card-text">Long-lasting protection for your vehicle's paint to keep it looking pristine for years.</p>
                            <a href="{{ route('service.form') }}" class="btn btn-dark mt-3">Book Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">✨</div>
                            <h5 class="card-title">Ceramic Coating</h5>
                            <p class="card-text">Premium coating that provides superior protection against environmental contaminants.</p>
                            <a href="{{ route('service.create') }}" class="btn btn-dark mt-3">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose AutoX Studio</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <h1 class="display-4 text-warning">10+</h1>
                        <p>Years Experience</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h1 class="display-4 text-warning">5k+</h1>
                        <p>Happy Customers</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h1 class="display-4 text-warning">100%</h1>
                        <p>Satisfaction</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h1 class="display-4 text-warning">24/7</h1>
                        <p>Customer Support</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-autox-yellow">
        <div class="container text-center">
            <h2 class="mb-4">Ready to give your car the care it deserves?</h2>
            <a href="{{ route('service.create') }}" class="btn btn-dark btn-lg px-5">Book Your Service Today</a>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Contact Us</h2>
            <div class="row">
                <div class="col-md-6">
                    <h5>Our Location</h5>
                    <p>123 Car Detail Street<br>Sydney, NSW 2000<br>Australia</p>
                    <h5 class="mt-4">Opening Hours</h5>
                    <p>Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 5:00 PM<br>Sunday: Closed</p>
                </div>
                <div class="col-md-6">
                    <h5>Get In Touch</h5>
                    <p>Phone: (02) 1234 5678</p>
                    <p>Email: info@autoxstudio.com.au</p>
                    <div class="mt-4">
                        <a href="#" class="btn btn-outline-dark me-2">Facebook</a>
                        <a href="#" class="btn btn-outline-dark me-2">Instagram</a>
                        <a href="#" class="btn btn-outline-dark">Twitter</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-dark text-white">
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