<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Hotel - Hotel Management System</title>
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

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
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
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
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

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .logo-preview {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            border: 2px dashed var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            overflow: hidden;
            background: var(--light-gray);
        }

        .logo-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .logo-preview.empty i {
            font-size: 2rem;
            color: var(--text-gray);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .form-card {
                margin: 1rem;
            }
        }

        /* Sidebar Styles */
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

            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <?= $this->include('admin/shared/sidebar') ?>

    <div class="main-content">
        <div class="form-card">
            <h2 class="mb-4">Create New Hotel</h2>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/hotels/create') ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name" class="form-label">Hotel Name</label>
                    <input type="text" 
                           class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" 
                           id="name" 
                           name="name" 
                           value="<?= old('name') ?>" 
                           required>
                    <?php if (session('errors.name')): ?>
                        <div class="error-feedback">
                            <?= session('errors.name') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="manager_id" class="form-label">Manager</label>
                    <select class="form-control <?= session('errors.manager_id') ? 'is-invalid' : '' ?>" 
                            id="manager_id" 
                            name="manager_id" 
                            required>
                        <option value="">Select Manager</option>
                        <?php foreach ($managers as $manager): ?>
                            <option value="<?= $manager['manager_id'] ?>" 
                                    <?= old('manager_id') == $manager['manager_id'] ? 'selected' : '' ?>>
                                <?= esc($manager['full_name']) ?> (<?= esc($manager['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (session('errors.manager_id')): ?>
                        <div class="error-feedback">
                            <?= session('errors.manager_id') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="hotel_logo" class="form-label">Hotel Logo</label>
                    <div class="logo-preview empty" id="logoPreview">
                        <i class="fas fa-image"></i>
                    </div>
                    <input type="file" 
                           class="form-control <?= session('errors.hotel_logo') ? 'is-invalid' : '' ?>" 
                           id="hotel_logo" 
                           name="hotel_logo" 
                           accept="image/*"
                           required>
                    <?php if (session('errors.hotel_logo')): ?>
                        <div class="error-feedback">
                            <?= session('errors.hotel_logo') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control <?= session('errors.address') ? 'is-invalid' : '' ?>" 
                              id="address" 
                              name="address" 
                              required><?= old('address') ?></textarea>
                    <?php if (session('errors.address')): ?>
                        <div class="error-feedback">
                            <?= session('errors.address') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="city" class="form-label">City</label>
                    <input type="text" 
                           class="form-control <?= session('errors.city') ? 'is-invalid' : '' ?>" 
                           id="city" 
                           name="city" 
                           value="<?= old('city') ?>" 
                           required>
                    <?php if (session('errors.city')): ?>
                        <div class="error-feedback">
                            <?= session('errors.city') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" 
                           class="form-control <?= session('errors.country') ? 'is-invalid' : '' ?>" 
                           id="country" 
                           name="country" 
                           value="<?= old('country') ?>" 
                           required>
                    <?php if (session('errors.country')): ?>
                        <div class="error-feedback">
                            <?= session('errors.country') ?>
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
                    <label for="email" class="form-label">Email</label>
                    <input type="email" 
                           class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                           id="email" 
                           name="email" 
                           value="<?= old('email') ?>">
                    <?php if (session('errors.email')): ?>
                        <div class="error-feedback">
                            <?= session('errors.email') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Hotel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Logo preview functionality
        document.getElementById('hotel_logo').addEventListener('change', function(e) {
            const preview = document.getElementById('logoPreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
                    preview.classList.remove('empty');
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<i class="fas fa-image"></i>';
                preview.classList.add('empty');
            }
        });
    </script>
</body>
</html>
