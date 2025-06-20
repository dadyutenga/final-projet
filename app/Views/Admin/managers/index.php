<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Managers - Hotel Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Include the dashboard CSS variables and common styles */
        :root {
            --primary-color: #32CD32;
            --primary-dark: #228B22;
            --primary-light: #90EE90;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --dark-gray: #333333;
            --text-gray: #666666;
            --border-color: #e0e0e0;
        }

        .main-content {
            padding: 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .search-box {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .search-input {
            flex: 1;
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .managers-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .managers-table th,
        .managers-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .managers-table th {
            background: var(--light-gray);
            font-weight: 600;
        }

        .managers-table tr:hover {
            background: var(--light-gray);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .page-link {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--dark-gray);
            text-decoration: none;
        }

        .page-link.active {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>
    <?= $this->include('admin/shared/sidebar') ?>

    <div class="main-content">
        <div class="page-header">
            <h2>Manage Managers</h2>
            <a href="<?= base_url('admin/managers/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Manager
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
                       placeholder="Search managers..." 
                       value="<?= $searchTerm ?? '' ?>">
            </form>
        </div>

        <table class="managers-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($managers as $manager): ?>
                <tr>
                    <td><?= esc($manager['full_name']) ?></td>
                    <td><?= esc($manager['username']) ?></td>
                    <td><?= esc($manager['email']) ?></td>
                    <td><?= esc($manager['phone']) ?? '-' ?></td>
                    <td><?= date('M d, Y', strtotime($manager['created_at'])) ?></td>
                    <td class="action-buttons">
                        <a href="<?= base_url('admin/managers/edit/' . $manager['manager_id']) ?>" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteManager(<?= $manager['manager_id'] ?>)" 
                                class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?= $pager->links() ?>
    </div>

    <script>
        function deleteManager(managerId) {
            if (confirm('Are you sure you want to delete this manager?')) {
                fetch(`<?= base_url('admin/managers') ?>/${managerId}`, {
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
                        alert('Failed to delete manager');
                    }
                });
            }
        }
    </script>
</body>
</html>
