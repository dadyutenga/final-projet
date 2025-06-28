<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking System - Welcome</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .booking-form {
            padding: 40px 30px;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.3rem;
            border-bottom: 2px solid #4facfe;
            padding-bottom: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .form-row .form-group {
            flex: 1;
            min-width: 200px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #4facfe;
        }

        .btn {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s ease;
            width: 100%;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .room-selection {
            display: none;
            margin-top: 20px;
        }

        .room-card {
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .room-card:hover {
            border-color: #4facfe;
            transform: translateY(-2px);
        }

        .room-card.selected {
            border-color: #4facfe;
            background-color: #f0f8ff;
        }

        .room-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .room-details h4 {
            color: #333;
            margin-bottom: 5px;
        }

        .room-details p {
            color: #666;
            margin-bottom: 3px;
        }

        .room-price {
            text-align: right;
        }

        .room-price .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4facfe;
        }

        .room-price .per-night {
            color: #666;
            font-size: 0.9rem;
        }

        .booking-summary {
            display: none;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .booking-summary h4 {
            color: #333;
            margin-bottom: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-row.total {
            border-top: 2px solid #4facfe;
            padding-top: 10px;
            font-weight: bold;
            font-size: 1.2rem;
            color: #4facfe;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert.error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .container {
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏨 Hotel Booking System</h1>
            <p>Find and book your perfect stay with us</p>
        </div>

        <div class="booking-form">
            <div class="alert" id="alertMessage"></div>

            <form id="bookingForm">
                <!-- Hotel Selection -->
                <div class="form-section">
                    <h3>🏨 Select Hotel</h3>
                    <div class="form-group">
                        <label for="hotel_id">Choose Hotel:</label>
                        <select id="hotel_id" name="hotel_id" required>
                            <option value="">Loading hotels...</option>
                        </select>
                    </div>
                </div>

                <!-- Dates and Guests -->
                <div class="form-section">
                    <h3>📅 Booking Details</h3>
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
                    <button type="button" id="searchRooms" class="btn" disabled>🔍 Search Available Rooms</button>
                </div>

                <!-- Room Selection -->
                <div class="form-section room-selection" id="roomSelection">
                    <h3>🛏️ Available Rooms</h3>
                    <div id="roomsList" class="loading">
                        Searching for available rooms...
                    </div>
                </div>

                <!-- Guest Information -->
                <div class="form-section" id="guestInfo" style="display: none;">
                    <h3>👤 Guest Information</h3>
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
                    <h4>📋 Booking Summary</h4>
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
                    ✅ Confirm Booking
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
            roomsList.innerHTML = '<div class="loading">Searching for available rooms...</div>';
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
                    roomsList.innerHTML = '<p>No rooms available for the selected dates and guest count.</p>';
                }
            } catch (error) {
                console.error('Error searching rooms:', error);
                roomsList.innerHTML = '<p>Error searching for rooms. Please try again.</p>';
            }
        }

        // Display available rooms
        function displayRooms(rooms) {
            const roomsList = document.getElementById('roomsList');
            
            if (rooms.length === 0) {
                roomsList.innerHTML = '<p>No rooms available for your search criteria.</p>';
                return;
            }

            let html = '';
            rooms.forEach(room => {
                html += `
                    <div class="room-card" onclick="selectRoom(${room.room_id})">
                        <div class="room-info">
                            <div class="room-details">
                                <h4>Room ${room.room_number} - ${room.type_name}</h4>
                                <p>Capacity: ${room.capacity} guests</p>
                                <p>Status: ${room.status}</p>
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
            submitBtn.textContent = 'Processing...';

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
                submitBtn.textContent = '✅ Confirm Booking';
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