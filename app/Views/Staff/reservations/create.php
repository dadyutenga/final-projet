<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Reservation - Hotel Management System</title>
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

            .main-content {
                margin-left: 0;
                padding-top: 4rem;
            }
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
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
        }

        .btn-secondary {
            background: var(--text-gray);
            color: var(--white);
        }

        .form-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .form-header {
            background: var(--primary-color);
            color: var(--white);
            padding: 1.5rem;
            font-weight: 600;
        }

        .form-body {
            padding: 2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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

        .form-control:disabled {
            background: var(--light-gray);
            color: var(--text-gray);
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

        .booking-details {
            background: var(--light-gray);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .booking-details h4 {
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .booking-details p {
            margin: 0.25rem 0;
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
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
                <i class="fas fa-bed"></i>
                Create New Reservation
            </h2>
            <a href="<?= base_url('staff/reservations') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Reservations
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

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <ul style="margin: 0; padding-left: 1rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="form-header">
                <i class="fas fa-plus-circle"></i>
                Create Reservation from Booking
            </div>
            <div class="form-body">
                <form action="<?= base_url('staff/reservations/store') ?>" method="POST" id="reservationForm">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="booking_id" class="form-label">Select Confirmed Booking *</label>
                        <select class="form-select" id="booking_id" name="booking_id" required onchange="loadBookingDetails()">
                            <option value="">Choose a booking...</option>
                            <?php foreach ($availableBookings as $booking): ?>
                                <option value="<?= $booking['history_id'] ?>" 
                                        data-guest-name="<?= esc($booking['person_full_name']) ?>"
                                        data-guest-phone="<?= esc($booking['person_phone']) ?>"
                                        data-hotel-name="<?= esc($booking['hotel_name']) ?>"
                                        data-room-number="<?= esc($booking['room_number']) ?>"
                                        data-room-type="<?= esc($booking['type_name']) ?>"
                                        data-check-in="<?= $booking['check_in_date'] ?>"
                                        data-check-out="<?= $booking['check_out_date'] ?>"
                                        data-total-price="<?= $booking['total_price'] ?>"
                                        data-ticket-no="<?= esc($booking['booking_ticket_no']) ?>">
                                    <?= esc($booking['booking_ticket_no']) ?> - <?= esc($booking['person_full_name']) ?> 
                                    (Room <?= esc($booking['room_number']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="booking-details" class="booking-details" style="display: none;">
                        <h4>Booking Details</h4>
                        <div class="form-row">
                            <div>
                                <p><strong>Ticket No:</strong> <span id="ticket-no">-</span></p>
                                <p><strong>Guest:</strong> <span id="guest-name">-</span></p>
                                <p><strong>Phone:</strong> <span id="guest-phone">-</span></p>
                            </div>
                            <div>
                                <p><strong>Hotel:</strong> <span id="hotel-name">-</span></p>
                                <p><strong>Room:</strong> <span id="room-info">-</span></p>
                                <p><strong>Total Price:</strong> $<span id="booking-price">0.00</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="check_in_date" class="form-label">Check-in Date *</label>
                            <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
                        </div>
                        <div class="form-group">
                            <label for="check_out_date" class="form-label">Check-out Date *</label>
                            <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="total_price" class="form-label">Total Price *</label>
                            <input type="number" step="0.01" class="form-control" id="total_price" name="total_price" required>
                        </div>
                        <div class="form-group">
                            <label for="assigned_staff_id" class="form-label">Assign Staff (Optional)</label>
                            <select class="form-select" id="assigned_staff_id" name="assigned_staff_id">
                                <option value="">Current Staff</option>
                                <?php foreach ($allStaff as $staff): ?>
                                    <option value="<?= $staff['staff_id'] ?>">
                                        <?= esc($staff['full_name']) ?> - <?= esc($staff['role']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Reservation Status *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed" selected>Confirmed</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <a href="<?= base_url('staff/reservations') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Create Reservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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

        function loadBookingDetails() {
            const select = document.getElementById('booking_id');
            const selectedOption = select.options[select.selectedIndex];
            const detailsDiv = document.getElementById('booking-details');

            if (selectedOption.value) {
                // Show booking details
                document.getElementById('ticket-no').textContent = selectedOption.dataset.ticketNo;
                document.getElementById('guest-name').textContent = selectedOption.dataset.guestName;
                document.getElementById('guest-phone').textContent = selectedOption.dataset.guestPhone;
                document.getElementById('hotel-name').textContent = selectedOption.dataset.hotelName;
                document.getElementById('room-info').textContent = 'Room ' + selectedOption.dataset.roomNumber + ' (' + selectedOption.dataset.roomType + ')';
                document.getElementById('booking-price').textContent = parseFloat(selectedOption.dataset.totalPrice).toFixed(2);

                // Pre-fill form fields
                document.getElementById('check_in_date').value = selectedOption.dataset.checkIn;
                document.getElementById('check_out_date').value = selectedOption.dataset.checkOut;
                document.getElementById('total_price').value = selectedOption.dataset.totalPrice;

                detailsDiv.style.display = 'block';
            } else {
                detailsDiv.style.display = 'none';
                // Clear form fields
                document.getElementById('check_in_date').value = '';
                document.getElementById('check_out_date').value = '';
                document.getElementById('total_price').value = '';
            }
        }

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('check_in_date').setAttribute('min', today);
        document.getElementById('check_out_date').setAttribute('min', today);

        // Ensure check-out date is after check-in date
        document.getElementById('check_in_date').addEventListener('change', function() {
            const checkInDate = this.value;
            const checkOutInput = document.getElementById('check_out_date');
            checkOutInput.setAttribute('min', checkInDate);
            
            if (checkOutInput.value && checkOutInput.value <= checkInDate) {
                checkOutInput.value = '';
            }
        });
    </script>
</body>
</html>