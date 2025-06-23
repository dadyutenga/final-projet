<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign New Task - Hotel Management System</title>
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
            font-size: 1rem;
        }

        .nav-item .badge {
            margin-left: auto;
            background: var(--primary-color);
            color: var(--white);
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .nav-item .badge.danger {
            background: #dc3545;
        }

        .nav-item .badge.warning {
            background: #ffc107;
            color: var(--dark-gray);
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

        .form-control, .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-control:focus, .form-select:focus, .form-textarea:focus {
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

        .staff-info {
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

        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar-overlay" onclick="closeSidebar()"></div>
    
    <?= $this->include('managers/shared/sidebar') ?>

    <div class="main-content">
        <div class="page-header">
            <h2><i class="fas fa-tasks"></i> Assign New Task</h2>
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
            
            <form action="<?= base_url('manager/staff-tasks/store') ?>" method="post" id="taskForm">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="staff_id" class="form-label">Assign to Staff Member <span class="required">*</span></label>
                    <select class="form-select" id="staff_id" name="staff_id" required onchange="updateStaffInfo()">
                        <option value="">Select Staff Member</option>
                        <?php foreach ($staff as $member): ?>
                            <option value="<?= $member['staff_id'] ?>" 
                                    data-name="<?= esc($member['full_name']) ?>"
                                    data-role="<?= esc($member['role']) ?>"
                                    data-phone="<?= esc($member['phone']) ?>"
                                    data-email="<?= esc($member['email']) ?>"
                                    data-hire="<?= esc($member['hire_date']) ?>"
                                    <?= old('staff_id') == $member['staff_id'] ? 'selected' : '' ?>>
                                <?= esc($member['full_name']) ?> - <?= esc($member['role']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="staff-info" id="staffInfo">
                        <div class="info-item">
                            <span><i class="fas fa-user"></i> Name:</span>
                            <span id="staffName">-</span>
                        </div>
                        <div class="info-item">
                            <span><i class="fas fa-briefcase"></i> Role:</span>
                            <span id="staffRole">-</span>
                        </div>
                        <div class="info-item">
                            <span><i class="fas fa-phone"></i> Phone:</span>
                            <span id="staffPhone">-</span>
                        </div>
                        <div class="info-item">
                            <span><i class="fas fa-envelope"></i> Email:</span>
                            <span id="staffEmail">-</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="task_description" class="form-label">Task Description <span class="required">*</span></label>
                    <textarea class="form-textarea" id="task_description" name="task_description" 
                              required placeholder="Describe the task in detail..."><?= old('task_description') ?></textarea>
                    <div class="form-text">Be specific about what needs to be done, where, and any special instructions.</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="due_date" class="form-label">Due Date <span class="required">*</span></label>
                        <input type="datetime-local" class="form-control" id="due_date" name="due_date" 
                               value="<?= old('due_date') ?>" required min="<?= date('Y-m-d\TH:i') ?>">
                        <div class="form-text">When should this task be completed?</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="priority" class="form-label">Priority Level</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="medium" <?= old('priority') == 'medium' ? 'selected' : '' ?>>Medium (Default)</option>
                            <option value="low" <?= old('priority') == 'low' ? 'selected' : '' ?>>Low</option>
                            <option value="high" <?= old('priority') == 'high' ? 'selected' : '' ?>>High</option>
                            <option value="urgent" <?= old('priority') == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                        </select>
                        <div class="form-text">Set the priority level for this task</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Additional Information</label>
                    <div class="form-text" style="margin-bottom: 1rem;">
                        <strong>Task Assignment Guidelines:</strong><br>
                        • Be clear and specific in task descriptions<br>
                        • Set realistic due dates<br>
                        • Consider staff workload and availability<br>
                        • High priority tasks will be highlighted to staff<br>
                        • Staff will be notified of new task assignments
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Assign Task
                    </button>
                    <a href="<?= base_url('manager/staff-tasks') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
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

        function updateStaffInfo() {
            const select = document.getElementById('staff_id');
            const info = document.getElementById('staffInfo');
            const name = document.getElementById('staffName');
            const role = document.getElementById('staffRole');
            const phone = document.getElementById('staffPhone');
            const email = document.getElementById('staffEmail');
            
            if (select.value) {
                const option = select.options[select.selectedIndex];
                name.textContent = option.dataset.name;
                role.textContent = option.dataset.role;
                phone.textContent = option.dataset.phone || 'Not provided';
                email.textContent = option.dataset.email || 'Not provided';
                info.style.display = 'block';
            } else {
                info.style.display = 'none';
            }
        }

        // Set minimum due date to current time
        document.addEventListener('DOMContentLoaded', function() {
            const dueDateInput = document.getElementById('due_date');
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            dueDateInput.min = now.toISOString().slice(0, 16);
            
            // Set default due date to tomorrow at 9 AM if not set
            if (!dueDateInput.value) {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                tomorrow.setHours(9, 0, 0, 0);
                tomorrow.setMinutes(tomorrow.getMinutes() - tomorrow.getTimezoneOffset());
                dueDateInput.value = tomorrow.toISOString().slice(0, 16);
            }
            
            updateStaffInfo();
        });

        // Form validation
        document.getElementById('taskForm').addEventListener('submit', function(e) {
            const staffId = document.getElementById('staff_id').value;
            const taskDescription = document.getElementById('task_description').value.trim();
            const dueDate = document.getElementById('due_date').value;
            
            if (!staffId) {
                alert('Please select a staff member to assign the task to.');
                e.preventDefault();
                return;
            }
            
            if (!taskDescription) {
                alert('Please provide a task description.');
                e.preventDefault();
                return;
            }
            
            if (!dueDate) {
                alert('Please set a due date for the task.');
                e.preventDefault();
                return;
            }
            
            if (new Date(dueDate) <= new Date()) {
                alert('Due date must be in the future.');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>