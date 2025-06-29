<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks - Hotel Management System</title>
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

        .assigned { color: var(--info-color); }
        .in_progress { color: var(--warning-color); }
        .completed { color: var(--success-color); }
        .overdue { color: var(--danger-color); }

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
            box-shadow: 0 0 0 3px rgba(0, 128, 128, 0.1);
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

        .tasks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .task-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            border-left: 4px solid transparent;
            position: relative;
        }

        .task-card:hover {
            transform: translateY(-2px);
        }

        .task-card.assigned {
            border-left-color: var(--info-color);
        }

        .task-card.in_progress {
            border-left-color: var(--warning-color);
        }

        .task-card.completed {
            border-left-color: var(--success-color);
        }

        .task-card.overdue {
            border-left-color: var(--danger-color);
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .task-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-assigned {
            background: rgba(23, 162, 184, 0.1);
            color: var(--info-color);
        }

        .status-in_progress {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .status-completed {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .status-overdue {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .task-description {
            margin-bottom: 1rem;
            line-height: 1.5;
            color: var(--dark-gray);
            font-size: 0.95rem;
        }

        .task-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            color: var(--text-gray);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .task-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
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

        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.2);
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

        .urgent-tasks {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .urgent-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            color: var(--danger-color);
        }

        .urgent-list {
            list-style: none;
        }

        .urgent-item {
            padding: 0.75rem;
            border-left: 3px solid var(--danger-color);
            background: rgba(220, 53, 69, 0.05);
            margin-bottom: 0.5rem;
            border-radius: 0 6px 6px 0;
        }

        .urgent-item:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .tasks-grid {
                grid-template-columns: 1fr;
            }

            .filters-row {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .task-actions {
                flex-direction: column;
            }

            .btn-sm {
                width: 100%;
                justify-content: center;
            }
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }

        .loading-spinner {
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .spinner {
            border: 4px solid var(--light-gray);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar-overlay" onclick="closeSidebar()"></div>
    
    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Updating task...</p>
        </div>
    </div>
    
    <?= $this->include('staff/shared/sidebar') ?>

    <div class="main-content">
        <div class="page-header">
            <h2>
                <i class="fas fa-tasks"></i>
                My Tasks
            </h2>
            <div style="font-size: 0.9rem; color: var(--text-gray);">
                <i class="fas fa-user"></i> <?= session()->get('staff_name') ?>
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

        <!-- Task Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon assigned"><i class="fas fa-clipboard-list"></i></div>
                <div class="stat-value assigned"><?= $taskStats['assigned'] ?></div>
                <div class="stat-label">Assigned</div>
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
                <div class="stat-value overdue"><?= $taskStats['overdue'] ?></div>
                <div class="stat-label">Overdue</div>
            </div>
        </div>

        <!-- Urgent Tasks Alert -->
        <?php if (!empty($overdueTasks) || !empty($tasksDueToday)): ?>
            <div class="urgent-tasks">
                <div class="urgent-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Urgent Attention Required</h3>
                </div>
                <ul class="urgent-list">
                    <?php foreach ($overdueTasks as $task): ?>
                        <li class="urgent-item">
                            <strong>OVERDUE:</strong> <?= esc($task['task_description']) ?>
                            (Due: <?= date('M j, Y', strtotime($task['due_date'])) ?>)
                        </li>
                    <?php endforeach; ?>
                    <?php foreach ($tasksDueToday as $task): ?>
                        <li class="urgent-item">
                            <strong>DUE TODAY:</strong> <?= esc($task['task_description']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="filters-card">
            <form method="GET" action="<?= base_url('staff/tasks') ?>">
                <div class="filters-row">
                    <div class="form-group">
                        <label for="search" class="form-label">Search Tasks</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?= esc($searchTerm) ?>" placeholder="Search task descriptions...">
                    </div>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="assigned" <?= $statusFilter == 'assigned' ? 'selected' : '' ?>>Assigned</option>
                            <option value="in_progress" <?= $statusFilter == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="completed" <?= $statusFilter == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="overdue" <?= $statusFilter == 'overdue' ? 'selected' : '' ?>>Overdue</option>
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

        <!-- Tasks Grid -->
        <?php if (!empty($tasks)): ?>
            <div class="tasks-grid">
                <?php foreach ($tasks as $task): ?>
                    <div class="task-card <?= esc($task['status']) ?>">
                        <div class="task-header">
                            <span class="task-status status-<?= esc($task['status']) ?>">
                                <?= ucfirst(str_replace('_', ' ', $task['status'])) ?>
                            </span>
                            <small class="text-muted">
                                #<?= $task['task_id'] ?>
                            </small>
                        </div>
                        
                        <div class="task-description">
                            <?= esc($task['task_description']) ?>
                        </div>
                        
                        <div class="task-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Assigned: <?= date('M j, Y', strtotime($task['assigned_date'])) ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar-times"></i>
                                <span>Due: <?= date('M j, Y', strtotime($task['due_date'])) ?></span>
                            </div>
                            <?php if (isset($task['completed_at']) && $task['completed_at']): ?>
                            <div class="meta-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Completed: <?= date('M j, Y', strtotime($task['completed_at'])) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="task-actions">
                            <a href="<?= base_url('staff/tasks/show/' . $task['task_id']) ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            
                            <?php if ($task['status'] == 'assigned'): ?>
                                <button onclick="updateTaskStatus(<?= $task['task_id'] ?>, 'in_progress')" class="btn btn-warning btn-sm">
                                    <i class="fas fa-play"></i> Start Task
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($task['status'] == 'in_progress'): ?>
                                <button onclick="updateTaskStatus(<?= $task['task_id'] ?>, 'completed')" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Mark Complete
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-tasks"></i>
                <h3>No Tasks Found</h3>
                <p>You don't have any tasks assigned at the moment.</p>
            </div>
        <?php endif; ?>
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

        async function updateTaskStatus(taskId, status) {
            const statusText = status === 'in_progress' ? 'start this task' : 'mark this task as completed';
            
            if (!confirm(`Are you sure you want to ${statusText}?`)) {
                return;
            }

            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'flex';

            try {
                const formData = new FormData();
                formData.append('status', status);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                const response = await fetch(`<?= base_url('staff/tasks/update-status/') ?>${taskId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    showAlert('Task status updated successfully!', 'success');
                    
                    // Reload the page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(result.message || 'Failed to update task status', 'error');
                }
            } catch (error) {
                console.error('Error updating task status:', error);
                showAlert('An error occurred while updating the task', 'error');
            } finally {
                loadingOverlay.style.display = 'none';
            }
        }

        function showAlert(message, type) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());

            // Create new alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                ${message}
            `;

            // Insert alert at the top of main content
            const mainContent = document.querySelector('.main-content');
            const pageHeader = document.querySelector('.page-header');
            mainContent.insertBefore(alertDiv, pageHeader.nextSibling);

            // Auto-hide alert after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>