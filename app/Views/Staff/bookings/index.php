<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Hotel Management System</title>
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

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--white);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .sidebar-logo {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-subtitle {
            font-size: 0.8rem;
            color: var(--text-gray);
            margin-top: 0.25rem;
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

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
        }

        .btn-success {
            background: var(--success-color);
            color: var(--white);
        }

        .btn-success:hover {
            background: #218838;
            color: var(--white);
        }

        .btn-warning {
            background: var(--warning-color);
            color: var(--dark-gray);
        }

        .btn-info {
            background: var(--info-color);
            color: var(--white);
        }

        .btn-danger {
            background: var(--danger-color);
            color: var(--white);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-gray);
        }

        .pending { color: var(--warning-color); }
        .confirmed { color: var(--success-color); }
        .cancelled { color: var(--danger-color); }
        .completed { color: var(--info-color); }
        .checked_in { color: var(--primary-color); }

        .filters-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .filters-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            margin-bottom: 0;
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

        .bookings-table-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .table th {
            background: var(--light-gray);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark-gray);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--light-gray);
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

        .btn-group {
            display: flex;
            gap: 0.25rem;
        }

        .code {
            font-family: 'Courier New', monospace;
            background: var(--light-gray);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
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

        @media (max-width: 768px) {
            .filters-row {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-sm {
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
                <i class="fas fa-calendar-check"></i>
                Manage Bookings
            </h2>
            <a href="<?= base_url('staff/bookings/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Booking
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

        <!-- DEBUG: Add this temporarily before your table -->
        <div class="alert alert-info">
            <strong>Debug Info:</strong><br>
            Total bookings found: <?= count($bookings) ?><br>
            Hotel ID: <?= session()->get('staff_hotel_id') ?><br>
            Current filters: Status = "<?= $current_status ?: 'All' ?>", From = "<?= $date_from ?: 'None' ?>", To = "<?= $date_to ?: 'None' ?>"
        </div>

        <!-- Booking Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon pending"><i class="fas fa-clock"></i></div>
                <div class="stat-value pending"><?= $stats['pending']['count'] ?? 0 ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon confirmed"><i class="fas fa-check-circle"></i></div>
                <div class="stat-value confirmed"><?= $stats['confirmed']['count'] ?? 0 ?></div>
                <div class="stat-label">Confirmed</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon checked_in"><i class="fas fa-door-open"></i></div>
                <div class="stat-value checked_in"><?= $stats['checked_in']['count'] ?? 0 ?></div>
                <div class="stat-label">Checked In</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed"><i class="fas fa-flag-checkered"></i></div>
                <div class="stat-value completed"><?= $stats['completed']['count'] ?? 0 ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <form method="GET" action="<?= base_url('staff/bookings') ?>">
                <div class="filters-row">
                    <div class="form-group">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?= esc($search) ?>" placeholder="Search by name, phone, ticket...">
                    </div>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="pending" <?= $current_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="confirmed" <?= $current_status == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="checked_in" <?= $current_status == 'checked_in' ? 'selected' : '' ?>>Checked In</option>
                            <option value="completed" <?= $current_status == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $current_status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="<?= esc($date_from) ?>">
                    </div>
                    <div class="form-group">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="<?= esc($date_to) ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bookings-table-card">
            <?php if (!empty($bookings)): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ticket #</th>
                                <th>Guest Details</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td>
                                        <span class="code"><?= $booking['booking_ticket_no'] ?></span>
                                    </td>
                                    <td>
                                        <strong><?= esc($booking['person_full_name']) ?></strong><br>
                                        <small style="color: var(--text-gray);"><?= esc($booking['person_phone']) ?></small>
                                    </td>
                                    <td>
                                        Room <?= esc($booking['room_number']) ?><br>
                                        <small style="color: var(--text-gray);"><?= esc($booking['type_name']) ?></small>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($booking['check_in_date'])) ?></td>
                                    <td><?= date('M d, Y', strtotime($booking['check_out_date'])) ?></td>
                                    <td><strong>$<?= number_format($booking['total_price'], 2) ?></strong></td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('staff/bookings/view/' . $booking['history_id']) ?>" 
                                               class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($status == 'pending'): ?>
                                                <a href="<?= base_url('staff/bookings/confirm/' . $booking['history_id']) ?>" 
                                                   class="btn btn-success btn-sm" title="Confirm">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($status == 'confirmed'): ?>
                                                <a href="<?= base_url('staff/bookings/checkin/' . $booking['history_id']) ?>" 
                                                   class="btn btn-primary btn-sm" title="Check In">
                                                    <i class="fas fa-door-open"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($status == 'checked_in'): ?>
                                                <a href="<?= base_url('staff/bookings/complete/' . $booking['history_id']) ?>" 
                                                   class="btn btn-warning btn-sm" title="Complete">
                                                    <i class="fas fa-flag-checkered"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (in_array($status, ['pending', 'confirmed'])): ?>
                                                <a href="<?= base_url('staff/bookings/cancel/' . $booking['history_id']) ?>" 
                                                   class="btn btn-danger btn-sm" title="Cancel"
                                                   onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (in_array($status, ['pending', 'cancelled']) && $booking['check_in_date'] > date('Y-m-d')): ?>
                                                <a href="<?= base_url('staff/bookings/delete/' . $booking['history_id']) ?>" 
                                                   class="btn btn-danger btn-sm" title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this booking? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No bookings found</h3>
                    <p>There are no bookings matching your criteria.</p>
                </div>
            <?php endif; ?>
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