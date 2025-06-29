<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Hotel Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
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
            color: var(--dark-gray);
            font-size: 1.8rem;
            font-weight: 600;
        }

        .page-header h2 i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: var(--white);
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 968px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .card-header h6 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .card-body {
            padding: 1.5rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
            color: var(--dark-gray);
            font-size: 1.2rem;
            font-weight: 600;
        }

        .section-title i {
            color: var(--primary-color);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-gray);
        }

        .required {
            color: var(--danger-color);
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(50, 205, 50, 0.1);
        }

        .text-danger {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .room-info-list {
            list-style: none;
            padding: 0;
        }

        .room-info-item {
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .room-info-item:hover {
            border-color: var(--primary-color);
            background: rgba(50, 205, 50, 0.05);
        }

        .room-info-item strong {
            color: var(--dark-gray);
        }

        .room-info-item small {
            color: var(--text-gray);
        }

        .price-success {
            color: var(--success-color);
            font-weight: 600;
        }

        .hotel-info p {
            margin-bottom: 0.5rem;
            color: var(--text-gray);
        }

        .hotel-info i {
            color: var(--primary-color);
            width: 20px;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .btn-group {
                width: 100%;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <?= $this->include('staff/shared/sidebar') ?>

    <div class="main-content">
        <div class="page-header">
            <h2>
                <i class="fas fa-plus-circle"></i> Create New Booking
            </h2>
            <a href="<?= base_url('staff/bookings') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Bookings
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
                <i class="fas fa-exclamation-circle"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <div>
                <!-- Booking Form -->
                <div class="card">
                    <div class="card-header">
                        <h6><i class="fas fa-edit"></i> Booking Information</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= base_url('staff/bookings/store') ?>" id="bookingForm">
                            <?= csrf_field() ?>
                            
                            <!-- Guest Information -->
                            <div class="section-title">
                                <i class="fas fa-user"></i> Guest Information
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="guest_name">Guest Name <span class="required">*</span></label>
                                    <input type="text" name="guest_name" id="guest_name" class="form-control" 
                                           value="<?= old('guest_name') ?>" required>
                                    <?php if (isset($errors['guest_name'])): ?>
                                        <div class="text-danger"><?= $errors['guest_name'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="guest_phone">Phone Number <span class="required">*</span></label>
                                    <input type="tel" name="guest_phone" id="guest_phone" class="form-control" 
                                           value="<?= old('guest_phone') ?>" required>
                                    <?php if (isset($errors['guest_phone'])): ?>
                                        <div class="text-danger"><?= $errors['guest_phone'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="guest_email">Email Address</label>
                                    <input type="email" name="guest_email" id="guest_email" class="form-control" 
                                           value="<?= old('guest_email') ?>">
                                    <?php if (isset($errors['guest_email'])): ?>
                                        <div class="text-danger"><?= $errors['guest_email'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="guests_count">Number of Guests <span class="required">*</span></label>
                                    <select name="guests_count" id="guests_count" class="form-select" required>
                                        <option value="">Select guests count</option>
                                        <?php for ($i = 1; $i <= 10; $i++): ?>
                                            <option value="<?= $i ?>" <?= old('guests_count') == $i ? 'selected' : '' ?>><?= $i ?> Guest<?= $i > 1 ? 's' : '' ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <?php if (isset($errors['guests_count'])): ?>
                                        <div class="text-danger"><?= $errors['guests_count'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Booking Details -->
                            <div class="section-title">
                                <i class="fas fa-calendar-alt"></i> Booking Details
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="check_in_date">Check-in Date <span class="required">*</span></label>
                                    <input type="date" name="check_in_date" id="check_in_date" class="form-control" 
                                           value="<?= old('check_in_date', date('Y-m-d')) ?>" min="<?= date('Y-m-d') ?>" required>
                                    <?php if (isset($errors['check_in_date'])): ?>
                                        <div class="text-danger"><?= $errors['check_in_date'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="check_out_date">Check-out Date <span class="required">*</span></label>
                                    <input type="date" name="check_out_date" id="check_out_date" class="form-control" 
                                           value="<?= old('check_out_date') ?>" required>
                                    <?php if (isset($errors['check_out_date'])): ?>
                                        <div class="text-danger"><?= $errors['check_out_date'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Room Selection -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="room_type_filter">Filter by Room Type</label>
                                    <select id="room_type_filter" class="form-select">
                                        <option value="">All Room Types</option>
                                        <?php foreach ($roomTypes as $type): ?>
                                            <option value="<?= $type['room_type_id'] ?>"><?= esc($type['type_name']) ?> - $<?= number_format($type['base_price'], 2) ?>/night</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="room_id">Select Room <span class="required">*</span></label>
                                    <select name="room_id" id="room_id" class="form-select" required>
                                        <option value="">Select check-in/out dates first</option>
                                    </select>
                                    <?php if (isset($errors['room_id'])): ?>
                                        <div class="text-danger"><?= $errors['room_id'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Price Information -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="nights_count">Number of Nights</label>
                                    <input type="number" id="nights_count" class="form-control" readonly style="background: var(--light-gray);">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="total_price">Total Price <span class="required">*</span></label>
                                    <input type="number" name="total_price" id="total_price" class="form-control" 
                                           step="0.01" min="0" value="<?= old('total_price') ?>" required>
                                    <?php if (isset($errors['total_price'])): ?>
                                        <div class="text-danger"><?= $errors['total_price'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="btn-group" style="margin-top: 2rem;">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Create Booking
                                </button>
                                <a href="<?= base_url('staff/bookings') ?>" class="btn btn-secondary btn-lg">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div>
                <!-- Available Rooms Info -->
                <div class="card">
                    <div class="card-header">
                        <h6><i class="fas fa-door-open"></i> Available Rooms</h6>
                    </div>
                    <div class="card-body">
                        <div id="roomsInfo">
                            <p style="color: var(--text-gray);">Select check-in and check-out dates to see available rooms.</p>
                        </div>
                    </div>
                </div>

                <!-- Hotel Information -->
                <div class="card">
                    <div class="card-header">
                        <h6><i class="fas fa-hotel"></i> Hotel Information</h6>
                    </div>
                    <div class="card-body hotel-info">
                        <h5 style="color: var(--dark-gray); margin-bottom: 1rem;"><?= esc($hotel['name']) ?></h5>
                        <p><i class="fas fa-map-marker-alt"></i> <?= esc($hotel['address']) ?></p>
                        <p><i class="fas fa-city"></i> <?= esc($hotel['city']) ?>, <?= esc($hotel['country']) ?></p>
                        <p><i class="fas fa-phone"></i> <?= esc($hotel['phone']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }

        $(document).ready(function() {
            // Update checkout date minimum when checkin date changes
            $('#check_in_date').on('change', function() {
                const checkInDate = new Date(this.value);
                checkInDate.setDate(checkInDate.getDate() + 1);
                const minCheckOut = checkInDate.toISOString().split('T')[0];
                $('#check_out_date').attr('min', minCheckOut);
                
                if ($('#check_out_date').val() && $('#check_out_date').val() <= this.value) {
                    $('#check_out_date').val(minCheckOut);
                }
                
                loadAvailableRooms();
            });

            $('#check_out_date').on('change', loadAvailableRooms);
            $('#room_type_filter').on('change', loadAvailableRooms);

            // Calculate nights and total price when room is selected
            $('#room_id').on('change', function() {
                calculatePrice();
            });

            // Calculate nights when dates change
            $('#check_in_date, #check_out_date').on('change', function() {
                calculateNights();
                calculatePrice();
            });

            function loadAvailableRooms() {
                const checkIn = $('#check_in_date').val();
                const checkOut = $('#check_out_date').val();
                const roomTypeId = $('#room_type_filter').val();

                if (!checkIn || !checkOut) {
                    $('#room_id').html('<option value="">Select check-in/out dates first</option>');
                    $('#roomsInfo').html('<p style="color: var(--text-gray);">Select check-in and check-out dates to see available rooms.</p>');
                    return;
                }

                if (checkOut <= checkIn) {
                    $('#room_id').html('<option value="">Check-out must be after check-in</option>');
                    return;
                }

                // Show loading
                $('#room_id').html('<option value="">Loading...</option>');
                $('#roomsInfo').html('<p style="color: var(--text-gray);"><i class="fas fa-spinner fa-spin"></i> Loading available rooms...</p>');

                $.ajax({
                    url: '<?= base_url('staff/bookings/get-available-rooms') ?>',
                    method: 'POST',
                    data: {
                        check_in: checkIn,
                        check_out: checkOut,
                        room_type_id: roomTypeId,
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        if (response.success && response.rooms.length > 0) {
                            let options = '<option value="">Select a room</option>';
                            let roomsInfoHtml = '<div class="room-info-list">';
                            
                            response.rooms.forEach(function(room) {
                                options += `<option value="${room.room_id}" data-price="${room.base_price}">
                                    Room ${room.room_number} - ${room.type_name} ($${parseFloat(room.base_price).toFixed(2)}/night)
                                </option>`;
                                
                                roomsInfoHtml += `<div class="room-info-item">
                                    <strong>Room ${room.room_number}</strong><br>
                                    <small>${room.type_name} - Capacity: ${room.capacity}</small><br>
                                    <small class="price-success">$${parseFloat(room.base_price).toFixed(2)}/night</small>
                                </div>`;
                            });
                            
                            roomsInfoHtml += '</div>';
                            
                            $('#room_id').html(options);
                            $('#roomsInfo').html(roomsInfoHtml);
                        } else {
                            $('#room_id').html('<option value="">No rooms available for selected dates</option>');
                            $('#roomsInfo').html('<p style="color: var(--warning-color);">No rooms available for the selected dates and criteria.</p>');
                        }
                    },
                    error: function() {
                        $('#room_id').html('<option value="">Error loading rooms</option>');
                        $('#roomsInfo').html('<p style="color: var(--danger-color);">Error loading available rooms. Please try again.</p>');
                    }
                });
            }

            function calculateNights() {
                const checkIn = $('#check_in_date').val();
                const checkOut = $('#check_out_date').val();

                if (checkIn && checkOut && checkOut > checkIn) {
                    const checkInDate = new Date(checkIn);
                    const checkOutDate = new Date(checkOut);
                    const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
                    const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    $('#nights_count').val(nights);
                } else {
                    $('#nights_count').val('');
                }
            }

            function calculatePrice() {
                const nights = parseInt($('#nights_count').val()) || 0;
                const selectedRoom = $('#room_id option:selected');
                const roomPrice = parseFloat(selectedRoom.data('price')) || 0;

                if (nights > 0 && roomPrice > 0) {
                    const totalPrice = nights * roomPrice;
                    $('#total_price').val(totalPrice.toFixed(2));
                } else {
                    $('#total_price').val('');
                }
            }

            // Initialize if dates are already set
            if ($('#check_in_date').val() && $('#check_out_date').val()) {
                calculateNights();
                loadAvailableRooms();
            }
        });
    </script>
</body>
</html>