<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Hotel Management System</title>
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
            background: #17a2b8;
            color: var(--white);
        }

        .btn-info:hover {
            background: #138496;
            color: var(--white);
        }

        .btn-warning {
            background: #ffc107;
            color: var(--dark-gray);
        }

        .btn-warning:hover {
            background: #e0a800;
            color: var(--dark-gray);
        }

        .btn-danger {
            background: #dc3545;
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

        .table-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
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

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .inline-form {
            display: inline-block;
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

            .action-buttons {
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
            <h2><i class="fas fa-users"></i> Staff Management</h2>
            <a href="<?= base_url('manager/staff/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Staff
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="table-card">
            <?php if (!empty($staff)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Full Name</th>
                            <th><i class="fas fa-briefcase"></i> Role</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Phone</th>
                            <th><i class="fas fa-calendar"></i> Hire Date</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staff as $member): ?>
                            <tr>
                                <td><?= esc($member['staff_id']) ?></td>
                                <td>
                                    <strong><?= esc($member['full_name']) ?></strong>
                                    <div style="font-size: 0.8rem; color: var(--text-gray);">
                                        @<?= esc($member['username'] ?? 'N/A') ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background: var(--primary-light); color: var(--primary-dark); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                                        <?= esc($member['role']) ?>
                                    </span>
                                </td>
                                <td><?= esc($member['email'] ?? 'N/A') ?></td>
                                <td><?= esc($member['phone'] ?? 'N/A') ?></td>
                                <td><?= esc($member['hire_date'] ?? 'N/A') ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= base_url('manager/staff/show/' . $member['staff_id']) ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="<?= base_url('manager/staff/edit/' . $member['staff_id']) ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="<?= base_url('manager/staff/destroy/' . $member['staff_id']) ?>" method="post" class="inline-form">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this staff member?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>No Staff Members Found</h3>
                    <p>Start by adding your first staff member to get started.</p>
                    <a href="<?= base_url('manager/staff/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add First Staff Member
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
