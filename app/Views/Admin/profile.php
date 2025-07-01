<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Hotel Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #32CD32;
            --primary-dark: #228B22;
            --primary-light: #dbeafe;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --white: #ffffff;
            --light-gray: #f8fafc;
            --dark-gray: #1e293b;
            --text-gray: #64748b;
            --border-color: #e2e8f0;
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
            background: var(--secondary-color);
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
            color: var(--primary-color);
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

        .profile-email {
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

        .system-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .system-stat {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }

        .system-stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .system-stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
            border: 1px solid rgba(239, 68, 68, 0.2);
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

        .admin-badge {
            background: var(--primary-color);
            color: var(--white);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
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

            .system-stats {
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
    
    <?= $this->include('admin/shared/sidebar') ?>

    <div class="main-content">
        <div class="page-header">
            <h2>
                <i class="fas fa-user-shield"></i>
                Admin Profile
            </h2>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">
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

        <!-- System Overview Stats -->
        <div class="system-stats">
            <div class="system-stat">
                <div class="system-stat-value" id="totalHotels">-</div>
                <div class="system-stat-label">Total Hotels</div>
            </div>
            <div class="system-stat">
                <div class="system-stat-value" id="totalManagers">-</div>
                <div class="system-stat-label">Total Managers</div>
            </div>
            <div class="system-stat">
                <div class="system-stat-value" id="totalAdmins">-</div>
                <div class="system-stat-label">Total Admins</div>
            </div>
        </div>

        <div class="profile-grid">
            <!-- Profile Overview -->
            <div class="profile-card">
                <div class="card-header">
                    <i class="fas fa-user-shield"></i>
                    Admin Overview
                </div>
                <div class="card-body">
                    <div class="profile-info">
                        <div class="profile-avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="profile-name"><?= esc($admin['full_name']) ?></div>
                        <div class="profile-role">
                            <span class="admin-badge">System Administrator</span>
                        </div>
                        <div class="profile-email"><?= esc($admin['email']) ?></div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value"><?= $admin['hotel_count'] ?></div>
                            <div class="stat-label">Managed Hotels</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="managerCount">-</div>
                            <div class="stat-label">Managers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="systemHealth">100%</div>
                            <div class="stat-label">System Health</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="activeUsers">-</div>
                            <div class="stat-label">Active Users</div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="info-item">
                        <span class="info-label">Admin ID</span>
                        <span class="info-value">#<?= $admin['admin_id'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Username</span>
                        <span class="info-value"><?= esc($admin['username']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= esc($admin['email']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Created</span>
                        <span class="info-value"><?= date('M d, Y', strtotime($admin['created_at'])) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Updated</span>
                        <span class="info-value"><?= date('M d, Y', strtotime($admin['updated_at'])) ?></span>
                    </div>
                </div>
            </div>

            <!-- Edit Profile -->
            <div class="profile-card">
                <div class="card-header">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/profile/update') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="full_name" class="form-label">Full Name <span style="color: var(--danger-color);">*</span></label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" 
                                       id="full_name" name="full_name" 
                                       value="<?= old('full_name', $admin['full_name']) ?>" 
                                       required>
                                <?php if (isset($errors['full_name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['full_name'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="username" class="form-label">Username <span style="color: var(--danger-color);">*</span></label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                                       id="username" name="username" 
                                       value="<?= old('username', $admin['username']) ?>" 
                                       required>
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback"><?= $errors['username'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email <span style="color: var(--danger-color);">*</span></label>
                            <input type="email" 
                                   class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" 
                                   value="<?= old('email', $admin['email']) ?>" 
                                   required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
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

                        <form action="<?= base_url('admin/profile/change-password') ?>" method="POST">
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

        // Load admin statistics
        function loadStats() {
            fetch('<?= base_url('admin/profile/stats') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('managerCount').textContent = data.stats.total_managers || 0;
                        document.getElementById('activeUsers').textContent = (data.stats.total_managers + data.stats.total_admins) || 0;
                    }
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                    document.getElementById('managerCount').textContent = '0';
                    document.getElementById('activeUsers').textContent = '0';
                });
        }

        // Load system statistics
        function loadSystemStats() {
            fetch('<?= base_url('admin/profile/system-stats') ?>')
                .then(response => response.json())
                .then data => {
                    if (data.success) {
                        document.getElementById('totalHotels').textContent = data.stats.totals.hotels || 0;
                        document.getElementById('totalManagers').textContent = data.stats.totals.managers || 0;
                        document.getElementById('totalAdmins').textContent = data.stats.totals.admins || 0;
                    }
                })
                .catch(error => {
                    console.error('Error loading system stats:', error);
                    document.getElementById('totalHotels').textContent = '0';
                    document.getElementById('totalManagers').textContent = '0';
                    document.getElementById('totalAdmins').textContent = '0';
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
            loadSystemStats();
        });
    </script>
</body>
</html>