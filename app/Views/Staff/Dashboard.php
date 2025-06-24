<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Hotel Management System</title>
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
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
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

        .user-info {
            background: var(--light-gray);
            padding: 1rem;
            margin: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-size: 1.5rem;
            color: var(--white);
        }

        .user-name {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.25rem;
        }

        .user-role {
            font-size: 0.8rem;
            color: var(--text-gray);
            text-transform: capitalize;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            color: var(--dark-gray);
            text-decoration: none;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
        }

        .nav-item:hover {
            background: var(--light-gray);
            color: var(--primary-color);
        }

        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary-dark);
            border-right-color: var(--primary-color);
            font-weight: 500;
        }

        .nav-item i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
        }

        .nav-item .badge {
            margin-left: auto;
            background: var(--danger-color);
            color: var(--white);
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
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

        .page-header h1 {
            font-size: 1.8rem;
            color: var(--dark-gray);
        }

        .welcome-time {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-top: 0.25rem;
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
            transform: translateY(-2px);
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

        .assigned { color: var(--info-color); }
        .in_progress { color: var(--warning-color); }
        .completed { color: var(--success-color); }
        .overdue { color: var(--danger-color); }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: between;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .task-item {
            padding: 1rem;
            border-left: 4px solid var(--border-color);
            background: var(--light-gray);
            margin-bottom: 1rem;
            border-radius: 0 6px 6px 0;
            transition: all 0.3s ease;
        }

        .task-item:hover {
            transform: translateX(4px);
        }

        .task-item.assigned { border-left-color: var(--info-color); }
        .task-item.in_progress { border-left-color: var(--warning-color); }
        .task-item.completed { border-left-color: var(--success-color); }
        .task-item.overdue { border-left-color: var(--danger-color); }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .task-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-assigned { background: rgba(23, 162, 184, 0.1); color: var(--info-color); }
        .status-in_progress { background: rgba(255, 193, 7, 0.1); color: var(--warning-color); }
        .status-completed { background: rgba(40, 167, 69, 0.1); color: var(--success-color); }
        .status-overdue { background: rgba(220, 53, 69, 0.1); color: var(--danger-color); }

        .task-description {
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .task-meta {
            font-size: 0.8rem;
            color: var(--text-gray);
            display: flex;
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

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--text-gray);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--border-color);
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: var(--white);
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-hotel"></i>
                Hotel Management
            </div>
            <div class="sidebar-subtitle">Staff Dashboard</div>
        </div>

        <div class="user-info">
            <div class="user-avatar">
                <?= strtoupper(substr($staff['full_name'], 0, 2)) ?>
            </div>
            <div class="user-name"><?= esc($staff['full_name']) ?></div>
            <div class="user-role"><?= esc($staff['role']) ?></div>
        </div>

        <nav class="sidebar-nav">
            <a href="<?= base_url('staff/dashboard') ?>" class="nav-item active">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="<?= base_url('staff/tasks') ?>" class="nav-item">
                <i class="fas fa-tasks"></i>
                My Tasks
                <?php if ($taskStats['assigned'] + $taskStats['in_progress'] > 0): ?>
                    <span class="badge"><?= $taskStats['assigned'] + $taskStats['in_progress'] ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= base_url('staff/profile') ?>" class="nav-item">
                <i class="fas fa-user"></i>
                Profile
            </a>
            <a href="<?= base_url('staff/logout') ?>" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="page-header">
            <div>
                <h1>Welcome back, <?= esc($staff['full_name']) ?>!</h1>
                <div class="welcome-time">
                    <?= date('l, F j, Y • g:i A') ?> • <?= esc($staff['hotel_name']) ?>
                </div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Task Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon assigned"><i class="fas fa-clipboard-list"></i></div>
                <div class="stat-value assigned"><?= $taskStats['assigned'] ?></div>
                <div class="stat-label">Assigned Tasks</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon in_progress"><i class="fas fa-clock"></i></div>
                <div class="stat-value in_progress"><?= $taskStats['in_progress'] ?></div>
                <div class="stat-label">In Progress</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed"><i class="fas fa-check-circle"></i></div>
                <div class="stat-value completed"><?= $taskStats['completed'] ?></div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon overdue"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-value overdue"><?= count($overdueTasks) ?></div>
                <div class="stat-label">Overdue</div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Recent Tasks -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks"></i>
                        Recent Tasks
                    </h3>
                    <a href="<?= base_url('staff/tasks') ?>" class="btn btn-outline">
                        <i class="fas fa-eye"></i>
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentTasks)): ?>
                        <?php foreach (array_slice($recentTasks, 0, 5) as $task): ?>
                            <div class="task-item <?= esc($task['status']) ?>">
                                <div class="task-header">
                                    <span class="task-status status-<?= esc($task['status']) ?>">
                                        <?= ucfirst(str_replace('_', ' ', $task['status'])) ?>
                                    </span>
                                    <small><?= date('M j', strtotime($task['assigned_date'])) ?></small>
                                </div>
                                <div class="task-description">
                                    <?= esc($task['task_description']) ?>
                                </div>
                                <div class="task-meta">
                                    <span><i class="fas fa-user"></i> <?= esc($task['assigned_by']) ?></span>
                                    <span><i class="fas fa-calendar"></i> Due: <?= date('M j, Y', strtotime($task['due_date'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <h3>No Tasks Yet</h3>
                            <p>You don't have any tasks assigned.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Upcoming & Overdue Tasks -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Urgent Tasks
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($overdueTasks)): ?>
                        <h4 style="color: var(--danger-color); margin-bottom: 1rem;">
                            <i class="fas fa-exclamation-circle"></i> Overdue
                        </h4>
                        <?php foreach ($overdueTasks as $task): ?>
                            <div class="task-item overdue">
                                <div class="task-description">
                                    <?= esc($task['task_description']) ?>
                                </div>
                                <div class="task-meta">
                                    <span><i class="fas fa-calendar-times"></i> Due: <?= date('M j, Y', strtotime($task['due_date'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($upcomingTasks)): ?>
                        <h4 style="color: var(--warning-color); margin-bottom: 1rem; margin-top: <?= !empty($overdueTasks) ? '2rem' : '0' ?>;">
                            <i class="fas fa-clock"></i> Due Soon
                        </h4>
                        <?php foreach (array_slice($upcomingTasks, 0, 3) as $task): ?>
                            <div class="task-item <?= esc($task['status']) ?>">
                                <div class="task-description">
                                    <?= esc($task['task_description']) ?>
                                </div>
                                <div class="task-meta">
                                    <span><i class="fas fa-calendar"></i> Due: <?= date('M j, Y', strtotime($task['due_date'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (empty($overdueTasks) && empty($upcomingTasks)): ?>
                        <div class="empty-state">
                            <i class="fas fa-check-circle"></i>
                            <h3>All Caught Up!</h3>
                            <p>No urgent tasks at the moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        // Auto refresh task counts every 5 minutes
        setInterval(function() {
            location.reload();
        }, 300000);
    </script>
</body>
</html>