<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Room - Hotel Management System</title>
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

        .form-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--dark-gray);
            font-weight: 500;
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

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .alert ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .form-text {
            font-size: 0.875rem;
            color: var(--text-gray);
            margin-top: 0.25rem;
        }

        .required {
            color: #dc3545;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .room-type-info {
            background: var(--light-gray);
            padding: 1rem;
            border-radius: 8px;
            margin-top: 0.5rem;
            display: none;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .status-preview {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .status-available {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-occupied {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .status-maintenance {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
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
        }
    </style>
</head>
<body>
    <?= $this->include('managers/shared/sidebar') ?>

    <div class="main-content">
        <div class="page-header">
            <h2><i class="fas fa-door-open"></i> Create New Room</h2>
        </div>

        <div class="form-card">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('manager/rooms/store') ?>" method="post" id="roomForm">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="room_type_id" class="form-label">Room Type <span class="required">*</span></label>
                    <select class="form-select" id="room_type_id" name="room_type_id" required onchange="updateRoomTypeInfo()">
                        <option value="">Select Room Type</option>
                        <?php foreach ($roomTypes as $type): ?>
                            <option value="<?= $type['room_type_id'] ?>" 
                                    data-type-name="<?= esc($type['type_name']) ?>"
                                    data-capacity="<?= $type['capacity'] ?>"
                                    data-price="<?= $type['base_price'] ?>"
                                    data-description="<?= esc($type['description']) ?>"
                                    <?= old('room_type_id') == $type['room_type_id'] ? 'selected' : '' ?>>
                                <?= esc($type['type_name']) ?> - $<?= number_format($type['base_price'], 2) ?>/night
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="room-type-info" id="roomTypeInfo">
                        <div class="info-item">
                            <span><i class="fas fa-users"></i> Capacity:</span>
                            <span id="typeCapacity">-</span>
                        </div>
                        <div class="info-item">
                            <span><i class="fas fa-dollar-sign"></i> Base Price:</span>
                            <span id="typePrice">-</span>
                        </div>
                        <div class="info-item">
                            <span><i class="fas fa-info-circle"></i> Description:</span>
                        </div>
                        <div id="typeDescription" style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--text-gray);"></div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="room_number" class="form-label">Room Number <span class="required">*</span></label>
                        <input type="text" class="form-control" id="room_number" name="room_number" 
                               value="<?= old('room_number') ?>" required placeholder="e.g., 101, A12, etc.">
                        <div class="form-text">Unique identifier for this room</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="floor" class="form-label">Floor</label>
                        <select class="form-select" id="floor" name="floor">
                            <option value="">Select Floor</option>
                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i ?>" <?= old('floor') == $i ? 'selected' : '' ?>>
                                    Floor <?= $i ?>
                                </option>
                            <?php endfor; ?>
                            <option value="0" <?= old('floor') === '0' ? 'selected' : '' ?>>Ground Floor</option>
                            <option value="-1" <?= old('floor') === '-1' ? 'selected' : '' ?>>Basement</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status" class="form-label">Initial Status <span class="required">*</span></label>
                    <select class="form-select" id="status" name="status" required onchange="updateStatusPreview()">
                        <option value="">Select Status</option>
                        <option value="available" <?= old('status') == 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="occupied" <?= old('status') == 'occupied' ? 'selected' : '' ?>>Occupied</option>
                        <option value="maintenance" <?= old('status') == 'maintenance' ? 'selected' : '' ?>>Under Maintenance</option>
                    </select>
                    <div class="form-text">
                        Room status can be changed later from the rooms list
                        <span class="status-preview" id="statusPreview"></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Room
                    </button>
                    <a href="<?= base_url('manager/rooms') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateRoomTypeInfo() {
            const select = document.getElementById('room_type_id');
            const info = document.getElementById('roomTypeInfo');
            const capacity = document.getElementById('typeCapacity');
            const price = document.getElementById('typePrice');
            const description = document.getElementById('typeDescription');
            
            if (select.value) {
                const option = select.options[select.selectedIndex];
                capacity.textContent = option.dataset.capacity + ' guests';
                price.textContent = '$' + parseFloat(option.dataset.price).toFixed(2) + '/night';
                description.textContent = option.dataset.description || 'No description available';
                info.style.display = 'block';
            } else {
                info.style.display = 'none';
            }
        }

        function updateStatusPreview() {
            const select = document.getElementById('status');
            const preview = document.getElementById('statusPreview');
            
            if (select.value) {
                preview.textContent = select.options[select.selectedIndex].text;
                preview.className = 'status-preview status-' + select.value;
                preview.style.display = 'inline-block';
            } else {
                preview.style.display = 'none';
            }
        }

        // Auto-generate room number suggestion
        document.getElementById('floor').addEventListener('change', function() {
            const floor = this.value;
            const roomNumber = document.getElementById('room_number');
            
            if (floor && !roomNumber.value) {
                // Simple suggestion: floor + 01, 02, etc.
                let suggestion = '';
                if (floor === '0') {
                    suggestion = 'G01';
                } else if (floor === '-1') {
                    suggestion = 'B01';
                } else {
                    suggestion = floor.padStart(1, '0') + '01';
                }
                roomNumber.placeholder = 'e.g., ' + suggestion;
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateRoomTypeInfo();
            updateStatusPreview();
        });
    </script>
</body>
</html>