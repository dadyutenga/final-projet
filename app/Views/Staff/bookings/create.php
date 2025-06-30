<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Booking - Hotel Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #32CD32;
            --primary-dark: #228B22;
            --primary-light: #90EE90;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --dark-gray: #333333;
            --text-gray: #666666;
            --border-color: #e0e0e0;
            --sidebar-width: 280px;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--light-gray);
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--white);
            border-right: 1px solid var(--border-color);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 0.5rem;
            border-radius: 6px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .mobile-toggle {
                display: block;
            }
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
                padding-top: 4rem;
            }
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h2 {
            font-size: 1.5rem;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            color: var(--white);
        }

        .btn-secondary {
            background: var(--text-gray);
            color: var(--white);
        }

        .btn-secondary:hover {
            background: var(--dark-gray);
            color: var(--white);
        }

        .form-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--dark-gray);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .required {
            color: var(--danger-color);
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(50, 205, 50, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger-color);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .room-selection {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            background: var(--light-gray);
            margin-top: 0.5rem;
        }

        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .room-option {
            background: var(--white);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .room-option:hover {
            border-color: var(--primary-color);
        }

        .room-option.selected {
            border-color: var(--primary-color);
            background: rgba(50, 205, 50, 0.05);
        }

        .room-number {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.25rem;
        }

        .room-type {
            font-size: 0.8rem;
            color: var(--text-gray);
            margin-bottom: 0.5rem;
        }

        .room-price {
            color: var(--primary-color);
            font-weight: 600;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .price-calculation {
            background: var(--light-gray);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .price-total {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.1rem;
            border-top: 1px solid var(--border-color);
            padding-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .room-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar-overlay" onclick="closeSidebar()"></div>
    
    <?= $this->include('staff/shared/sidebar') ?>

    <div class="main-content">
        <div class="page-header">
            <h2>
                <i class="fas fa-plus-circle"></i>
                Create New Booking
            </h2>
            <a href="<?= base_url('staff/bookings') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Bookings
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form action="<?= base_url('staff/bookings/store') ?>" method="POST" id="bookingForm">
                <h3 style="margin-bottom: 1.5rem; color: var(--dark-gray);">
                    <i class="fas fa-user"></i> Guest Information
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="guest_name" class="form-label">
                            Guest Name <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control <?= isset($errors['guest_name']) ? 'is-invalid' : '' ?>" 
                               id="guest_name" name="guest_name" value="<?= old('guest_name') ?>" required>
                        <?php if (isset($errors['guest_name'])): ?>
                            <div class="invalid-feedback"><?= $errors['guest_name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="guest_phone" class="form-label">
                            Phone Number <span class="required">*</span>
                        </label>
                        <input type="tel" class="form-control <?= isset($errors['guest_phone']) ? 'is-invalid' : '' ?>" 
                               id="guest_phone" name="guest_phone" value="<?= old('guest_phone') ?>" required>
                        <?php if (isset($errors['guest_phone'])): ?>
                            <div class="invalid-feedback"><?= $errors['guest_phone'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="guest_email" class="form-label">Email Address</label>
                    <input type="email" class="form-control <?= isset($errors['guest_email']) ? 'is-invalid' : '' ?>" 
                           id="guest_email" name="guest_email" value="<?= old('guest_email') ?>">
                    <?php if (isset($errors['guest_email'])): ?>
                        <div class="invalid-feedback"><?= $errors['guest_email'] ?></div>
                    <?php endif; ?>
                </div>

                <h3 style="margin: 2rem 0 1.5rem; color: var(--dark-gray);">
                    <i class="fas fa-calendar-alt"></i> Booking Details
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="check_in_date" class="form-label">
                            Check-in Date <span class="required">*</span>
                        </label>
                        <input type="date" class="form-control <?= isset($errors['check_in_date']) ? 'is-invalid' : '' ?>" 
                               id="check_in_date" name="check_in_date" value="<?= old('check_in_date') ?>" 
                               min="<?= date('Y-m-d') ?>" onchange="updateAvailableRooms()" required>
                        <?php if (isset($errors['check_in_date'])): ?>
                            <div class="invalid-feedback"><?= $errors['check_in_date'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="check_out_date" class="form-label">
                            Check-out Date <span class="required">*</span>
                        </label>
                        <input type="date" class="form-control <?= isset($errors['check_out_date']) ? 'is-invalid' : '' ?>" 
                               id="check_out_date" name="check_out_date" value="<?= old('check_out_date') ?>" 
                               onchange="updateAvailableRooms()" required>
                        <?php if (isset($errors['check_out_date'])): ?>
                            <div class="invalid-feedback"><?= $errors['check_out_date'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="guests_count" class="form-label">
                        Number of Guests <span class="required">*</span>
                    </label>
                    <select class="form-select <?= isset($errors['guests_count']) ? 'is-invalid' : '' ?>" 
                            id="guests_count" name="guests_count" onchange="updateAvailableRooms()" required>
                        <option value="">Select number of guests</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>" <?= old('guests_count') == $i ? 'selected' : '' ?>><?= $i ?> Guest<?= $i > 1 ? 's' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                    <?php if (isset($errors['guests_count'])): ?>
                        <div class="invalid-feedback"><?= $errors['guests_count'] ?></div>
                    <?php endif; ?>
                </div>

                <h3 style="margin: 2rem 0 1.5rem; color: var(--dark-gray);">
                    <i class="fas fa-bed"></i> Room Selection
                </h3>

                <div class="form-group">
                    <label class="form-label">
                        Select Room <span class="required">*</span>
                    </label>
                    <div id="roomsLoading" style="display: none; text-align: center; padding: 2rem; color: var(--text-gray);">
                        <i class="fas fa-spinner fa-spin"></i> Loading available rooms...
                    </div>
                    <div class="room-selection">
                        <div class="room-grid" id="roomGrid">
                            <?php if (!empty($availableRooms)): ?>
                                <?php foreach ($availableRooms as $room): ?>
                                    <div class="room-option" onclick="selectRoom(<?= $room['room_id'] ?>, <?= $room['base_price'] ?>)">
                                        <div class="room-number">Room <?= esc($room['room_number']) ?></div>
                                        <div class="room-type"><?= esc($room['type_name']) ?> (<?= $room['capacity'] ?> guests)</div>
                                        <div class="room-price">$<?= number_format($room['base_price'], 2) ?>/night</div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="grid-column: 1 / -1; text-align: center; color: var(--text-gray); padding: 2rem;">
                                    <i class="fas fa-info-circle"></i>
                                    Please select check-in/out dates and number of guests to see available rooms.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="hidden" id="room_id" name="room_id" value="<?= old('room_id') ?>">
                    <input type="hidden" id="room_price" name="room_price" value="<?= old('room_price') ?>">
                    <?php if (isset($errors['room_id'])): ?>
                        <div class="invalid-feedback"><?= $errors['room_id'] ?></div>
                    <?php endif; ?>
                </div>

                <div id="priceCalculation" style="display: none;">
                    <h3 style="margin: 2rem 0 1.5rem; color: var(--dark-gray);">
                        <i class="fas fa-calculator"></i> Price Calculation
                    </h3>
                    <div class="price-calculation">
                        <div class="price-row">
                            <span>Room Rate (per night):</span>
                            <span id="roomRate">$0.00</span>
                        </div>
                        <div class="price-row">
                            <span>Number of Nights:</span>
                            <span id="numberOfNights">0</span>
                        </div>
                        <div class="price-row price-total">
                            <span>Total Amount:</span>
                            <span id="totalAmount">$0.00</span>
                        </div>
                    </div>
                    <input type="hidden" id="total_price" name="total_price" value="<?= old('total_price') ?>">
                </div>

                <div class="form-actions">
                    <a href="<?= base_url('staff/bookings') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="fas fa-save"></i>
                        Create Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedRoomId = null;
        let selectedRoomPrice = 0;

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        function closeSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        function updateAvailableRooms() {
            const checkIn = document.getElementById('check_in_date').value;
            const checkOut = document.getElementById('check_out_date').value;
            const guests = document.getElementById('guests_count').value;

            if (!checkIn || !checkOut || !guests) {
                return;
            }

            const roomGrid = document.getElementById('roomGrid');
            const roomsLoading = document.getElementById('roomsLoading');
            
            roomsLoading.style.display = 'block';
            roomGrid.innerHTML = '';

            fetch('<?= base_url('staff/bookings/getAvailableRooms') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `check_in=${checkIn}&check_out=${checkOut}&guests=${guests}`
            })
            .then(response => response.json())
            .then(data => {
                roomsLoading.style.display = 'none';
                
                if (data.success && data.rooms.length > 0) {
                    roomGrid.innerHTML = data.rooms.map(room => `
                        <div class="room-option" onclick="selectRoom(${room.room_id}, ${room.base_price})">
                            <div class="room-number">Room ${room.room_number}</div>
                            <div class="room-type">${room.type_name} (${room.capacity} guests)</div>
                            <div class="room-price">$${parseFloat(room.base_price).toFixed(2)}/night</div>
                        </div>
                    `).join('');
                } else {
                    roomGrid.innerHTML = `
                        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-gray); padding: 2rem;">
                            <i class="fas fa-exclamation-circle"></i>
                            No rooms available for the selected dates and guest count.
                        </div>
                    `;
                }
                
                // Reset room selection
                selectedRoomId = null;
                selectedRoomPrice = 0;
                document.getElementById('room_id').value = '';
                document.getElementById('room_price').value = '';
                document.getElementById('priceCalculation').style.display = 'none';
                updateSubmitButton();
            })
            .catch(error => {
                roomsLoading.style.display = 'none';
                roomGrid.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; color: var(--danger-color); padding: 2rem;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Error loading rooms. Please try again.
                    </div>
                `;
            });
        }

        function selectRoom(roomId, roomPrice) {
            // Remove previous selection
            document.querySelectorAll('.room-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Select new room
            event.currentTarget.classList.add('selected');
            selectedRoomId = roomId;
            selectedRoomPrice = parseFloat(roomPrice);

            document.getElementById('room_id').value = roomId;
            document.getElementById('room_price').value = roomPrice;

            updatePriceCalculation();
            updateSubmitButton();
        }

        function updatePriceCalculation() {
            const checkIn = document.getElementById('check_in_date').value;
            const checkOut = document.getElementById('check_out_date').value;

            if (!checkIn || !checkOut || !selectedRoomPrice) {
                return;
            }

            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            const totalPrice = nights * selectedRoomPrice;

            document.getElementById('roomRate').textContent = `$${selectedRoomPrice.toFixed(2)}`;
            document.getElementById('numberOfNights').textContent = nights;
            document.getElementById('totalAmount').textContent = `$${totalPrice.toFixed(2)}`;
            document.getElementById('total_price').value = totalPrice.toFixed(2);
            document.getElementById('priceCalculation').style.display = 'block';
        }

        function updateSubmitButton() {
            const submitBtn = document.getElementById('submitBtn');
            const requiredFields = ['guest_name', 'guest_phone', 'check_in_date', 'check_out_date', 'guests_count'];
            
            let allFilled = requiredFields.every(field => {
                return document.getElementById(field).value.trim() !== '';
            });

            allFilled = allFilled && selectedRoomId;

            submitBtn.disabled = !allFilled;
        }

        // Add event listeners for form validation
        document.addEventListener('DOMContentLoaded', function() {
            const requiredFields = ['guest_name', 'guest_phone', 'check_in_date', 'check_out_date', 'guests_count'];
            
            requiredFields.forEach(field => {
                document.getElementById(field).addEventListener('input', updateSubmitButton);
                document.getElementById(field).addEventListener('change', updateSubmitButton);
            });

            // Set minimum date for check-out
            document.getElementById('check_in_date').addEventListener('change', function() {
                const checkInDate = this.value;
                const checkOutInput = document.getElementById('check_out_date');
                
                if (checkInDate) {
                    const nextDay = new Date(checkInDate);
                    nextDay.setDate(nextDay.getDate() + 1);
                    checkOutInput.min = nextDay.toISOString().split('T')[0];
                    
                    if (checkOutInput.value && checkOutInput.value <= checkInDate) {
                        checkOutInput.value = nextDay.toISOString().split('T')[0];
                    }
                }
                
                updatePriceCalculation();
            });

            document.getElementById('check_out_date').addEventListener('change', updatePriceCalculation);
        });
    </script>
</body>
</html>