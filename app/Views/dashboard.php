<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeStay Hotel - Manager Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #32CD32;
            --primary-dark: #228B22;
            --primary-light: #90EE90;
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

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-gray);
            color: var(--dark-gray);
            overflow-x: hidden;
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
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }

        .sidebar-logo {
            font-size: 1.8rem;
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
            padding: 0 1.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-gray);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            color: var(--dark-gray);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: var(--light-gray);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary-dark);
            border-left-color: var(--primary-color);
            font-weight: 500;
        }

        .nav-item i {
            width: 20px;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--primary-color);
            color: var(--white);
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Header */
        .header {
            background: var(--white);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-gray);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .menu-toggle:hover {
            background: var(--light-gray);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--text-gray);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            background: var(--light-gray);
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--danger);
            color: var(--white);
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .user-profile:hover {
            background: var(--light-gray);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
        }

        .user-info h4 {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .user-info p {
            font-size: 0.8rem;
            color: var(--text-gray);
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 2rem;
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

        .stat-title {
            font-size: 0.9rem;
            color: var(--text-gray);
            font-weight: 500;
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
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }

        .stat-change {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* Charts and Tables */
        .dashboard-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card,
        .table-card {
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

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
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

        .btn-outline {
            background: transparent;
            color: var(--text-gray);
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            background: var(--light-gray);
        }

        .chart-container {
            padding: 1.5rem;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
            color: var(--white);
            font-size: 1.1rem;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
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
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        .table td {
            font-size: 0.9rem;
        }

        .table tbody tr:hover {
            background: var(--light-gray);
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-confirmed {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-cancelled {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        /* Room Management */
        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .room-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-5px);
        }

        .room-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .room-status {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .room-status.available {
            background: var(--success);
            color: var(--white);
        }

        .room-status.occupied {
            background: var(--danger);
            color: var(--white);
        }

        .room-status.maintenance {
            background: var(--warning);
            color: var(--dark-gray);
        }

        .room-info {
            padding: 1.5rem;
        }

        .room-number {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }

        .room-type {
            color: var(--text-gray);
            margin-bottom: 1rem;
        }

        .room-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .room-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .dashboard-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                padding: 1rem;
            }

            .dashboard-content {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .user-info {
                display: none;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--white);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Hide content sections by default */
        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">LuxeStay</div>
            <div class="sidebar-subtitle">Manager Dashboard</div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="#" class="nav-item active" data-section="dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-item" data-section="bookings">
                    <i class="fas fa-calendar-check"></i>
                    <span>Bookings</span>
                    <span class="nav-badge">12</span>
                </a>
                <a href="#" class="nav-item" data-section="rooms">
                    <i class="fas fa-bed"></i>
                    <span>Rooms</span>
                </a>
                <a href="#" class="nav-item" data-section="guests">
                    <i class="fas fa-users"></i>
                    <span>Guests</span>
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Management</div>
                <a href="#" class="nav-item" data-section="staff">
                    <i class="fas fa-user-tie"></i>
                    <span>Staff</span>
                </a>
                <a href="#" class="nav-item" data-section="housekeeping">
                    <i class="fas fa-broom"></i>
                    <span>Housekeeping</span>
                    <span class="nav-badge">3</span>
                </a>
                <a href="#" class="nav-item" data-section="maintenance">
                    <i class="fas fa-tools"></i>
                    <span>Maintenance</span>
                </a>
                <a href="#" class="nav-item" data-section="reports">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Settings</div>
                <a href="#" class="nav-item" data-section="settings">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title" id="pageTitle">Dashboard Overview</h1>
            </div>
            
            <div class="header-right">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>
                </button>
                
                <div class="user-profile">
                    <div class="user-avatar">JD</div>
                    <div class="user-info">
                        <h4>John Doe</h4>
                        <p>Hotel Manager</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content">
            <!-- Dashboard Overview Section -->
            <div class="content-section active" id="dashboard">
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Bookings</div>
                            <div class="stat-icon primary">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="stat-value">247</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+12% from last month</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Occupancy Rate</div>
                            <div class="stat-icon success">
                                <i class="fas fa-percentage"></i>
                            </div>
                        </div>
                        <div class="stat-value">87%</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+5% from last week</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Revenue</div>
                            <div class="stat-icon warning">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="stat-value">$45,230</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+18% from last month</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Available Rooms</div>
                            <div class="stat-icon info">
                                <i class="fas fa-bed"></i>
                            </div>
                        </div>
                        <div class="stat-value">23</div>
                        <div class="stat-change negative">
                            <i class="fas fa-arrow-down"></i>
                            <span>-3 from yesterday</span>
                        </div>
                    </div>
                </div>

                <!-- Charts and Recent Activity -->
                <div class="dashboard-row">
                    <div class="chart-card">
                        <div class="card-header">
                            <h3 class="card-title">Revenue Analytics</h3>
                            <div class="card-actions">
                                <button class="btn btn-outline">
                                    <i class="fas fa-download"></i>
                                    Export
                                </button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <i class="fas fa-chart-line" style="font-size: 3rem; margin-right: 1rem;"></i>
                            <div>
                                <h4>Revenue Chart</h4>
                                <p>Interactive chart would be displayed here</p>
                            </div>
                        </div>
                    </div>

                    <div class="table-card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Bookings</h3>
                            <a href="#" class="btn btn-primary">View All</a>
                        </div>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Guest</th>
                                        <th>Room</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Sarah Johnson</td>
                                        <td>101</td>
                                        <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                    </tr>
                                    <tr>
                                        <td>Michael Chen</td>
                                        <td>205</td>
                                        <td><span class="status-badge status-pending">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td>Emily Rodriguez</td>
                                        <td>301</td>
                                        <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                    </tr>
                                    <tr>
                                        <td>David Wilson</td>
                                        <td>150</td>
                                        <td><span class="status-badge status-cancelled">Cancelled</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Section -->
            <div class="content-section" id="bookings">
                <div class="card-header" style="background: var(--white); margin-bottom: 2rem; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <h3 class="card-title">Booking Management</h3>
                    <div class="card-actions">
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            New Booking
                        </button>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Guest Name</th>
                                    <th>Room</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#BK001</td>
                                    <td>Sarah Johnson</td>
                                    <td>Deluxe King - 101</td>
                                    <td>2024-01-15</td>
                                    <td>2024-01-18</td>
                                    <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                    <td>$597</td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#BK002</td>
                                    <td>Michael Chen</td>
                                    <td>Executive Suite - 205</td>
                                    <td>2024-01-16</td>
                                    <td>2024-01-20</td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td>$1,196</td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#BK003</td>
                                    <td>Emily Rodriguez</td>
                                    <td>Presidential Suite - 301</td>
                                    <td>2024-01-17</td>
                                    <td>2024-01-21</td>
                                    <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                    <td>$1,596</td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Rooms Section -->
            <div class="content-section" id="rooms">
                <div class="card-header" style="background: var(--white); margin-bottom: 2rem; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <h3 class="card-title">Room Management</h3>
                    <div class="card-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Room
                        </button>
                    </div>
                </div>

                <div class="room-grid">
                    <div class="room-card">
                        <div class="room-image" style="background-image: url('https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                            <div class="room-status available">Available</div>
                        </div>
                        <div class="room-info">
                            <div class="room-number">Room 101</div>
                            <div class="room-type">Deluxe King Room</div>
                            <div class="room-details">
                                <div class="room-price">$199/night</div>
                                <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                                    Manage
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="room-card">
                        <div class="room-image" style="background-image: url('https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                            <div class="room-status occupied">Occupied</div>
                        </div>
                        <div class="room-info">
                            <div class="room-number">Room 205</div>
                            <div class="room-type">Executive Suite</div>
                            <div class="room-details">
                                <div class="room-price">$299/night</div>
                                <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                                    Manage
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="room-card">
                        <div class="room-image" style="background-image: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                            <div class="room-status maintenance">Maintenance</div>
                        </div>
                        <div class="room-info">
                            <div class="room-number">Room 150</div>
                            <div class="room-type">Standard Double</div>
                            <div class="room-details">
                                <div class="room-price">$149/night</div>
                                <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                                    Manage
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="room-card">
                        <div class="room-image" style="background-image: url('https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80');">
                            <div class="room-status available">Available</div>
                        </div>
                        <div class="room-info">
                            <div class="room-number">Room 301</div>
                            <div class="room-type">Presidential Suite</div>
                            <div class="room-details">
                                <div class="room-price">$399/night</div>
                                <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                                    Manage
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guests Section -->
            <div class="content-section" id="guests">
                <div class="card-header" style="background: var(--white); margin-bottom: 2rem; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <h3 class="card-title">Guest Management</h3>
                    <div class="card-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-search"></i>
                            Search
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-user-plus"></i>
                            Add Guest
                        </button>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Guest Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Room</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sarah Johnson</td>
                                    <td>sarah.j@email.com</td>
                                    <td>+1 (555) 123-4567</td>
                                    <td>101</td>
                                    <td>2024-01-15</td>
                                    <td>2024-01-18</td>
                                    <td><span class="status-badge status-confirmed">Checked In</span></td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Michael Chen</td>
                                    <td>m.chen@email.com</td>
                                    <td>+1 (555) 987-6543</td>
                                    <td>205</td>
                                    <td>2024-01-16</td>
                                    <td>2024-01-20</td>
                                    <td><span class="status-badge status-pending">Arriving Today</span></td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Emily Rodriguez</td>
                                    <td>emily.r@email.com</td>
                                    <td>+1 (555) 456-7890</td>
                                    <td>301</td>
                                    <td>2024-01-17</td>
                                    <td>2024-01-21</td>
                                    <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Staff Section -->
            <div class="content-section" id="staff">
                <div class="card-header" style="background: var(--white); margin-bottom: 2rem; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <h3 class="card-title">Staff Management</h3>
                    <div class="card-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-calendar"></i>
                            Schedule
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-user-plus"></i>
                            Add Staff
                        </button>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Staff</div>
                            <div class="stat-icon primary">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stat-value">24</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>2 new hires this month</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">On Duty</div>
                            <div class="stat-icon success">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="stat-value">18</div>
                        <div class="stat-change positive">
                            <i class="fas fa-clock"></i>
                            <span>Current shift</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Off Duty</div>
                            <div class="stat-icon warning">
                                <i class="fas fa-user-clock"></i>
                            </div>
                        </div>
                        <div class="stat-value">6</div>
                        <div class="stat-change">
                            <i class="fas fa-home"></i>
                            <span>Rest day/vacation</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Housekeeping Section -->
            <div class="content-section" id="housekeeping">
                <div class="card-header" style="background: var(--white); margin-bottom: 2rem; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <h3 class="card-title">Housekeeping Tasks</h3>
                    <div class="card-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            New Task
                        </button>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Task Type</th>
                                    <th>Assigned To</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Room 102</td>
                                    <td>Checkout Cleaning</td>
                                    <td>Maria Santos</td>
                                    <td><span class="status-badge status-confirmed">High</span></td>
                                    <td><span class="status-badge status-pending">In Progress</span></td>
                                    <td>11:00 AM</td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Room 205</td>
                                    <td>Maintenance Check</td>
                                    <td>John Smith</td>
                                    <td><span class="status-badge status-pending">Medium</span></td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td>2:00 PM</td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Room 301</td>
                                    <td>Deep Cleaning</td>
                                    <td>Lisa Johnson</td>
                                    <td><span class="status-badge status-confirmed">High</span></td>
                                    <td><span class="status-badge status-confirmed">Completed</span></td>
                                    <td>9:00 AM</td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Other sections would follow similar patterns -->
            <div class="content-section" id="maintenance">
                <div style="text-align: center; padding: 3rem; background: var(--white); border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <i class="fas fa-tools" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>Maintenance Management</h3>
                    <p style="color: var(--text-gray);">Maintenance management features coming soon...</p>
                </div>
            </div>

            <div class="content-section" id="reports">
                <div style="text-align: center; padding: 3rem; background: var(--white); border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <i class="fas fa-chart-bar" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>Reports & Analytics</h3>
                    <p style="color: var(--text-gray);">Detailed reports and analytics coming soon...</p>
                </div>
            </div>

            <div class="content-section" id="settings">
                <div style="text-align: center; padding: 3rem; background: var(--white); border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <i class="fas fa-cog" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>System Settings</h3>
                    <p style="color: var(--text-gray);">System configuration and settings coming soon...</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Dashboard functionality
        class HotelDashboard {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.mainContent = document.getElementById('mainContent');
                this.menuToggle = document.getElementById('menuToggle');
                this.pageTitle = document.getElementById('pageTitle');
                this.navItems = document.querySelectorAll('.nav-item[data-section]');
                this.contentSections = document.querySelectorAll('.content-section');
                
                this.init();
            }
            
            init() {
                // Menu toggle functionality
                this.menuToggle.addEventListener('click', () => {
                    this.toggleSidebar();
                });
                
                // Navigation functionality
                this.navItems.forEach(item => {
                    item.addEventListener('click', (e) => {
                        e.preventDefault();
                        const section = item.getAttribute('data-section');
                        this.showSection(section);
                        this.setActiveNav(item);
                        
                        // Close sidebar on mobile after selection
                        if (window.innerWidth <= 768) {
                            this.closeSidebar();
                        }
                    });
                });
                
                // Handle responsive behavior
                this.handleResize();
                window.addEventListener('resize', () => {
                    this.handleResize();
                });
                
                // Initialize with dashboard section
                this.showSection('dashboard');
                
                // Auto-update stats (simulation)
                this.startStatsUpdate();
            }
            
            toggleSidebar() {
                if (window.innerWidth <= 768) {
                    this.sidebar.classList.toggle('open');
                } else {
                    this.sidebar.classList.toggle('collapsed');
                    this.mainContent.classList.toggle('expanded');
                }
            }
            
            closeSidebar() {
                this.sidebar.classList.remove('open');
            }
            
            showSection(sectionId) {
                // Hide all sections
                this.contentSections.forEach(section => {
                    section.classList.remove('active');
                });
                
                // Show selected section
                const targetSection = document.getElementById(sectionId);
                if (targetSection) {
                    targetSection.classList.add('active');
                }
                
                // Update page title
                this.updatePageTitle(sectionId);
            }
            
            setActiveNav(activeItem) {
                // Remove active class from all nav items
                this.navItems.forEach(item => {
                    item.classList.remove('active');
                });
                
                // Add active class to clicked item
                activeItem.classList.add('active');
            }
            
            updatePageTitle(sectionId) {
                const titles = {
                    'dashboard': 'Dashboard Overview',
                    'bookings': 'Booking Management',
                    'rooms': 'Room Management',
                    'guests': 'Guest Management',
                    'staff': 'Staff Management',
                    'housekeeping': 'Housekeeping Tasks',
                    'maintenance': 'Maintenance Management',
                    'reports': 'Reports & Analytics',
                    'settings': 'System Settings'
                };
                
                this.pageTitle.textContent = titles[sectionId] || 'Dashboard';
            }
            
            handleResize() {
                if (window.innerWidth <= 768) {
                    this.sidebar.classList.remove('collapsed');
                    this.mainContent.classList.remove('expanded');
                } else {
                    this.sidebar.classList.remove('open');
                }
            }
            
            startStatsUpdate() {
                // Simulate real-time stats updates
                setInterval(() => {
                    this.updateStats();
                }, 30000); // Update every 30 seconds
            }
            
            updateStats() {
                // Simulate random stat changes
                const statValues = document.querySelectorAll('.stat-value');
                statValues.forEach(stat => {
                    const currentValue = parseInt(stat.textContent.replace(/[^0-9]/g, ''));
                    if (currentValue && Math.random() > 0.7) {
                        const change = Math.floor(Math.random() * 5) - 2;
                        const newValue = Math.max(0, currentValue + change);
                        
                        if (stat.textContent.includes('%')) {
                            stat.textContent = newValue + '%';
                        } else if (stat.textContent.includes('$')) {
                            stat.textContent = '$' + newValue.toLocaleString();
                        } else {
                            stat.textContent = newValue.toString();
                        }
                    }
                });
            }
        }
        
        // Notification system
        class NotificationSystem {
            constructor() {
                this.notifications = [];
                this.notificationBtn = document.querySelector('.notification-btn');
                this.notificationBadge = document.querySelector('.notification-badge');
                
                this.init();
            }
            
            init() {
                this.notificationBtn.addEventListener('click', () => {
                    this.showNotifications();
                });
                
                // Simulate incoming notifications
                this.simulateNotifications();
            }
            
            addNotification(message, type = 'info') {
                const notification = {
                    id: Date.now(),
                    message,
                    type,
                    timestamp: new Date()
                };
                
                this.notifications.unshift(notification);
                this.updateBadge();
                
                // Auto-remove after 5 minutes
                setTimeout(() => {
                    this.removeNotification(notification.id);
                }, 300000);
            }
            
            removeNotification(id) {
                this.notifications = this.notifications.filter(n => n.id !== id);
                this.updateBadge();
            }
            
            updateBadge() {
                const count = this.notifications.length;
                this.notificationBadge.textContent = count;
                this.notificationBadge.style.display = count > 0 ? 'flex' : 'none';
            }
            
            showNotifications() {
                if (this.notifications.length === 0) {
                    alert('No new notifications');
                    return;
                }
                
                let message = 'Recent Notifications:\n\n';
                this.notifications.slice(0, 5).forEach((notification, index) => {
                    message += `${index + 1}. ${notification.message}\n`;
                });
                
                alert(message);
            }
            
            simulateNotifications() {
                const messages = [
                    'New booking received for Room 205',
                    'Housekeeping completed for Room 101',
                    'Maintenance request for Room 150',
                    'Guest checked out from Room 301',
                    'New guest review submitted'
                ];
                
                setInterval(() => {
                    if (Math.random() > 0.7) {
                        const randomMessage = messages[Math.floor(Math.random() * messages.length)];
                        this.addNotification(randomMessage);
                    }
                }, 45000); // Random notifications every 45 seconds
            }
        }
        
        // Form handling
        class FormHandler {
            constructor() {
                this.init();
            }
            
            init() {
                // Handle all form submissions
                document.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleFormSubmit(e.target);
                });
                
                // Handle button clicks
                document.addEventListener('click', (e) => {
                    if (e.target.matches('.btn-primary') || e.target.closest('.btn-primary')) {
                        this.handleButtonClick(e);
                    }
                });
            }
            
            handleFormSubmit(form) {
                const formData = new FormData(form);
                console.log('Form submitted:', Object.fromEntries(formData));
                
                // Show success message
                this.showMessage('Form submitted successfully!', 'success');
            }
            
            handleButtonClick(e) {
                const button = e.target.matches('.btn-primary') ? e.target : e.target.closest('.btn-primary');
                const buttonText = button.textContent.trim();
                
                // Handle different button actions
                if (buttonText.includes('Book') || buttonText.includes('Add') || buttonText.includes('New')) {
                    this.showMessage(`${buttonText} action initiated`, 'info');
                } else if (buttonText.includes('Manage') || buttonText.includes('Edit')) {
                    this.showMessage(`${buttonText} panel opened`, 'info');
                }
            }
            
            showMessage(message, type = 'info') {
                // Simple alert for now - could be replaced with toast notifications
                alert(message);
            }
        }
        
        // Initialize dashboard when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new HotelDashboard();
            new NotificationSystem();
            new FormHandler();
            
            // Add some initial notifications
            setTimeout(() => {
                const notificationSystem = new NotificationSystem();
                notificationSystem.addNotification('Welcome to LuxeStay Manager Dashboard!');
                notificationSystem.addNotification('3 new bookings pending approval');
                notificationSystem.addNotification('Room 150 maintenance completed');
            }, 2000);
        });
        
        // Utility functions
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        }
        
        function formatDate(date) {
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            }).format(new Date(date));
        }
        
        // Export data functionality
        function exportData(type) {
            console.log(`Exporting ${type} data...`);
            alert(`${type} data export initiated. Download will start shortly.`);
        }
        
        // Print functionality
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>