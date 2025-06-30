<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details - Hotel Management System</title>
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

        .badge-completed {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .badge-failed {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
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
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .payment-method-icon {
            margin-right: 0.5rem;
        }

        .status-timeline {
            margin-top: 1rem;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 0;
        }

        .timeline-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .timeline-icon.completed {
            background: var(--success-color);
            color: var(--white);
        }

        .timeline-icon.pending {
            background: var(--warning-color);
            color: var(--white);
        }

        .timeline-icon.failed {
            background: var(--danger-color);
            color: var(--white);
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
                <i class="fas fa-credit-card"></i>
                Payment Details
            </h2>
            <a href="<?= base_url('staff/payments') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Payments
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

        <!-- Payment Overview -->
        <div class="detail-card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i>
                Payment Overview
            </div>
            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail-section">
                        <h4><i class="fas fa-credit-card"></i> Payment Information</h4>
                        <div class="detail-item">
                            <span class="detail-label">Payment ID</span>
                            <span class="detail-value code">#<?= $payment['payment_id'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Amount</span>
                            <span class="detail-value price">$<?= number_format($payment['amount'], 2) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Payment Method</span>
                            <span class="detail-value">
                                <?php
                                $methodIcon = match($payment['payment_method']) {
                                    'credit_card' => 'fas fa-credit-card',
                                    'debit_card' => 'fas fa-credit-card',
                                    'cash' => 'fas fa-money-bill-wave',
                                    'online' => 'fas fa-globe',
                                    default => 'fas fa-credit-card'
                                };
                                $methodDisplay = match($payment['payment_method']) {
                                    'credit_card' => 'Credit Card',
                                    'debit_card' => 'Debit Card',
                                    'cash' => 'Cash',
                                    'online' => 'Online Payment',
                                    default => ucfirst($payment['payment_method'])
                                };
                                ?>
                                <i class="<?= $methodIcon ?> payment-method-icon"></i>
                                <?= $methodDisplay ?>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value">
                                <?php
                                $status = $payment['payment_status'] ?? 'pending';
                                $statusDisplay = match($status) {
                                    'pending' => 'Pending',
                                    'completed' => 'Completed',
                                    'failed' => 'Failed',
                                    default => ucfirst($status)
                                };
                                ?>
                                <span class="badge badge-<?= $status ?>"><?= $statusDisplay ?></span>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Payment Date</span>
                            <span class="detail-value"><?= date('M d, Y g:i A', strtotime($payment['payment_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Created Date</span>
                            <span class="detail-value"><?= date('M d, Y g:i A', strtotime($payment['created_at'])) ?></span>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4><i class="fas fa-timeline"></i> Payment Status</h4>
                        <div class="status-timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon completed">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <div>
                                    <strong>Payment Created</strong><br>
                                    <small><?= date('M d, Y g:i A', strtotime($payment['created_at'])) ?></small>
                                </div>
                            </div>
                            <?php if ($status == 'completed'): ?>
                                <div class="timeline-item">
                                    <div class="timeline-icon completed">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div>
                                        <strong>Payment Completed</strong><br>
                                        <small><?= date('M d, Y g:i A', strtotime($payment['payment_date'])) ?></small>
                                    </div>
                                </div>
                            <?php elseif ($status == 'failed'): ?>
                                <div class="timeline-item">
                                    <div class="timeline-icon failed">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div>
                                        <strong>Payment Failed</strong><br>
                                        <small><?= date('M d, Y g:i A', strtotime($payment['updated_at'])) ?></small>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="timeline-item">
                                    <div class="timeline-icon pending">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <strong>Payment Pending</strong><br>
                                        <small>Awaiting processing</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservation Details -->
        <div class="detail-card">
            <div class="card-header">
                <i class="fas fa-bed"></i>
                Related Reservation
            </div>
            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail-section">
                        <h4><i class="fas fa-hashtag"></i> Reservation Information</h4>
                        <div class="detail-item">
                            <span class="detail-label">Reservation ID</span>
                            <span class="detail-value">
                                <a href="<?= base_url('staff/reservations/view/' . $reservation['reservation_id']) ?>" 
                                   class="code" style="color: var(--primary-color); text-decoration: none;">
                                    #<?= $reservation['reservation_id'] ?>
                                </a>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Booking Ticket</span>
                            <span class="detail-value code"><?= esc($reservation['booking_ticket_no']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Guest Name</span>
                            <span class="detail-value"><?= esc($reservation['booked_by_name']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value"><?= esc($reservation['booked_by_phone']) ?></span>
                        </div>
                        <?php if (!empty($reservation['booked_by_email'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value"><?= esc($reservation['booked_by_email']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="detail-section">
                        <h4><i class="fas fa-hotel"></i> Hotel & Room Details</h4>
                        <div class="detail-item">
                            <span class="detail-label">Hotel</span>
                            <span class="detail-value"><?= esc($reservation['hotel_name']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Room</span>
                            <span class="detail-value">Room <?= esc($reservation['room_number']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Room Type</span>
                            <span class="detail-value"><?= esc($reservation['type_name']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Check-in Date</span>
                            <span class="detail-value"><?= date('M d, Y', strtotime($reservation['check_in_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Check-out Date</span>
                            <span class="detail-value"><?= date('M d, Y', strtotime($reservation['check_out_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Reservation Amount</span>
                            <span class="detail-value">$<?= number_format($reservation['total_price'], 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="detail-card">
            <div class="card-body">
                <div class="action-buttons">
                    <?php
                    $status = $payment['payment_status'] ?? 'pending';
                    ?>
                    
                    <?php if ($status == 'failed'): ?>
                        <a href="<?= base_url('staff/payments/retry/' . $payment['payment_id']) ?>" 
                           class="btn btn-warning">
                            <i class="fas fa-redo"></i>
                            Retry Payment
                        </a>
                    <?php endif; ?>

                    <?php if ($status != 'completed'): ?>
                        <form action="<?= base_url('staff/payments/update-status/' . $payment['payment_id']) ?>" 
                              method="POST" style="display: inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Are you sure you want to mark this payment as completed?')">
                                <i class="fas fa-check"></i>
                                Mark as Completed
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ($status == 'completed'): ?>
                        <form action="<?= base_url('staff/payments/update-status/' . $payment['payment_id']) ?>" 
                              method="POST" style="display: inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="pending">
                            <button type="submit" class="btn btn-warning"
                                    onclick="return confirm('Are you sure you want to mark this payment as pending?')">
                                <i class="fas fa-clock"></i>
                                Mark as Pending
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if (in_array($status, ['pending', 'failed'])): ?>
                        <a href="<?= base_url('staff/payments/delete/' . $payment['payment_id']) ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Are you sure you want to delete this payment? This action cannot be undone.')">
                            <i class="fas fa-trash"></i>
                            Delete Payment
                        </a>
                    <?php endif; ?>

                    <a href="<?= base_url('staff/reservations/view/' . $reservation['reservation_id']) ?>" 
                       class="btn btn-info">
                        <i class="fas fa-bed"></i>
                        View Reservation
                    </a>

                    <a href="<?= base_url('staff/payments') ?>" class="btn btn-secondary">
                        <i class="fas fa-list"></i>
                        Back to All Payments
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