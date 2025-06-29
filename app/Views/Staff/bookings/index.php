<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Hotel Management System</title>
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

        .btn-group {
            display: flex;
            gap: 0.5rem;
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

        .btn-info {
            background: var(--info-color);
            color: var(--white);
        }

        .btn-info:hover {
            background: #138496;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: var(--white);
        }

        .btn-success {
            background: var(--success-color);
            color: var(--white);
        }

        .btn-warning {
            background: var(--warning-color);
            color: var(--white);
        }

        .btn-danger {
            background: var(--danger-color);
            color: var(--white);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
        }

        .stat-icon.primary { background: var(--primary-color); }
        .stat-icon.success { background: var(--success-color); }
        .stat-icon.warning { background: var(--warning-color); }
        .stat-icon.info { background: var(--info-color); }

        .stat-content h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 0.25rem;
        }

        .stat-content p {
            color: var(--text-gray);
            font-size: 0.875rem;
            margin: 0;
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

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .table th {
            background: var(--light-gray);
            font-weight: 600;
            color: var(--dark-gray);
        }

        .table tbody tr:hover {
            background: rgba(50, 205, 50, 0.05);
        }

        .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .badge-success { background: var(--success-color); color: var(--white); }
        .badge-warning { background: var(--warning-color); color: var(--white); }
        .badge-danger { background: var(--danger-color); color: var(--white); }
        .badge-info { background: var(--info-color); color: var(--white); }

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

        .code {
            background: var(--light-gray);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .btn-group {
                width: 100%;
                justify-content: flex-start;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .table {
                font-size: 0.875rem;
            }

            .table th,
            .table td {
                padding: 0.5rem;
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
                <i class="fas fa-calendar-check"></i> Manage Bookings
            </h2>
            <div class="btn-group">
                <a href="<?= base_url('staff/bookings/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Booking
                </a>
                <a href="<?= base_url('staff/bookings/today-activity') ?>" class="btn btn-info">
                    <i class="fas fa-clock"></i> Today's Activity
                </a>
            </div>
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

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['total']['count'] ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['confirmed']['count'] ?></h3>
                    <p>Confirmed</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['pending']['count'] ?></h3>
                    <p>Pending</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>$<?= number_format($stats['total']['revenue'], 2) ?></h3>
                    <p>Revenue</p>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-filter"></i> Filters & Search</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="<?= base_url('staff/bookings') ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" <?= $current_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= $current_status == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="cancelled" <?= $current_status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                <option value="completed" <?= $current_status == 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="date_from">From Date</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="<?= $date_from ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="date_to">To Date</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="<?= $date_to ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="search">Search</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Guest name, phone, ticket..." value="<?= $search ?>">
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="<?= base_url('staff/bookings') ?>" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Search by Ticket -->
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-ticket-alt"></i> Quick Search by Ticket</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('staff/bookings/search-ticket') ?>">
                    <?= csrf_field() ?>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="ticket_number" class="form-control" placeholder="Enter booking ticket number" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-search"></i> Search Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-list"></i> Bookings List</h6>
            </div>
            <div class="card-body">
                <?php if (empty($reservations)): ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>No bookings found</h3>
                        <p>No bookings match your current filters</p>
                        <a href="<?= base_url('staff/bookings/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create First Booking
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Guest Name</th>
                                    <th>Room</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations as $reservation): ?>
                                    <tr>
                                        <td>
                                            <span class="code"><?= $reservation['booking_ticket_no'] ?></span>
                                        </td>
                                        <td>
                                            <strong><?= esc($reservation['booked_by_name']) ?></strong><br>
                                            <small style="color: var(--text-gray);"><?= esc($reservation['booked_by_phone']) ?></small>
                                        </td>
                                        <td>
                                            Room <?= esc($reservation['room_number']) ?><br>
                                            <small style="color: var(--text-gray);"><?= esc($reservation['type_name']) ?></small>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($reservation['check_in_date'])) ?></td>
                                        <td><?= date('M d, Y', strtotime($reservation['check_out_date'])) ?></td>
                                        <td><strong>$<?= number_format($reservation['total_price'], 2) ?></strong></td>
                                        <td>
                                            <?php
                                            $statusClass = match($reservation['status']) {
                                                'confirmed' => 'success',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger',
                                                'completed' => 'info',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge badge-<?= $statusClass ?>"><?= ucfirst($reservation['status']) ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= base_url('staff/bookings/view/' . $reservation['reservation_id']) ?>" 
                                                   class="btn btn-info btn-sm" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($reservation['status'] == 'pending'): ?>
                                                    <button type="button" class="btn btn-success btn-sm" 
                                                            onclick="updateStatus(<?= $reservation['reservation_id'] ?>, 'confirmed')" title="Confirm">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if (in_array($reservation['status'], ['pending', 'confirmed'])): ?>
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            onclick="updateStatus(<?= $reservation['reservation_id'] ?>, 'cancelled')" title="Cancel">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($reservation['status'] == 'confirmed' && $reservation['check_out_date'] <= date('Y-m-d')): ?>
                                                    <button type="button" class="btn btn-primary btn-sm" 
                                                            onclick="updateStatus(<?= $reservation['reservation_id'] ?>, 'completed')" title="Complete">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if (in_array($reservation['status'], ['pending', 'confirmed']) && $reservation['check_in_date'] > date('Y-m-d')): ?>
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            onclick="deleteBooking(<?= $reservation['history_id'] ?>)" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Status Update Form (Hidden) -->
    <form id="statusUpdateForm" method="POST" style="display: none;">
        <?= csrf_field() ?>
        <input type="hidden" name="status" id="statusInput">
    </form>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }

        function updateStatus(reservationId, status) {
            if (confirm('Are you sure you want to update this booking status?')) {
                const form = document.getElementById('statusUpdateForm');
                form.action = '<?= base_url('staff/bookings/update-status') ?>/' + reservationId;
                document.getElementById('statusInput').value = status;
                form.submit();
            }
        }

        function deleteBooking(bookingId) {
            if (confirm('Are you sure you want to delete this booking? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url('staff/bookings/delete') ?>/' + bookingId;
                
                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '<?= csrf_token() ?>';
                csrfField.value = '<?= csrf_hash() ?>';
                
                form.appendChild(csrfField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>