<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms Management - Hotel Management System</title>
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

        .btn-info {
            background: var(--info-color);
            color: var(--white);
        }

        .btn-info:hover {
            background: #138496;
            color: var(--white);
        }

        .btn-warning {
            background: var(--warning-color);
            color: var(--dark-gray);
        }

        .btn-warning:hover {
            background: #e0a800;
            color: var(--dark-gray);
        }

        .btn-danger {
            background: var(--danger-color);
            color: var(--white);
        }

        .btn-danger:hover {
            background: #c82333;
            color: var(--white);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
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

        .available { color: var(--success-color); }
        .occupied { color: var(--danger-color); }
        .maintenance { color: var(--warning-color); }
        .total { color: var(--info-color); }

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

        .table-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .table th {
            background: var(--light-gray);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark-gray);
            border-bottom: 1px solid var(--border-color);
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--dark-gray);
        }

        .table tbody tr:hover {
            background: rgba(50, 205, 50, 0.05);
        }

        .room-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-2px);
        }

        .room-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .room-number {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .room-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .room-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: var(--text-gray);
        }

        .room-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .status-available {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .status-occupied {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .status-maintenance {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
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

        .view-toggle {
            display: flex;
            gap: 0.5rem;
            margin-left: auto;
        }

        .toggle-btn {
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            background: var(--white);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-btn.active {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .bulk-actions {
            display: none;
            padding: 1rem;
            background: var(--light-gray);
            border-bottom: 1px solid var(--border-color);
            align-items: center;
            gap: 1rem;
        }

        .bulk-actions.show {
            display: flex;
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
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-logo {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .sidebar-subtitle {
            font-size: 0.8rem;
            color: var(--text-gray);
            margin-top: 0.25rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            color: var(--dark-gray);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background: var(--light-gray);
            color: var(--primary-color);
        }

        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary-dark);
            border-right: 3px solid var(--primary-color);
        }

        .nav-item i {
            width: 20px;
            margin-right: 0.75rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .filters-row {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .rooms-grid {
                grid-template-columns: 1fr;
            }

            .room-actions {
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
    <?= $this->include('managers/shared/sidebar') ?>

    <div class="main-content">
        <div class="page-header">
            <h2><i class="fas fa-door-open"></i> Rooms Management</h2>
            <div class="view-toggle">
                <button class="toggle-btn active" onclick="toggleView('grid')" id="gridToggle">
                    <i class="fas fa-th-large"></i>
                </button>
                <button class="toggle-btn" onclick="toggleView('table')" id="tableToggle">
                    <i class="fas fa-list"></i>
                </button>
                <a href="<?= base_url('manager/rooms/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Room
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
                <i class="fas fa-exclamation-triangle"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Room Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon available"><i class="fas fa-door-open"></i></div>
                <div class="stat-value available"><?= $roomStats['available'] ?></div>
                <div class="stat-label">Available Rooms</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon occupied"><i class="fas fa-bed"></i></div>
                <div class="stat-value occupied"><?= $roomStats['occupied'] ?></div>
                <div class="stat-label">Occupied Rooms</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon maintenance"><i class="fas fa-tools"></i></div>
                <div class="stat-value maintenance"><?= $roomStats['maintenance'] ?></div>
                <div class="stat-label">Under Maintenance</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon total"><i class="fas fa-building"></i></div>
                <div class="stat-value total"><?= $roomStats['total'] ?></div>
                <div class="stat-label">Total Rooms</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <form method="GET" action="<?= base_url('manager/rooms') ?>">
                <div class="filters-row">
                    <div class="form-group">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?= esc($searchTerm) ?>" placeholder="Room number or type...">
                    </div>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="available" <?= $statusFilter == 'available' ? 'selected' : '' ?>>Available</option>
                            <option value="occupied" <?= $statusFilter == 'occupied' ? 'selected' : '' ?>>Occupied</option>
                            <option value="maintenance" <?= $statusFilter == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type" class="form-label">Room Type</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">All Types</option>
                            <?php foreach ($roomTypes as $type): ?>
                                <option value="<?= $type['room_type_id'] ?>" <?= $typeFilter == $type['room_type_id'] ? 'selected' : '' ?>>
                                    <?= esc($type['type_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="floor" class="form-label">Floor</label>
                        <select class="form-select" id="floor" name="floor">
                            <option value="">All Floors</option>
                            <?php foreach ($floors as $floor): ?>
                                <option value="<?= $floor['floor'] ?>" <?= $floorFilter == $floor['floor'] ? 'selected' : '' ?>>
                                    Floor <?= $floor['floor'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <?php if (!empty($rooms)): ?>
            <!-- Grid View -->
            <div class="rooms-grid" id="gridView">
                <?php foreach ($rooms as $room): ?>
                    <div class="room-card">
                        <div class="room-header">
                            <div class="room-number">Room <?= esc($room['room_number']) ?></div>
                            <span class="room-status status-<?= esc($room['status']) ?>">
                                <?= ucfirst($room['status']) ?>
                            </span>
                        </div>
                        <div class="room-details">
                            <div class="detail-item">
                                <i class="fas fa-bed"></i>
                                <span><?= esc($room['type_name']) ?></span>
                            </div>
                            <?php if ($room['floor']): ?>
                                <div class="detail-item">
                                    <i class="fas fa-building"></i>
                                    <span>Floor <?= esc($room['floor']) ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <span><?= esc($room['capacity']) ?> Guests</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-dollar-sign"></i>
                                <span>$<?= number_format($room['base_price'], 2) ?>/night</span>
                            </div>
                        </div>
                        <div class="room-actions">
                            <a href="<?= base_url('manager/rooms/show/' . $room['room_id']) ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="<?= base_url('manager/rooms/edit/' . $room['room_id']) ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="<?= base_url('manager/rooms/destroy/' . $room['room_id']) ?>" method="post" style="display: inline-block;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this room?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Table View -->
            <div class="table-card" id="tableView" style="display: none;">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> Room #</th>
                            <th><i class="fas fa-bed"></i> Type</th>
                            <th><i class="fas fa-building"></i> Floor</th>
                            <th><i class="fas fa-users"></i> Capacity</th>
                            <th><i class="fas fa-dollar-sign"></i> Price</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $room): ?>
                            <tr>
                                <td><strong><?= esc($room['room_number']) ?></strong></td>
                                <td><?= esc($room['type_name']) ?></td>
                                <td><?= $room['floor'] ? 'Floor ' . esc($room['floor']) : 'N/A' ?></td>
                                <td><?= esc($room['capacity']) ?> guests</td>
                                <td>$<?= number_format($room['base_price'], 2) ?></td>
                                <td>
                                    <span class="room-status status-<?= esc($room['status']) ?>">
                                        <?= ucfirst($room['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="room-actions">
                                        <a href="<?= base_url('manager/rooms/show/' . $room['room_id']) ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('manager/rooms/edit/' . $room['room_id']) ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?= base_url('manager/rooms/destroy/' . $room['room_id']) ?>" method="post" style="display: inline-block;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-door-open"></i>
                <h3>No Rooms Found</h3>
                <p>Start by adding your first room to get started.</p>
                <a href="<?= base_url('manager/rooms/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add First Room
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleView(view) {
            const gridView = document.getElementById('gridView');
            const tableView = document.getElementById('tableView');
            const gridToggle = document.getElementById('gridToggle');
            const tableToggle = document.getElementById('tableToggle');

            if (view === 'grid') {
                gridView.style.display = 'grid';
                tableView.style.display = 'none';
                gridToggle.classList.add('active');
                tableToggle.classList.remove('active');
            } else {
                gridView.style.display = 'none';
                tableView.style.display = 'block';
                gridToggle.classList.remove('active');
                tableToggle.classList.add('active');
            }
        }
    </script>
</body>
</html>