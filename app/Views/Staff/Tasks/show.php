<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details - Hotel Management System</title>
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
                padding: 1rem;
                padding-top: 4rem;
            }
        }

        .task-detail-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .back-btn {
            background: var(--light-gray);
            color: var(--dark-gray);
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: var(--border-color);
            color: var(--dark-gray);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            text-transform: capitalize;
            font-size: 0.9rem;
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
            margin-right: 0.5rem;
            font-size: 0.9rem;
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

        .btn-warning:hover {
            background: #e0a800;
            color: var(--dark-gray);
        }

        .task-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .meta-item i {
            color: var(--primary-color);
        }

        .task-description {
            background: var(--light-gray);
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
            line-height: 1.6;
        }

        .task-description h3 {
            color: var(--dark-gray);
            margin-bottom: 1rem;
        }

        .task-description p {
            color: var(--text-gray);
            font-size: 1rem;
        }

        .task-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
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

        @media (max-width: 768px) {
            .task-header {
                flex-direction: column;
                gap: 1rem;
            }

            .task-meta-grid {
                grid-template-columns: 1fr;
            }

            .task-actions {
                flex-direction: column;
            }

            .btn {
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

    <?= $this->include('staff/shared/sidebar') ?>

    <div class="main-content">
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

        <a href="<?= base_url('staff/tasks') ?>" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Tasks
        </a>

        <div class="task-detail-card">
            <div class="task-header">
                <div>
                    <h2>Task #<?= $task['task_id'] ?></h2>
                    <p style="color: var(--text-gray); margin-top: 0.5rem;">
                        <i class="fas fa-user"></i> Assigned to: <?= esc($task['staff_name']) ?>
                    </p>
                </div>
                <div class="status-badge status-<?= esc($task['status']) ?>">
                    <?= ucfirst(str_replace('_', ' ', $task['status'])) ?>
                </div>
            </div>

            <div class="task-description">
                <h3>Task Description</h3>
                <p><?= esc($task['task_description']) ?></p>
            </div>

            <div class="task-meta-grid">
                <div class="meta-item">
                    <i class="fas fa-calendar-plus"></i>
                    <span><strong>Assigned:</strong> <?= date('M j, Y g:i A', strtotime($task['assigned_date'])) ?></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar-times"></i>
                    <span><strong>Due:</strong> <?= date('M j, Y g:i A', strtotime($task['due_date'])) ?></span>
                </div>
                <?php if (isset($task['completed_at']) && $task['completed_at']): ?>
                <div class="meta-item">
                    <i class="fas fa-check-circle"></i>
                    <span><strong>Completed:</strong> <?= date('M j, Y g:i A', strtotime($task['completed_at'])) ?></span>
                </div>
                <?php endif; ?>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($task['created_at'])) ?></span>
                </div>
            </div>

            <div class="task-actions">
                <?php if ($task['status'] == 'assigned'): ?>
                    <button onclick="updateTaskStatus(<?= $task['task_id'] ?>, 'in_progress')" class="btn btn-warning">
                        <i class="fas fa-play"></i> Start Task
                    </button>
                <?php endif; ?>
                
                <?php if ($task['status'] == 'in_progress'): ?>
                    <button onclick="updateTaskStatus(<?= $task['task_id'] ?>, 'completed')" class="btn btn-success">
                        <i class="fas fa-check"></i> Mark Complete
                    </button>
                <?php endif; ?>
                
                <?php if ($task['status'] == 'completed'): ?>
                    <div style="color: var(--success-color); font-weight: 500;">
                        <i class="fas fa-check-circle"></i> Task Completed
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }

        async function updateTaskStatus(taskId, status) {
            const statusText = status === 'in_progress' ? 'start this task' : 'mark this task as completed';
            
            if (!confirm(`Are you sure you want to ${statusText}?`)) {
                return;
            }

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
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(result.message || 'Failed to update task status', 'error');
                }
            } catch (error) {
                console.error('Error updating task status:', error);
                showAlert('An error occurred while updating the task', 'error');
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
            mainContent.insertBefore(alertDiv, mainContent.firstChild);

            // Auto-hide alert after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>