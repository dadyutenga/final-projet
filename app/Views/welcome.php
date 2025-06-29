
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System Hotel - Premium Accommodation</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #32CD32;
            --primary-dark: #228B22;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --dark-gray: #333333;
            --text-gray: #666666;
            --border-color: #e0e0e0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark-gray);
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark-gray);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .mobile-menu {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-gray);
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                        url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--white);
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            animation: fadeInUp 1s ease;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 3rem;
            font-weight: 300;
        }

        /* Book Now Button */
        .book-now-btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 20px 40px;
            border-radius: 50px;
            font-size: 1.3rem;
            font-weight: 700;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 15px 35px rgba(50, 205, 50, 0.4);
            transition: all 0.4s ease;
            border: 3px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .book-now-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(50, 205, 50, 0.6);
            border-color: var(--white);
        }

        .book-now-btn:active {
            transform: translateY(-2px);
        }

        .book-now-btn i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        /* Pulse animation for the button */
        .book-now-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .book-now-btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(50, 205, 50, 0.3);
        }

        /* Sections */
        section {
            padding: 5rem 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.1rem;
            color: var(--text-gray);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Rooms Section */
        .rooms {
            background: var(--light-gray);
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .room-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            transform: translateY(30px);
        }

        .room-card.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .room-image {
            height: 250px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .room-price {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--primary-color);
            color: var(--white);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
        }

        .room-content {
            padding: 1.5rem;
        }

        .room-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark-gray);
        }

        .room-content p {
            color: var(--text-gray);
            margin-bottom: 1rem;
        }

        .room-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .feature-tag {
            background: var(--light-gray);
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.9rem;
            color: var(--text-gray);
        }

        /* Features Section */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .feature-item {
            text-align: center;
            padding: 2rem;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            opacity: 0;
            transform: translateY(30px);
        }

        .feature-item.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-item:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .feature-item h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark-gray);
        }

        .feature-item p {
            color: var(--text-gray);
        }

        /* Testimonials Section */
        .testimonials {
            background: var(--light-gray);
        }

        .testimonials-container {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .testimonial-slider {
            overflow: hidden;
            border-radius: 15px;
        }

        .testimonial-track {
            display: flex;
            transition: transform 0.5s ease;
        }

        .testimonial {
            min-width: 100%;
            background: var(--white);
            padding: 3rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .testimonial-text {
            font-size: 1.2rem;
            font-style: italic;
            color: var(--text-gray);
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
        }

        .author-info h4 {
            font-weight: 600;
            color: var(--dark-gray);
        }

        .author-info p {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .testimonial-nav {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .nav-btn {
            background: var(--primary-color);
            color: var(--white);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.1);
        }

        .testimonial-dots {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--border-color);
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .dot.active {
            background: var(--primary-color);
        }

        /* Footer */
        footer {
            background: var(--dark-gray);
            color: var(--white);
            padding: 3rem 0 1rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section ul li a {
            color: var(--white);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: var(--primary-color);
        }

        .social-icons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-icons a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #555;
            color: var(--text-gray);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu {
                display: block;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .book-now-btn {
                padding: 15px 30px;
                font-size: 1.1rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .rooms-grid {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .testimonial {
                padding: 2rem 1rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2rem;
            }

            .book-now-btn {
                padding: 12px 25px;
                font-size: 1rem;
                letter-spacing: 1px;
            }

            section {
                padding: 3rem 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="container">
            <div class="logo">Hotel Management System</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#rooms">Rooms</a></li>
                <li><a href="manager/login">Managers-Login</a></li>
                <li><a href="admin/login">Admin-Login</a></li>
                <li><a href="staff/login">Staff-Login</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <button class="mobile-menu">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Welcome to Hotel Management System</h1>
            <p>Experience luxury and comfort in the heart of the city</p>
            
            <a href="<?= base_url('book') ?>" class="book-now-btn">
                <i class="fas fa-calendar-check"></i>
                Click Here to Book Room
            </a>
        </div>
    </section>

    <!-- Rooms Section -->
    <section id="rooms" class="rooms">
        <div class="container">
            <div class="section-title">
                <h2>Our Premium Rooms</h2>
                <p>Choose from our carefully designed rooms, each offering unique comfort and luxury</p>
            </div>
            
            <div class="rooms-grid">
                <div class="room-card">
                    <div class="room-image" style="background-image: url('https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                        <div class="room-price">$199/night</div>
                    </div>
                    <div class="room-content">
                        <h3>Deluxe King Room</h3>
                        <p>Spacious room with king-size bed, city view, and modern amenities for the perfect stay.</p>
                        <div class="room-features">
                            <span class="feature-tag">King Bed</span>
                            <span class="feature-tag">City View</span>
                            <span class="feature-tag">32" TV</span>
                            <span class="feature-tag">Mini Bar</span>
                        </div>
                        <a href="<?= base_url('book') ?>" class="btn-primary">Book Room</a>
                    </div>
                </div>

                <div class="room-card">
                    <div class="room-image" style="background-image: url('https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                        <div class="room-price">$299/night</div>
                    </div>
                    <div class="room-content">
                        <h3>Executive Suite</h3>
                        <p>Luxurious suite with separate living area, premium furnishings, and exclusive amenities.</p>
                        <div class="room-features">
                            <span class="feature-tag">Living Area</span>
                            <span class="feature-tag">Ocean View</span>
                            <span class="feature-tag">Balcony</span>
                            <span class="feature-tag">Premium Bath</span>
                        </div>
                        <a href="<?= base_url('book') ?>" class="btn-primary">Book Room</a>
                    </div>
                </div>

                <div class="room-card">
                    <div class="room-image" style="background-image: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                        <div class="room-price">$149/night</div>
                    </div>
                    <div class="room-content">
                        <h3>Standard Double</h3>
                        <p>Comfortable room with twin beds, perfect for business travelers or friends sharing.</p>
                        <div class="room-features">
                            <span class="feature-tag">Twin Beds</span>
                            <span class="feature-tag">Work Desk</span>
                            <span class="feature-tag">Free WiFi</span>
                            <span class="feature-tag">Coffee Maker</span>
                        </div>
                        <a href="<?= base_url('book') ?>" class="btn-primary">Book Room</a>
                    </div>
                </div>

                <div class="room-card">
                    <div class="room-image" style="background-image: url('https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80');">
                        <div class="room-price">$399/night</div>
                    </div>
                    <div class="room-content">
                        <h3>Presidential Suite</h3>
                        <p>Ultimate luxury with panoramic views, private terrace, and personalized concierge service.</p>
                        <div class="room-features">
                            <span class="feature-tag">Private Terrace</span>
                            <span class="feature-tag">Jacuzzi</span>
                            <span class="feature-tag">Butler Service</span>
                            <span class="feature-tag">Premium Location</span>
                        </div>
                        <a href="<?= base_url('book') ?>" class="btn-primary">Book Room</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-title">
                <h2>Hotel Features</h2>
                <p>Enjoy world-class amenities and services designed for your comfort and convenience</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <h3>Free Wi-Fi</h3>
                    <p>High-speed internet access throughout the hotel for all your connectivity needs</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>Complimentary Breakfast</h3>
                    <p>Start your day with our delicious continental breakfast buffet included in your stay</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-swimming-pool"></i>
                    </div>
                    <h3>Outdoor Pool</h3>
                    <p>Relax and unwind in our beautiful outdoor swimming pool with poolside service</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-plane"></i>
                    </div>
                    <h3>Airport Shuttle</h3>
                    <p>Complimentary shuttle service to and from the airport for your convenience</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h3>Fitness Center</h3>
                    <p>Stay active with our fully equipped 24/7 fitness center and modern equipment</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h3>Concierge Service</h3>
                    <p>Our dedicated concierge team is available 24/7 to assist with all your needs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>What Our Guests Say</h2>
                <p>Read reviews from our satisfied guests who experienced the Hotel Management System difference</p>
            </div>
            
            <div class="testimonials-container">
                <div class="testimonial-slider">
                    <div class="testimonial-track">
                        <div class="testimonial">
                            <p class="testimonial-text">"Absolutely amazing experience! The staff was incredibly friendly, the room was spotless, and the amenities exceeded our expectations. Will definitely be returning!"</p>
                            <div class="testimonial-author">
                                <div class="author-avatar" style="background-image: url('https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=687&q=80');"></div>
                                <div class="author-info">
                                    <h4>Sarah Johnson</h4>
                                    <p>Business Traveler</p>
                                </div>
                            </div>
                        </div>

                        <div class="testimonial">
                            <p class="testimonial-text">"Perfect location, beautiful rooms, and outstanding service. The breakfast was delicious and the pool area was so relaxing. Highly recommend Hotel Management System!"</p>
                            <div class="testimonial-author">
                                <div class="author-avatar" style="background-image: url('https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80');"></div>
                                <div class="author-info">
                                    <h4>Michael Chen</h4>
                                    <p>Vacation Guest</p>
                                </div>
                            </div>
                        </div>

                        <div class="testimonial">
                            <p class="testimonial-text">"From check-in to check-out, everything was seamless. The concierge helped us plan our entire itinerary. This hotel truly understands hospitality!"</p>
                            <div class="testimonial-author">
                                <div class="author-avatar" style="background-image: url('https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80');"></div>
                                <div class="author-info">
                                    <h4>Emily Rodriguez</h4>
                                    <p>Honeymoon Guest</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-nav">
                    <button class="nav-btn" id="prevBtn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="nav-btn" id="nextBtn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                
                <div class="testimonial-dots">
                    <span class="dot active" data-slide="0"></span>
                    <span class="dot" data-slide="1"></span>
                    <span class="dot" data-slide="2"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Hotel Management System Hotel</h3>
                    <p>Experience luxury and comfort in the heart of the city. Your perfect stay awaits.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#rooms">Rooms & Suites</a></li>
                        <li><a href="#features">Amenities</a></li>
                        <li><a href="#testimonials">Reviews</a></li>
                        <li><a href="#">Gallery</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Services</h3>
                    <ul>
                        <li><a href="#">Room Service</a></li>
                        <li><a href="#">Concierge</a></li>
                        <li><a href="#">Airport Shuttle</a></li>
                        <li><a href="#">Business Center</a></li>
                        <li><a href="#">Event Planning</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Bibi  Titi </li>
                        <li><i class="fas fa-phone"></i> No Phone</li>
                        <li><i class="fas fa-envelope"></i> info@hotelmanagementsystem.com</li>
                        <li><i class="fas fa-clock"></i> 24/7 Front Desk Service</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 Hotel Management System Hotel. All rights reserved. | Privacy Policy | Terms of Service</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
                header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.boxShadow = 'none';
            }
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.room-card, .feature-item').forEach(el => {
            observer.observe(el);
        });

        // Testimonial slider
        class TestimonialSlider {
            constructor() {
                this.currentSlide = 0;
                this.slides = document.querySelectorAll('.testimonial');
                this.totalSlides = this.slides.length;
                this.track = document.querySelector('.testimonial-track');
                this.dots = document.querySelectorAll('.dot');
                this.prevBtn = document.getElementById('prevBtn');
                this.nextBtn = document.getElementById('nextBtn');
                
                this.init();
            }
            
            init() {
                this.prevBtn.addEventListener('click', () => this.prevSlide());
                this.nextBtn.addEventListener('click', () => this.nextSlide());
                
                this.dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => this.goToSlide(index));
                });
                
                // Auto-play slider
                setInterval(() => this.nextSlide(), 5000);
            }
            
            updateSlider() {
                const translateX = -this.currentSlide * 100;
                this.track.style.transform = `translateX(${translateX}%)`;
                
                // Update dots
                this.dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === this.currentSlide);
                });
            }
            
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                this.updateSlider();
            }
            
            prevSlide() {
                this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                this.updateSlider();
            }
            
            goToSlide(index) {
                this.currentSlide = index;
                this.updateSlider();
            }
        }

        // Initialize slider when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new TestimonialSlider();
        });

        // Mobile menu toggle (basic implementation)
        document.querySelector('.mobile-menu').addEventListener('click', function() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
        });
    </script>
</body>
</html>