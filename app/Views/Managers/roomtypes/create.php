<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Room Type - Hotel Management System</title>
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

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
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

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            background-color: var(--white);
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(50, 205, 50, 0.1);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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

        .room-type-preview {
            background: var(--light-gray);
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .preview-header {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }

        .preview-content {
            color: var(--text-gray);
            font-size: 0.9rem;
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
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-logo {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
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
        }

        .nav-item:hover {
            background: var(--light-gray);
            color: var(--primary-color);
        }

        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary-dark);
            border-right: 3px solid var(--primary-color);
        }

        .nav-item i {
            width: 20px;
            margin-right: 0.75rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <?= $this->include('managers/shared/sidebar') ?>

    <div class="main-content">
        <div class="page-header">
            <h2><i class="fas fa-bed"></i> Create New Room Type</h2>
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
            
            <form action="<?= base_url('manager/roomtypes/store') ?>" method="post" id="roomTypeForm">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="type_name" class="form-label">Room Type <span class="required">*</span></label>
                    <select class="form-select" id="type_name" name="type_name" required onchange="updateDescription()">
                        <option value="">Select Room Type</option>
                        <option value="Presidential Suite" <?= old('type_name') == 'Presidential Suite' ? 'selected' : '' ?>>Presidential Suite</option>
                        <option value="Executive Suite" <?= old('type_name') == 'Executive Suite' ? 'selected' : '' ?>>Executive Suite</option>
                        <option value="Deluxe King Room" <?= old('type_name') == 'Deluxe King Room' ? 'selected' : '' ?>>Deluxe King Room</option>
                        <option value="Standard Double" <?= old('type_name') == 'Standard Double' ? 'selected' : '' ?>>Standard Double</option>
                        <option value="Junior Suite" <?= old('type_name') == 'Junior Suite' ? 'selected' : '' ?>>Junior Suite</option>
                        <option value="Family Room" <?= old('type_name') == 'Family Room' ? 'selected' : '' ?>>Family Room</option>
                        <option value="Single Room" <?= old('type_name') == 'Single Room' ? 'selected' : '' ?>>Single Room</option>
                        <option value="Twin Room" <?= old('type_name') == 'Twin Room' ? 'selected' : '' ?>>Twin Room</option>
                        <option value="Penthouse Suite" <?= old('type_name') == 'Penthouse Suite' ? 'selected' : '' ?>>Penthouse Suite</option>
                        <option value="Ocean View Room" <?= old('type_name') == 'Ocean View Room' ? 'selected' : '' ?>>Ocean View Room</option>
                        <option value="Custom" <?= old('type_name') == 'Custom' ? 'selected' : '' ?>>Custom (Enter your own)</option>
                    </select>
                </div>

                <div class="form-group" id="customTypeGroup" style="display: none;">
                    <label for="custom_type_name" class="form-label">Custom Room Type Name <span class="required">*</span></label>
                    <input type="text" class="form-control" id="custom_type_name" name="custom_type_name" 
                           value="<?= old('custom_type_name') ?>">
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"><?= old('description') ?></textarea>
                    <div class="form-text">Describe the amenities and features of this room type</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="base_price" class="form-label">Base Price per Night (Tzs) <span class="required">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="base_price" name="base_price" 
                               value="<?= old('base_price') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="capacity" class="form-label">Maximum Capacity <span class="required">*</span></label>
                        <select class="form-select" id="capacity" name="capacity" required>
                            <option value="">Select Capacity</option>
                            <option value="1" <?= old('capacity') == '1' ? 'selected' : '' ?>>1 Guest</option>
                            <option value="2" <?= old('capacity') == '2' ? 'selected' : '' ?>>2 Guests</option>
                            <option value="3" <?= old('capacity') == '3' ? 'selected' : '' ?>>3 Guests</option>
                            <option value="4" <?= old('capacity') == '4' ? 'selected' : '' ?>>4 Guests</option>
                            <option value="5" <?= old('capacity') == '5' ? 'selected' : '' ?>>5 Guests</option>
                            <option value="6" <?= old('capacity') == '6' ? 'selected' : '' ?>>6 Guests</option>
                            <option value="8" <?= old('capacity') == '8' ? 'selected' : '' ?>>8 Guests</option>
                            <option value="10" <?= old('capacity') == '10' ? 'selected' : '' ?>>10 Guests</option>
                        </select>
                    </div>
                </div>

                <div class="room-type-preview" id="preview" style="display: none;">
                    <div class="preview-header">Preview:</div>
                    <div class="preview-content" id="previewContent"></div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Room Type
                    </button>
                    <a href="<?= base_url('manager/roomtypes') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Predefined descriptions for room types
        const roomTypeDescriptions = {
            'Presidential Suite': 'Luxurious suite with separate living area, premium amenities, butler service, and panoramic views. Perfect for VIP guests.',
            'Executive Suite': 'Spacious suite with work area, premium amenities, and enhanced services. Ideal for business travelers.',
            'Deluxe King Room': 'Elegant room with king-size bed, premium furnishings, and upgraded amenities. Perfect for couples.',
            'Standard Double': 'Comfortable room with double bed, essential amenities, and modern furnishings. Great value for money.',
            'Junior Suite': 'Compact suite with sitting area, modern amenities, and stylish decor. Perfect for extended stays.',
            'Family Room': 'Spacious room designed for families with multiple beds and family-friendly amenities.',
            'Single Room': 'Cozy room perfect for solo travelers with single bed and essential amenities.',
            'Twin Room': 'Room with two single beds, ideal for friends or colleagues traveling together.',
            'Penthouse Suite': 'Ultimate luxury with private terrace, premium services, and exclusive amenities.',
            'Ocean View Room': 'Room with stunning ocean views, premium location, and enhanced amenities.'
        };

        const roomTypeCapacities = {
            'Presidential Suite': '4',
            'Executive Suite': '3',
            'Deluxe King Room': '2',
            'Standard Double': '2',
            'Junior Suite': '2',
            'Family Room': '4',
            'Single Room': '1',
            'Twin Room': '2',
            'Penthouse Suite': '6',
            'Ocean View Room': '2'
        };

        const roomTypePrices = {
            'Presidential Suite': '1500.00',
            'Executive Suite': '800.00',
            'Deluxe King Room': '300.00',
            'Standard Double': '150.00',
            'Junior Suite': '250.00',
            'Family Room': '350.00',
            'Single Room': '100.00',
            'Twin Room': '180.00',
            'Penthouse Suite': '2500.00',
            'Ocean View Room': '400.00'
        };

        function updateDescription() {
            const typeSelect = document.getElementById('type_name');
            const descriptionField = document.getElementById('description');
            const capacityField = document.getElementById('capacity');
            const priceField = document.getElementById('base_price');
            const customGroup = document.getElementById('customTypeGroup');
            const preview = document.getElementById('preview');
            const previewContent = document.getElementById('previewContent');
            
            const selectedType = typeSelect.value;
            
            if (selectedType === 'Custom') {
                customGroup.style.display = 'block';
                descriptionField.value = '';
                capacityField.value = '';
                priceField.value = '';
                preview.style.display = 'none';
            } else if (selectedType && roomTypeDescriptions[selectedType]) {
                customGroup.style.display = 'none';
                descriptionField.value = roomTypeDescriptions[selectedType];
                capacityField.value = roomTypeCapacities[selectedType] || '';
                priceField.value = roomTypePrices[selectedType] || '';
                
                // Show preview
                preview.style.display = 'block';
                previewContent.innerHTML = `
                    <strong>${selectedType}</strong><br>
                    <small>Capacity: ${roomTypeCapacities[selectedType]} guests | Price: $${roomTypePrices[selectedType]}/night</small><br>
                    ${roomTypeDescriptions[selectedType]}
                `;
            } else {
                customGroup.style.display = 'none';
                preview.style.display = 'none';
            }
        }

        // Handle form submission for custom types
        document.getElementById('roomTypeForm').addEventListener('submit', function(e) {
            const typeSelect = document.getElementById('type_name');
            const customInput = document.getElementById('custom_type_name');
            
            if (typeSelect.value === 'Custom') {
                if (!customInput.value.trim()) {
                    e.preventDefault();
                    alert('Please enter a custom room type name.');
                    customInput.focus();
                    return;
                }
                // Set the custom name as the type_name value
                typeSelect.value = customInput.value.trim();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateDescription();
        });
    </script>
</body>
</html>