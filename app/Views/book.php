<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking System - Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #008080;  /* Teal */
            --primary-dark: #005050;   /* Darker teal */
            --primary-light: #40E0D0;  /* Lighter teal */
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --dark-gray: #333333;
            --text-gray: #666666;
            --border-color: #e0e0e0;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-gray);
            color: var(--dark-gray);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: var(--white);
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .booking-form {
            padding: 40px 30px;
        }

        .form-section {
            margin-bottom: 30px;
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .form-section h3 {
            color: var(--dark-gray);
            margin-bottom: 20px;
            font-size: 1.3rem;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-gray);
            font-weight: 500;
            font-size: 0.95rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: var(--white);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 128, 128, 0.1);
        }

        .btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: var(--white);
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
            font-family: 'Poppins', sans-serif;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 128, 128, 0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .room-selection {
            display: none;
            margin-top: 20px;
        }

        .room-card {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .room-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 128, 128, 0.15);
        }

        .room-card.selected {
            border-color: var(--primary-color);
            background: rgba(64, 224, 208, 0.05);
            box-shadow: 0 8px 25px rgba(0, 128, 128, 0.2);
        }

        .room-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .room-details h4 {
            color: var(--dark-gray);
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .room-details p {
            color: var(--text-gray);
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .room-price {
            text-align: right;
        }

        .room-price .price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2px;
        }

        .room-price .per-night {
            color: var(--text-gray);
            font-size: 0.85rem;
            font-weight: 400;
        }

        .booking-summary {
            display: none;
            background: var(--light-gray);
            padding: 25px;
            border-radius: 12px;
            margin-top: 20px;
            border: 1px solid var(--border-color);
        }

        .booking-summary h4 {
            color: var(--dark-gray);
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(224, 224, 224, 0.5);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row span:first-child {
            color: var(--text-gray);
            font-weight: 500;
        }

        .summary-row span:last-child {
            color: var(--dark-gray);
            font-weight: 500;
        }

        .summary-row.total {
            border-top: 2px solid var(--primary-color);
            padding-top: 15px;
            margin-top: 15px;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .summary-row.total span {
            color: var(--primary-color);
            font-weight: 700;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            font-weight: 500;
            border-left: 4px solid;
        }

        .alert.success {
            background-color: #d4edda;
            border-color: var(--success);
            color: #155724;
        }

        .alert.error {
            background-color: #f8d7da;
            border-color: var(--danger);
            color: #721c24;
        }

        .loading {
            text-align: center;
            padding: 30px 20px;
            color: var(--text-gray);
            font-style: italic;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--white);
            background: var(--primary-color);
            margin-right: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .container {
                margin: 0;
                border-radius: 10px;
            }

            .booking-form {
                padding: 20px;
            }

            .form-section {
                padding: 15px;
            }

            .room-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .room-price {
                text-align: left;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .booking-form {
                padding: 15px;
            }

            .form-section {
                padding: 12px;
            }
        }

        /* Loading Animation */
        .loading::after {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Enhanced hover effects */
        .form-section:hover {
            box-shadow: 0 6px 25px rgba(0, 128, 128, 0.1);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-gray);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-hotel" style="margin-right: 15px;"></i>Hotel Booking System</h1>
            <p>Find and book your perfect stay with us</p>
        </div>

        <div class="booking-form">
            <div class="alert" id="alertMessage"></div>

            <form id="bookingForm">
                <!-- Hotel Selection -->
                <div class="form-section">
                    <h3><i class="fas fa-building"></i>Select Hotel</h3>
                    <div class="form-group">
                        <label for="hotel_id">Choose Hotel:</label>
                        <select id="hotel_id" name="hotel_id" required>
                            <option value="">Loading hotels...</option>
                        </select>
                    </div>
                </div>

                <!-- Dates and Guests -->
                <div class="form-section">
                    <h3><i class="fas fa-calendar-alt"></i>Booking Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="check_in">Check-in Date:</label>
                            <input type="date" id="check_in" name="check_in" required>
                        </div>
                        <div class="form-group">
                            <label for="check_out">Check-out Date:</label>
                            <input type="date" id="check_out" name="check_out" required>
                        </div>
                        <div class="form-group">
                            <label for="guests">Number of Guests:</label>
                            <select id="guests" name="guests" required>
                                <option value="1">1 Guest</option>
                                <option value="2">2 Guests</option>
                                <option value="3">3 Guests</option>
                                <option value="4">4 Guests</option>
                                <option value="5">5 Guests</option>
                                <option value="6">6+ Guests</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" id="searchRooms" class="btn" disabled>
                        <i class="fas fa-search"></i> Search Available Rooms
                    </button>
                </div>

                <!-- Room Selection -->
                <div class="form-section room-selection" id="roomSelection">
                    <h3><i class="fas fa-bed"></i>Available Rooms</h3>
                    <div id="roomsList" class="loading">
                        Searching for available rooms
                    </div>
                </div>

                <!-- Guest Information -->
                <div class="form-section" id="guestInfo" style="display: none;">
                    <h3><i class="fas fa-user"></i>Guest Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="guest_name">Full Name:</label>
                            <input type="text" id="guest_name" name="guest_name" required>
                        </div>
                        <div class="form-group">
                            <label for="guest_phone">Phone Number:</label>
                            <input type="tel" id="guest_phone" name="guest_phone" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="guest_email">Email (Optional):</label>
                        <input type="email" id="guest_email" name="guest_email">
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="booking-summary" id="bookingSummary">
                    <h4><i class="fas fa-clipboard-list"></i>Booking Summary</h4>
                    <div class="summary-row">
                        <span>Hotel:</span>
                        <span id="summaryHotel">-</span>
                    </div>
                    <div class="summary-row">
                        <span>Room:</span>
                        <span id="summaryRoom">-</span>
                    </div>
                    <div class="summary-row">
                        <span>Check-in:</span>
                        <span id="summaryCheckIn">-</span>
                    </div>
                    <div class="summary-row">
                        <span>Check-out:</span>
                        <span id="summaryCheckOut">-</span>
                    </div>
                    <div class="summary-row">
                        <span>Nights:</span>
                        <span id="summaryNights">-</span>
                    </div>
                    <div class="summary-row">
                        <span>Guests:</span>
                        <span id="summaryGuests">-</span>
                    </div>
                    <div class="summary-row">
                        <span>Price per night:</span>
                        <span id="summaryPricePerNight">-</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Price:</span>
                        <span id="summaryTotal">-</span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="confirmBooking" class="btn" style="display: none;">
                    <i class="fas fa-check-circle"></i> Confirm Booking
                </button>
            </form>
        </div>
    </div>

    <script>
        let selectedRoom = null;
        let availableRooms = [];
        let hotelData = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadHotels();
            setMinDate();
            setupEventListeners();
        });

        // Set minimum date to today
        function setMinDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('check_in').min = today;
            document.getElementById('check_out').min = today;
        }

        // Setup event listeners
        function setupEventListeners() {
            // Hotel selection change
            document.getElementById('hotel_id').addEventListener('change', function() {
                validateSearchButton();
            });

            // Date changes
            document.getElementById('check_in').addEventListener('change', function() {
                const checkIn = this.value;
                const checkOutField = document.getElementById('check_out');
                checkOutField.min = checkIn;
                
                // Clear check-out if it's before check-in
                if (checkOutField.value && checkOutField.value <= checkIn) {
                    checkOutField.value = '';
                }
                validateSearchButton();
            });

            document.getElementById('check_out').addEventListener('change', function() {
                validateSearchButton();
            });

            // Search rooms button
            document.getElementById('searchRooms').addEventListener('click', searchRooms);

            // Form submission
            document.getElementById('bookingForm').addEventListener('submit', processBooking);
        }

        // Load hotels from API
        async function loadHotels() {
            try {
                const response = await fetch('<?= base_url('customer-booking/get-hotels') ?>', {
                    method: 'GET'
                });
                
                const hotels = await response.json();
                const hotelSelect = document.getElementById('hotel_id');
                
                if (Array.isArray(hotels) && hotels.length > 0) {
                    hotelData = hotels;
                    hotelSelect.innerHTML = '<option value="">Select a hotel</option>';
                    hotels.forEach(hotel => {
                        hotelSelect.innerHTML += `<option value="${hotel.hotel_id}">${hotel.name} - ${hotel.location}</option>`;
                    });
                } else {
                    hotelSelect.innerHTML = '<option value="">No hotels available</option>';
                }
            } catch (error) {
                console.error('Error loading hotels:', error);
                document.getElementById('hotel_id').innerHTML = '<option value="">Error loading hotels</option>';
            }
        }

        // Validate search button
        function validateSearchButton() {
            const hotelId = document.getElementById('hotel_id').value;
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            
            const searchBtn = document.getElementById('searchRooms');
            searchBtn.disabled = !(hotelId && checkIn && checkOut);
        }

        // Search for available rooms
        async function searchRooms() {
            const formData = new FormData();
            formData.append('hotel_id', document.getElementById('hotel_id').value);
            formData.append('check_in', document.getElementById('check_in').value);
            formData.append('check_out', document.getElementById('check_out').value);
            formData.append('guests', document.getElementById('guests').value);

            const roomsList = document.getElementById('roomsList');
            roomsList.innerHTML = '<div class="loading">Searching for available rooms</div>';
            document.getElementById('roomSelection').style.display = 'block';

            try {
                const response = await fetch('<?= base_url('customer-booking/get-available-rooms') ?>', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success && result.rooms.length > 0) {
                    availableRooms = result.rooms;
                    displayRooms(result.rooms);
                } else {
                    roomsList.innerHTML = '<p style="text-align: center; color: var(--text-gray); padding: 20px;">No rooms available for the selected dates and guest count.</p>';
                }
            } catch (error) {
                console.error('Error searching rooms:', error);
                roomsList.innerHTML = '<p style="text-align: center; color: var(--danger); padding: 20px;">Error searching for rooms. Please try again.</p>';
            }
        }

        // Display available rooms
        function displayRooms(rooms) {
            const roomsList = document.getElementById('roomsList');
            
            if (rooms.length === 0) {
                roomsList.innerHTML = '<p style="text-align: center; color: var(--text-gray); padding: 20px;">No rooms available for your search criteria.</p>';
                return;
            }

            let html = '';
            rooms.forEach(room => {
                html += `
                    <div class="room-card" onclick="selectRoom(${room.room_id})">
                        <div class="room-info">
                            <div class="room-details">
                                <h4><i class="fas fa-door-open" style="color: var(--primary-color); margin-right: 8px;"></i>Room ${room.room_number} - ${room.type_name}</h4>
                                <p><i class="fas fa-users" style="margin-right: 5px;"></i>Capacity: ${room.capacity} guests</p>
                                <p><i class="fas fa-info-circle" style="margin-right: 5px;"></i>Status: ${room.status}</p>
                            </div>
                            <div class="room-price">
                                <div class="price">$${room.base_price}</div>
                                <div class="per-night">per night</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            roomsList.innerHTML = html;
        }

        // Select room
        function selectRoom(roomId) {
            selectedRoom = availableRooms.find(room => room.room_id == roomId);
            
            // Update room card selection
            document.querySelectorAll('.room-card').forEach(card => {
                card.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            
            // Show guest info and booking summary
            document.getElementById('guestInfo').style.display = 'block';
            updateBookingSummary();
            document.getElementById('confirmBooking').style.display = 'block';
        }

        // Update booking summary
        function updateBookingSummary() {
            if (!selectedRoom) return;

            const hotelName = hotelData.find(hotel => hotel.hotel_id == document.getElementById('hotel_id').value)?.name || 'Unknown Hotel';
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            const guests = document.getElementById('guests').value;

            // Calculate nights
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            const totalPrice = selectedRoom.base_price * nights;

            // Update summary
            document.getElementById('summaryHotel').textContent = hotelName;
            document.getElementById('summaryRoom').textContent = `Room ${selectedRoom.room_number} - ${selectedRoom.type_name}`;
            document.getElementById('summaryCheckIn').textContent = checkIn;
            document.getElementById('summaryCheckOut').textContent = checkOut;
            document.getElementById('summaryNights').textContent = nights;
            document.getElementById('summaryGuests').textContent = guests;
            document.getElementById('summaryPricePerNight').textContent = `$${selectedRoom.base_price}`;
            document.getElementById('summaryTotal').textContent = `$${totalPrice}`;

            document.getElementById('bookingSummary').style.display = 'block';
        }

        // Process booking
        async function processBooking(e) {
            e.preventDefault();
            
            if (!selectedRoom) {
                showAlert('Please select a room first.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('hotel_id', document.getElementById('hotel_id').value);
            formData.append('room_id', selectedRoom.room_id);
            formData.append('guest_name', document.getElementById('guest_name').value);
            formData.append('guest_phone', document.getElementById('guest_phone').value);
            formData.append('guest_email', document.getElementById('guest_email').value);
            formData.append('check_in_date', document.getElementById('check_in').value);
            formData.append('check_out_date', document.getElementById('check_out').value);
            formData.append('guests', document.getElementById('guests').value);

            const submitBtn = document.getElementById('confirmBooking');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            try {
                const response = await fetch('<?= base_url('customer-booking/process-booking') ?>', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert(`Booking confirmed! Your booking ticket is: ${result.booking_ticket}`, 'success');
                    document.getElementById('bookingForm').reset();
                    document.getElementById('roomSelection').style.display = 'none';
                    document.getElementById('guestInfo').style.display = 'none';
                    document.getElementById('bookingSummary').style.display = 'none';
                    document.getElementById('confirmBooking').style.display = 'none';
                    selectedRoom = null;
                } else {
                    showAlert(result.message || 'Booking failed. Please try again.', 'error');
                }
            } catch (error) {
                console.error('Error processing booking:', error);
                showAlert('An error occurred while processing your booking. Please try again.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Confirm Booking';
            }
        }

        // Show alert message
        function showAlert(message, type) {
            const alertDiv = document.getElementById('alertMessage');
            alertDiv.textContent = message;
            alertDiv.className = `alert ${type}`;
            alertDiv.style.display = 'block';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                alertDiv.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>