<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details - Hotel Management System</title>
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

        .btn-success {
            background: var(--success-color);
            color: var(--white);
        }

        .btn-warning {
            background: var(--warning-color);
            color: var(--dark-gray);
        }

        .btn-danger {
            background: var(--danger-color);
            color: var(--white);
        }

        .btn-info {
            background: var(--info-color);
            color: var(--white);
        }

        .detail-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            background: var(--primary-color);
            color: var(--white);
            padding: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .detail-section {
            margin-bottom: 2rem;
        }

        .detail-section h4 {
            color: var(--dark-gray);
            margin-bottom: 1rem;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .detail-value {
            font-weight: 600;
            color: var(--dark-gray);
            text-align: right;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .badge-pending {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .badge-confirmed {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .badge-cancelled {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .badge-completed {
            background: rgba(23, 162, 184, 0.1);
            color: var(--info-color);
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

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .code {
            font-family: 'Courier New', monospace;
            background: var(--light-gray);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .detail-value {
                text-align: left;
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
                Reservation Details
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

        <!-- Reservation Overview -->
        <div class="detail-card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i>
                Reservation Overview
            </div>
            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail-section">
                        <h4><i class="fas fa-hashtag"></i> Reservation Information</h4>
                        <div class="detail-item">
                            <span class="detail-label">Reservation ID</span>
                            <span class="detail-value code">#<?= $reservation['reservation_id'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Booking Ticket</span>
                            <span class="detail-value code"><?= esc($reservation['booking_ticket_no']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value">
                                <?php
                                $status = $reservation['status'] ?? 'pending';
                                $statusDisplay = match($status) {
                                    'pending' => 'Pending',
                                    'confirmed' => 'Confirmed',
                                    'cancelled' => 'Cancelled',
                                    'completed' => 'Completed',
                                    default => ucfirst($status)
                                };
                                ?>
                                <span class="badge badge-<?= $status ?>"><?= $statusDisplay ?></span>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Created Date</span>
                            <span class="detail-value"><?= date('M d, Y g:i A', strtotime($reservation['created_at'])) ?></span>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4><i class="fas fa-user"></i> Guest Information</h4>
                        <div class="detail-item">
                            <span class="detail-label">Guest Name</span>
                            <span class="detail-value"><?= esc($reservation['booked_by_name']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone Number</span>
                            <span class="detail-value"><?= esc($reservation['booked_by_phone']) ?></span>
                        </div>
                        <?php if (!empty($reservation['booked_by_email'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value"><?= esc($reservation['booked_by_email']) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="detail-item">
                            <span class="detail-label">Number of Guests</span>
                            <span class="detail-value"><?= $reservation['guests_count'] ?? 1 ?> Guest(s)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hotel & Room Details -->
        <div class="detail-card">
            <div class="card-header">
                <i class="fas fa-hotel"></i>
                Hotel & Room Details
            </div>
            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail-section">
                        <h4><i class="fas fa-building"></i> Hotel Information</h4>
                        <div class="detail-item">
                            <span class="detail-label">Hotel Name</span>
                            <span class="detail-value"><?= esc($reservation['hotel_name']) ?></span>
                        </div>
                        <?php if (!empty($reservation['hotel_address'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Address</span>
                            <span class="detail-value"><?= esc($reservation['hotel_address']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($reservation['hotel_phone'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Hotel Phone</span>
                            <span class="detail-value"><?= esc($reservation['hotel_phone']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="detail-section">
                        <h4><i class="fas fa-door-open"></i> Room Information</h4>
                        <div class="detail-item">
                            <span class="detail-label">Room Number</span>
                            <span class="detail-value code">Room <?= esc($reservation['room_number']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Room Type</span>
                            <span class="detail-value"><?= esc($reservation['type_name']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Room Capacity</span>
                            <span class="detail-value"><?= $reservation['capacity'] ?? 'N/A' ?> Person(s)</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Base Price</span>
                            <span class="detail-value">$<?= number_format($reservation['base_price'] ?? 0, 2) ?>/night</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservation Dates & Payment -->
        <div class="detail-card">
            <div class="card-header">
                <i class="fas fa-calendar-alt"></i>
                Dates & Payment Information
            </div>
            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail-section">
                        <h4><i class="fas fa-calendar-check"></i> Stay Dates</h4>
                        <div class="detail-item">
                            <span class="detail-label">Check-in Date</span>
                            <span class="detail-value"><?= date('M d, Y', strtotime($reservation['check_in_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Check-out Date</span>
                            <span class="detail-value"><?= date('M d, Y', strtotime($reservation['check_out_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Duration</span>
                            <span class="detail-value">
                                <?php
                                $checkin = new DateTime($reservation['check_in_date']);
                                $checkout = new DateTime($reservation['check_out_date']);
                                $nights = $checkin->diff($checkout)->days;
                                ?>
                                <?= $nights ?> Night(s)
                            </span>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4><i class="fas fa-dollar-sign"></i> Payment Details</h4>
                        <div class="detail-item">
                            <span class="detail-label">Total Amount</span>
                            <span class="detail-value price">$<?= number_format($reservation['total_price'], 2) ?></span>
                        </div>
                        <?php if (!empty($reservation['base_price']) && !empty($nights)): ?>
                        <div class="detail-item">
                            <span class="detail-label">Rate Calculation</span>
                            <span class="detail-value">$<?= number_format($reservation['base_price'], 2) ?> × <?= $nights ?> nights</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Information -->
        <?php if (!empty($reservation['assigned_staff_name'])): ?>
        <div class="detail-card">
            <div class="card-header">
                <i class="fas fa-user-tie"></i>
                Assigned Staff
            </div>
            <div class="card-body">
                <div class="detail-section">
                    <div class="detail-item">
                        <span class="detail-label">Staff Name</span>
                        <span class="detail-value"><?= esc($reservation['assigned_staff_name']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Role</span>
                        <span class="detail-value"><?= esc($reservation['assigned_staff_role']) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="detail-card">
            <div class="card-body">
                <div class="action-buttons">
                    <?php
                    $status = $reservation['status'] ?? 'pending';
                    ?>
                    
                    <?php if ($status == 'pending'): ?>
                        <a href="<?= base_url('staff/reservations/confirm/' . $reservation['reservation_id']) ?>" 
                           class="btn btn-success">
                            <i class="fas fa-check"></i>
                            Confirm Reservation
                        </a>
                    <?php endif; ?>

                    <?php if ($status == 'confirmed'): ?>
                        <a href="<?= base_url('staff/reservations/complete/' . $reservation['reservation_id']) ?>" 
                           class="btn btn-warning">
                            <i class="fas fa-flag-checkered"></i>
                            Complete Reservation
                        </a>
                    <?php endif; ?>

                    <?php if (in_array($status, ['pending', 'confirmed'])): ?>
                        <a href="<?= base_url('staff/reservations/cancel/' . $reservation['reservation_id']) ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Are you sure you want to cancel this reservation?')">
                            <i class="fas fa-times"></i>
                            Cancel Reservation
                        </a>
                    <?php endif; ?>

                    <?php if (in_array($status, ['cancelled', 'pending'])): ?>
                        <a href="<?= base_url('staff/reservations/delete/' . $reservation['reservation_id']) ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Are you sure you want to permanently delete this reservation? This action cannot be undone.')">
                            <i class="fas fa-trash"></i>
                            Delete Reservation
                        </a>
                    <?php endif; ?>

                    <a href="<?= base_url('staff/reservations') ?>" class="btn btn-secondary">
                        <i class="fas fa-list"></i>
                        Back to All Reservations
                    </a>
                </div>
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
    </script>
</body>
</html>