<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payment - Hotel Management System</title>
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

        .form-card {
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
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

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
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

        .reservation-details {
            background: var(--light-gray);
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
            display: none;
        }

        .reservation-details.show {
            display: block;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: var(--text-gray);
        }

        .detail-value {
            font-weight: 600;
            color: var(--dark-gray);
        }

        .btn-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-gray);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--border-color);
        }

        .loading {
            text-align: center;
            padding: 1rem;
            color: var(--text-gray);
        }

        .loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .btn-actions {
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
                <i class="fas fa-credit-card"></i>
                Process Payment
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

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <?php foreach ($errors as $error): ?>
                        <div><?= esc($error) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="card-header">
                <i class="fas fa-plus"></i>
                Process New Payment
            </div>
            <div class="card-body">
                <?php if (!empty($availableReservations)): ?>
                    <form action="<?= base_url('staff/payments/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="form-grid">
                            <div>
                                <div class="form-group">
                                    <label for="reservation_id" class="form-label">Select Reservation <span style="color: var(--danger-color);">*</span></label>
                                    <select class="form-select <?= isset($errors['reservation_id']) ? 'is-invalid' : '' ?>" 
                                            id="reservation_id" name="reservation_id" required onchange="loadReservationDetails()">
                                        <option value="">Choose a reservation...</option>
                                        <?php foreach ($availableReservations as $reservation): ?>
                                            <option value="<?= $reservation['reservation_id'] ?>" 
                                                    data-amount="<?= $reservation['total_price'] ?>"
                                                    data-guest="<?= esc($reservation['guest_name']) ?>"
                                                    data-phone="<?= esc($reservation['guest_phone']) ?>"
                                                    data-room="<?= esc($reservation['room_number']) ?>"
                                                    data-type="<?= esc($reservation['type_name']) ?>"
                                                    data-checkin="<?= $reservation['check_in_date'] ?>"
                                                    data-checkout="<?= $reservation['check_out_date'] ?>"
                                                    data-ticket="<?= esc($reservation['booking_ticket_no']) ?>"
                                                    <?= old('reservation_id') == $reservation['reservation_id'] ? 'selected' : '' ?>>
                                                #<?= $reservation['reservation_id'] ?> - <?= esc($reservation['guest_name']) ?> - Room <?= esc($reservation['room_number']) ?> - $<?= number_format($reservation['total_price'], 2) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['reservation_id'])): ?>
                                        <div class="invalid-feedback"><?= $errors['reservation_id'] ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="amount" class="form-label">Payment Amount <span style="color: var(--danger-color);">*</span></label>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control <?= isset($errors['amount']) ? 'is-invalid' : '' ?>" 
                                           id="amount" name="amount" 
                                           value="<?= old('amount') ?>" 
                                           placeholder="Enter payment amount" required>
                                    <?php if (isset($errors['amount'])): ?>
                                        <div class="invalid-feedback"><?= $errors['amount'] ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="payment_method" class="form-label">Payment Method <span style="color: var(--danger-color);">*</span></label>
                                    <select class="form-select <?= isset($errors['payment_method']) ? 'is-invalid' : '' ?>" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">Select payment method...</option>
                                        <option value="credit_card" <?= old('payment_method') == 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                                        <option value="debit_card" <?= old('payment_method') == 'debit_card' ? 'selected' : '' ?>>Debit Card</option>
                                        <option value="cash" <?= old('payment_method') == 'cash' ? 'selected' : '' ?>>Cash</option>
                                        <option value="online" <?= old('payment_method') == 'online' ? 'selected' : '' ?>>Online Payment</option>
                                    </select>
                                    <?php if (isset($errors['payment_method'])): ?>
                                        <div class="invalid-feedback"><?= $errors['payment_method'] ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="payment_status" class="form-label">Payment Status</label>
                                    <select class="form-select" id="payment_status" name="payment_status">
                                        <option value="completed" <?= old('payment_status') == 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="pending" <?= old('payment_status') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="failed" <?= old('payment_status') == 'failed' ? 'selected' : '' ?>>Failed</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <!-- Reservation Details Panel -->
                                <div id="reservationDetails" class="reservation-details">
                                    <h4 style="margin-bottom: 1rem; color: var(--dark-gray);">
                                        <i class="fas fa-info-circle"></i>
                                        Reservation Details
                                    </h4>
                                    <div id="reservationContent">
                                        <div class="loading">
                                            <i class="fas fa-spinner"></i>
                                            Select a reservation to view details
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="btn-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-credit-card"></i>
                                Process Payment
                            </button>
                            <a href="<?= base_url('staff/payments') ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-bed"></i>
                        <h3>No Available Reservations</h3>
                        <p>There are no confirmed reservations available for payment processing.</p>
                        <p>Reservations must be confirmed and not have completed payments.</p>
                        <a href="<?= base_url('staff/reservations') ?>" class="btn btn-primary" style="margin-top: 1rem;">
                            <i class="fas fa-bed"></i>
                            Manage Reservations
                        </a>
                    </div>
                <?php endif; ?>
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

        function loadReservationDetails() {
            const select = document.getElementById('reservation_id');
            const detailsPanel = document.getElementById('reservationDetails');
            const content = document.getElementById('reservationContent');
            const amountInput = document.getElementById('amount');
            
            if (select.value) {
                const option = select.selectedOptions[0];
                const amount = option.getAttribute('data-amount');
                const guest = option.getAttribute('data-guest');
                const phone = option.getAttribute('data-phone');
                const room = option.getAttribute('data-room');
                const type = option.getAttribute('data-type');
                const checkin = option.getAttribute('data-checkin');
                const checkout = option.getAttribute('data-checkout');
                const ticket = option.getAttribute('data-ticket');
                
                // Auto-fill amount
                amountInput.value = amount;
                
                // Show reservation details
                content.innerHTML = `
                    <div class="detail-item">
                        <span class="detail-label">Booking Ticket</span>
                        <span class="detail-value">${ticket}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Guest Name</span>
                        <span class="detail-value">${guest}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Phone</span>
                        <span class="detail-value">${phone}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Room</span>
                        <span class="detail-value">Room ${room} (${type})</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Check-in</span>
                        <span class="detail-value">${new Date(checkin).toLocaleDateString()}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Check-out</span>
                        <span class="detail-value">${new Date(checkout).toLocaleDateString()}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Total Amount</span>
                        <span class="detail-value" style="color: var(--primary-color); font-size: 1.1rem;">$${parseFloat(amount).toFixed(2)}</span>
                    </div>
                `;
                
                detailsPanel.classList.add('show');
            } else {
                detailsPanel.classList.remove('show');
                amountInput.value = '';
            }
        }

        // Load reservation details on page load if there's a selected value
        document.addEventListener('DOMContentLoaded', function() {
            const reservationSelect = document.getElementById('reservation_id');
            if (reservationSelect.value) {
                loadReservationDetails();
            }
        });
    </script>
</body>
</html>