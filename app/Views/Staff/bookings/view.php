<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - Hotel Management System</title>
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

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .details-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .card-header h3 {
            color: var(--dark-gray);
            font-size: 1.1rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--text-gray);
            font-weight: 500;
        }

        .detail-value {
            color: var(--dark-gray);
            font-weight: 500;
            text-align: right;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
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

        .badge-checked_in {
            background: rgba(50, 205, 50, 0.1);
            color: var(--primary-color);
        }

        .actions-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
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

        .code {
            font-family: 'Courier New', monospace;
            background: var(--light-gray);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .price-highlight {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
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
                <i class="fas fa-file-alt"></i>
                Booking Details
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

        <div class="details-grid">
            <!-- Booking Information -->
            <div class="details-card">
                <div class="card-header">
                    <i class="fas fa-ticket-alt"></i>
                    <h3>Booking Information</h3>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Ticket Number</span>
                    <span class="detail-value">
                        <span class="code"><?= esc($booking['booking_ticket_no']) ?></span>
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <?php
                        $status = $booking['status'] ?? 'confirmed';
                        $statusDisplay = match($status) {
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'checked_in' => 'Checked In',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                            default => ucfirst($status)
                        };
                        ?>
                        <span class="badge badge-<?= $status ?>"><?= $statusDisplay ?></span>
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Booking Date</span>
                    <span class="detail-value"><?= date('M d, Y g:i A', strtotime($booking['created_at'])) ?></span>
                </div>

                <?php if (!empty($booking['checked_in_date'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Check-in Time</span>
                    <span class="detail-value"><?= date('M d, Y g:i A', strtotime($booking['checked_in_date'])) ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($booking['checked_out_date'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Check-out Time</span>
                    <span class="detail-value"><?= date('M d, Y g:i A', strtotime($booking['checked_out_date'])) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Guest Information -->
            <div class="details-card">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <h3>Guest Information</h3>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Full Name</span>
                    <span class="detail-value"><?= esc($booking['person_full_name']) ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Phone Number</span>
                    <span class="detail-value"><?= esc($booking['person_phone']) ?></span>
                </div>

                <?php if (!empty($booking['guest_email'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Email Address</span>
                    <span class="detail-value"><?= esc($booking['guest_email']) ?></span>
                </div>
                <?php endif; ?>

                <div class="detail-row">
                    <span class="detail-label">Number of Guests</span>
                    <span class="detail-value"><?= $booking['guests_count'] ?> Guest<?= $booking['guests_count'] > 1 ? 's' : '' ?></span>
                </div>
            </div>

            <!-- Room & Stay Information -->
            <div class="details-card">
                <div class="card-header">
                    <i class="fas fa-bed"></i>
                    <h3>Room & Stay Details</h3>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Hotel</span>
                    <span class="detail-value"><?= esc($booking['hotel_name'] ?? 'N/A') ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Room Number</span>
                    <span class="detail-value">Room <?= esc($booking['room_number'] ?? 'N/A') ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Room Type</span>
                    <span class="detail-value"><?= esc($booking['type_name'] ?? 'N/A') ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Check-in Date</span>
                    <span class="detail-value"><?= date('M d, Y', strtotime($booking['check_in_date'])) ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Check-out Date</span>
                    <span class="detail-value"><?= date('M d, Y', strtotime($booking['check_out_date'])) ?></span>
                </div>

                <?php
                $checkIn = new DateTime($booking['check_in_date']);
                $checkOut = new DateTime($booking['check_out_date']);
                $nights = $checkIn->diff($checkOut)->days;
                ?>
                <div class="detail-row">
                    <span class="detail-label">Duration</span>
                    <span class="detail-value"><?= $nights ?> Night<?= $nights > 1 ? 's' : '' ?></span>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="details-card">
                <div class="card-header">
                    <i class="fas fa-dollar-sign"></i>
                    <h3>Payment Information</h3>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Room Rate (per night)</span>
                    <span class="detail-value">$<?= number_format($booking['total_price'] / $nights, 2) ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Number of Nights</span>
                    <span class="detail-value"><?= $nights ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Total Amount</span>
                    <span class="detail-value price-highlight">$<?= number_format($booking['total_price'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions-card">
            <div class="card-header">
                <i class="fas fa-tools"></i>
                <h3>Actions</h3>
            </div>

            <div class="actions-grid">
                <?php $status = $booking['status'] ?? 'confirmed'; ?>
                
                <?php if ($status == 'pending'): ?>
                    <a href="<?= base_url('staff/bookings/confirm/' . $booking['history_id']) ?>" 
                       class="btn btn-success">
                        <i class="fas fa-check"></i>
                        Confirm Booking
                    </a>
                <?php endif; ?>

                <?php if ($status == 'confirmed'): ?>
                    <a href="<?= base_url('staff/bookings/checkin/' . $booking['history_id']) ?>" 
                       class="btn btn-primary">
                        <i class="fas fa-door-open"></i>
                        Check In Guest
                    </a>
                <?php endif; ?>

                <?php if ($status == 'checked_in'): ?>
                    <a href="<?= base_url('staff/bookings/complete/' . $booking['history_id']) ?>" 
                       class="btn btn-warning">
                        <i class="fas fa-flag-checkered"></i>
                        Complete Booking
                    </a>
                <?php endif; ?>

                <?php if (in_array($status, ['pending', 'confirmed'])): ?>
                    <a href="<?= base_url('staff/bookings/cancel/' . $booking['history_id']) ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to cancel this booking?')">
                        <i class="fas fa-times"></i>
                        Cancel Booking
                    </a>
                <?php endif; ?>

                <?php if (in_array($status, ['pending', 'cancelled']) && $booking['check_in_date'] > date('Y-m-d')): ?>
                    <a href="<?= base_url('staff/bookings/delete/' . $booking['history_id']) ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to delete this booking? This action cannot be undone.')">
                        <i class="fas fa-trash"></i>
                        Delete Booking
                    </a>
                <?php endif; ?>

                <button onclick="window.print()" class="btn btn-info">
                    <i class="fas fa-print"></i>
                    Print Details
                </button>
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