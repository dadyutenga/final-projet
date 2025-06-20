<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hotels - Hotel Management System</title>
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
            --danger-color: #dc3545;
            --danger-dark: #c82333;
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
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-danger {
            background: var(--danger-color);
            color: var(--white);
        }

        .btn-danger:hover {
            background: var(--danger-dark);
        }

        .search-box {
            margin-bottom: 2rem;
        }

        .search-input {
            width: 100%;
            max-width: 400px;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .hotel-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .hotel-card:hover {
            transform: translateY(-2px);
        }

        .hotel-card-header {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .hotel-logo {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .hotel-info {
            flex: 1;
        }

        .hotel-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }

        .hotel-location {
            color: var(--text-gray);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hotel-card-body {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .hotel-stat {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stat-label {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .stat-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .hotel-card-footer {
            padding: 1rem 1.5rem;
            background: var(--light-gray);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a, .pagination strong {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            color: var(--dark-gray);
            background: var(--white);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .pagination strong, .pagination a:hover {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .hotel-card-body {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--white);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-logo {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .sidebar-subtitle {
            font-size: 0.9rem;
            color: var(--text-gray);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: var(--text-gray);
            font-weight: 600;
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
        }

        .nav-item i {
            width: 20px;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <?= $this->include('admin/shared/sidebar') ?>

    <div class="main-content">
        <div class="page-header">
            <h2>Manage Hotels</h2>
            <a href="<?= base_url('admin/hotels/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Hotel
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="search-box">
            <form action="" method="get" class="flex-grow-1">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Search hotels..." 
                       value="<?= $searchTerm ?? '' ?>">
            </form>
        </div>

        <?php foreach ($hotels as $hotel): ?>
        <div class="hotel-card">
            <div class="hotel-card-header">
                <img src="<?= base_url($hotel['hotel_logo']) ?>" alt="<?= esc($hotel['name']) ?>" class="hotel-logo">
                <div class="hotel-info">
                    <h3 class="hotel-name"><?= esc($hotel['name']) ?></h3>
                    <div class="hotel-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <?= esc($hotel['city']) ?>, <?= esc($hotel['country']) ?>
                    </div>
                </div>
            </div>
            <div class="hotel-card-body">
                <div class="hotel-stat">
                    <span class="stat-label">Manager</span>
                    <span class="stat-value"><?= esc($hotel['manager_name'] ?? 'Not Assigned') ?></span>
                </div>
                <div class="hotel-stat">
                    <span class="stat-label">Contact</span>
                    <span class="stat-value"><?= esc($hotel['phone']) ?></span>
                </div>
                <div class="hotel-stat">
                    <span class="stat-label">Email</span>
                    <span class="stat-value"><?= esc($hotel['email']) ?></span>
                </div>
            </div>
            <div class="hotel-card-footer">
                <a href="<?= base_url('admin/hotels/edit/' . $hotel['hotel_id']) ?>" 
                   class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <button onclick="deleteHotel(<?= $hotel['hotel_id'] ?>)" 
                        class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
            <div class="pagination">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function deleteHotel(hotelId) {
            if (confirm('Are you sure you want to delete this hotel?')) {
                fetch(`<?= base_url('admin/hotels') ?>/${hotelId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Failed to delete hotel');
                    }
                });
            }
        }
    </script>
</body>
</html>
