<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System - Manager Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            {{ edit_1 }}  <!-- Changed primary colors to teal green shades -->
            --primary-color: #008080;  /* Teal */
            --primary-dark: #005050;   /* Darker teal */
            --primary-light: #40E0D0;  /* Lighter teal */
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --dark-gray: #333333;
            --text-gray: #666666;
            --border-color: #e0e0e0;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-gray);
            color: var(--dark-gray);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

        .header {
            background: var(--white);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-radius: 10px;
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
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
        }

        .stat-icon.primary { background: var(--primary-color); }
        .stat-icon.success { background: var(--success); }
        .stat-icon.warning { background: var(--warning); }
        .stat-icon.info { background: var(--info); }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-title {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .dashboard-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
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
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-active { 
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-inactive {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        @media (max-width: 1024px) {
            .dashboard-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Include the external sidebar or manager-specific one if available -->
    <?= $this->include('managers/shared/sidebar') ?>  <!-- Assuming a manager sidebar exists or adjust as needed -->

    <div class="main-content">
        <div class="header">
            <h1>Manager Dashboard</h1>
            <div class="user-info">
                <span>Welcome, <?= esc($manager_name) ?></span>  <!-- Changed to manager-specific variable -->
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Hotels Managed</div>
                    <div class="stat-icon primary">
                        <i class="fas fa-hotel"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $total_hotels_managed ?? 0 ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Staff</div>
                    <div class="stat-icon warning">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $total_staff ?? 0 ?></div>
            </div>
            <!-- Add more stats as needed -->
        </div>

        <div class="dashboard-row">
            <div class="card">
                <div class="card-header">
                    <h3>Recent Activities</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($recent_activities) && is_array($recent_activities)): ?>
                                <?php foreach ($recent_activities as $activity): ?>
                                <tr>
                                    <td><?= esc($activity['description']) ?></td>
                                    <td><?= esc($activity['time']) ?></td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower(esc($activity['status'])) ?>">
                                            <?= ucfirst(esc($activity['status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No recent activities found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Pending Tasks</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($pending_tasks) && is_array($pending_tasks)): ?>
                                <?php foreach ($pending_tasks as $task): ?>
                                <tr>
                                    <td><?= esc($task['description']) ?></td>
                                    <td><?= esc($task['due_date']) ?></td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower(esc($task['status'])) ?>">
                                            <?= ucfirst(esc($task['status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No pending tasks found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple script for responsive sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            function handleResize() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('collapsed');
                    mainContent.style.marginLeft = '0';
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.style.marginLeft = '280px';
                }
            }

            window.addEventListener('resize', handleResize);
            handleResize(); // Initial check
        });
    </script>
</body>
</html>
