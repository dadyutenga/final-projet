<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Manager - Hotel Management System</title>
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

        .form-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            max-width: 600px;
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

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
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
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .error-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
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
    <?= $this->include('admin/shared/sidebar') ?>

    <div class="main-content">
        <div class="form-card">
            <h2 class="mb-4">Create New Manager</h2>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/managers/create') ?>" method="post">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" 
                           class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" 
                           id="username" 
                           name="username" 
                           value="<?= old('username') ?>" 
                           required>
                    <?php if (session('errors.username')): ?>
                        <div class="error-feedback">
                            <?= session('errors.username') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" 
                           class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                           id="email" 
                           name="email" 
                           value="<?= old('email') ?>" 
                           required>
                    <?php if (session('errors.email')): ?>
                        <div class="error-feedback">
                            <?= session('errors.email') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" 
                           class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                           id="password" 
                           name="password" 
                           required>
                    <?php if (session('errors.password')): ?>
                        <div class="error-feedback">
                            <?= session('errors.password') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password_confirm" class="form-label">Confirm Password</label>
                    <input type="password" 
                           class="form-control <?= session('errors.password_confirm') ? 'is-invalid' : '' ?>" 
                           id="password_confirm" 
                           name="password_confirm" 
                           required>
                    <?php if (session('errors.password_confirm')): ?>
                        <div class="error-feedback">
                            <?= session('errors.password_confirm') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" 
                           class="form-control <?= session('errors.full_name') ? 'is-invalid' : '' ?>" 
                           id="full_name" 
                           name="full_name" 
                           value="<?= old('full_name') ?>" 
                           required>
                    <?php if (session('errors.full_name')): ?>
                        <div class="error-feedback">
                            <?= session('errors.full_name') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" 
                           class="form-control <?= session('errors.phone') ? 'is-invalid' : '' ?>" 
                           id="phone" 
                           name="phone" 
                           value="<?= old('phone') ?>">
                    <?php if (session('errors.phone')): ?>
                        <div class="error-feedback">
                            <?= session('errors.phone') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Manager
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
