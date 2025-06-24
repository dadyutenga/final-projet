<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - Hotel Management System</title>
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
            --danger-color: #dc3545;
            --success-color: #28a745;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            position: relative;
        }

        .login-header {
            background: var(--primary-color);
            color: var(--white);
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="80" r="2.5" fill="rgba(255,255,255,0.1)"/></svg>');
        }

        .login-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .login-header p {
            font-size: 0.9rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .login-header .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .login-form {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
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
            padding-left: 3rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(50, 205, 50, 0.1);
        }

        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-gray);
            z-index: 2;
        }

        .form-group.has-label .form-icon {
            top: calc(50% + 0.75rem);
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .alert ul {
            margin: 0;
            padding-left: 1rem;
        }

        .login-footer {
            text-align: center;
            padding: 1rem 2rem 2rem;
            font-size: 0.9rem;
            color: var(--text-gray);
        }

        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-gray);
            cursor: pointer;
            z-index: 2;
        }

        .form-group.has-label .password-toggle {
            top: calc(50% + 0.75rem);
        }

        .login-links {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            font-size: 0.85rem;
        }

        .login-links a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .login-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 0;
                border-radius: 0;
            }

            .login-header {
                padding: 1.5rem;
            }

            .login-form {
                padding: 1.5rem;
            }
        }

        /* Loading animation */
        .btn-loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-loading::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <h1>Staff Login</h1>
            <p>Access your hotel management dashboard</p>
        </div>

        <div class="login-form">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= session()->getFlashdata('success') ?>
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

            <form action="<?= base_url('staff/login') ?>" method="post" id="loginForm">
                <?= csrf_field() ?>
                
                <div class="form-group has-label">
                    <label for="username" class="form-label">Username</label>
                    <div style="position: relative;">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               value="<?= old('username') ?>" 
                               required 
                               placeholder="Enter your username"
                               autocomplete="username">
                    </div>
                </div>

                <div class="form-group has-label">
                    <label for="password" class="form-label">Password</label>
                    <div style="position: relative;">
                        <i class="fas fa-lock form-icon"></i>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="Enter your password"
                               autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>

                <div class="login-links">
                    <a href="<?= base_url('/') ?>">← Back to Home</a>
                    <a href="<?= base_url('manager/login') ?>">Manager Login</a>
                </div>
            </form>
        </div>

        <div class="login-footer">
            <p>© <?= date('Y') ?> Hotel Management System. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Form submission handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            
            // Add loading state
            loginBtn.classList.add('btn-loading');
            loginBtn.innerHTML = '<span>Signing In...</span>';
            loginBtn.disabled = true;
        });

        // Auto-focus on username field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });

        // Enter key handling
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').submit();
            }
        });
    </script>
</body>
</html>