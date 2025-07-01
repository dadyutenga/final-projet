
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Hotel Management System</title>
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
                padding-top: 4rem;
            }
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        }

        .btn-secondary {
            background: var(--text-gray);
            color: var(--white);
        }

        .btn-success {
            background: var(--success-color);
            color: var(--white);
        }

        .btn-danger {
            background: var(--danger-color);
            color: var(--white);
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .profile-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: var(--primary-color);
            color: var(--white);
            padding: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        .profile-info {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 3rem;
            color: var(--primary-dark);
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }

        .profile-role {
            font-size: 1rem;
            color: var(--text-gray);
            margin-bottom: 0.5rem;
        }

        .profile-hotel {
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            background: var(--light-gray);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-gray);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--dark-gray);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(50, 205, 50, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .form-control:disabled {
            background: var(--light-gray);
            color: var(--text-gray);
        }

        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.8rem;
            margin-top: 0.25rem;
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

        .divider {
            height: 1px;
            background: var(--border-color);
            margin: 2rem 0;
        }

        .password-section {
            margin-top: 2rem;
        }

        .password-section h4 {
            color: var(--dark-gray);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: var(--text-gray);
        }

        .info-value {
            font-weight: 600;
            color: var(--dark-gray);
        }

        .activity-section {
            margin-top: 2rem;
        }

        .activity-item {
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background: var(--light-gray);
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .activity-time {
            color: var(--text-gray);
            font-size: 0.8rem;
        }



        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .btn-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

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

            .sidebar.collapsed {
                transform: translateX(0);
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
            <h2>
                <i class="fas fa-user-tie"></i>
                My Profile
            </h2>
            <a href="<?= base_url('manager/dashboard') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
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

        <?php if (session()->getFlashdata('password_success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= session()->getFlashdata('password_success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('password_error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <?= session()->getFlashdata('password_error') ?>
            </div>
        <?php endif; ?>

        <div class="profile-grid">
            <!-- Profile Overview -->
            <div class="profile-card">
                <div class="card-header">
                    <i class="fas fa-user-tie"></i>
                    Manager Overview
                </div>
                <div class="card-body">
                    <div class="profile-info">
                        <div class="profile-avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="profile-name"><?= esc($manager['full_name']) ?></div>
                        <div class="profile-role">Hotel Manager</div>
                        <?php if (!empty($hotel)): ?>
                        <div class="profile-hotel"><?= esc($hotel['name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value" id="staffCount"><?= $manager['staff_count'] ?></div>
                            <div class="stat-label">Staff Members</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="totalTasks">-</div>
                            <div class="stat-label">Total Tasks</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="completedTasks">-</div>
                            <div class="stat-label">Completed</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="pendingTasks">-</div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="info-item">
                        <span class="info-label">Manager ID</span>
                        <span class="info-value">#<?= $manager['manager_id'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Username</span>
                        <span class="info-value"><?= esc($manager['username']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= esc($manager['email']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Created</span>
                        <span class="info-value"><?= date('M d, Y', strtotime($manager['created_at'])) ?></span>
                    </div>

                    <?php if (!empty($hotel)): ?>
                    <div class="activity-section">
                        <h4><i class="fas fa-hotel"></i> Hotel Information</h4>
                        <div class="info-item">
                            <span class="info-label">Hotel Name</span>
                            <span class="info-value"><?= esc($hotel['name']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Location</span>
                            <span class="info-value"><?= esc($hotel['address']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone</span>
                            <span class="info-value"><?= esc($hotel['phone']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Edit Profile -->
            <div class="profile-card">
                <div class="card-header">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </div>
                <div class="card-body">
                    <form action="<?= base_url('manager/profile/update') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="full_name" class="form-label">Full Name <span style="color: var(--danger-color);">*</span></label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" 
                                       id="full_name" name="full_name" 
                                       value="<?= old('full_name', $manager['full_name']) ?>" 
                                       required>
                                <?php if (isset($errors['full_name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['full_name'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= esc($manager['username']) ?>" disabled>
                                <small style="color: var(--text-gray);">Username cannot be changed</small>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" 
                                       value="<?= old('email', $manager['email']) ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                       id="phone" name="phone" 
                                       value="<?= old('phone', $manager['phone']) ?>">
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback"><?= $errors['phone'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="btn-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i>
                                Update Profile
                            </button>
                        </div>
                    </form>

                    <!-- Change Password Section -->
                    <div class="password-section">
                        <h4>
                            <i class="fas fa-lock"></i>
                            Change Password
                        </h4>

                        <?php if (session()->getFlashdata('password_errors')): ?>
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <?php foreach (session()->getFlashdata('password_errors') as $error): ?>
                                        <div><?= esc($error) ?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('manager/profile/change-password') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <div class="form-group">
                                <label for="current_password" class="form-label">Current Password <span style="color: var(--danger-color);">*</span></label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="new_password" class="form-label">New Password <span style="color: var(--danger-color);">*</span></label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" 
                                           minlength="8" required>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password" class="form-label">Confirm New Password <span style="color: var(--danger-color);">*</span></label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           minlength="8" required>
                                </div>
                            </div>

                            <div class="btn-actions">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-key"></i>
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

        // Load manager statistics
        function loadStats() {
            fetch('<?= base_url('manager/profile/stats') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('totalTasks').textContent = data.stats.total_tasks || 0;
                        document.getElementById('completedTasks').textContent = data.stats.completed_tasks || 0;
                        document.getElementById('pendingTasks').textContent = data.stats.pending_tasks || 0;
                    }
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                    // Set default values if stats fail to load
                    document.getElementById('totalTasks').textContent = '0';
                    document.getElementById('completedTasks').textContent = '0';
                    document.getElementById('pendingTasks').textContent = '0';
                });
        }

        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (newPassword !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });

        // Load stats on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
        });
    </script>
</body>
</html>