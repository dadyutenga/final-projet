<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-hotel"></i>
            Hotel Management System
        </div>
        <div class="sidebar-subtitle">Staff Dashboard</div>
    </div>

    <!-- User Info Section -->
    <div class="user-info">
        <div class="user-avatar">
            <?php if (session()->get('staff_name')): ?>
                <?= strtoupper(substr(session()->get('staff_name'), 0, 2)) ?>
            <?php else: ?>
                <i class="fas fa-user"></i>
            <?php endif; ?>
        </div>
        <div class="user-name">
            <?= session()->get('staff_name') ?? 'Staff Member' ?>
        </div>
        <div class="user-role">
            <?= ucfirst(session()->get('staff_role') ?? 'staff') ?>
        </div>
        <div class="user-hotel">
            <i class="fas fa-building"></i>
            <?= session()->get('hotel_name') ?? 'Hotel' ?>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Main</div>
            
            <a href="<?= base_url('staff/dashboard') ?>" 
               class="nav-item <?= (current_url() == base_url('staff/dashboard') || strpos(current_url(), 'staff/dashboard') !== false) ? 'active' : '' ?>" 
               data-section="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="<?= base_url('staff/tasks') ?>" 
               class="nav-item <?= (strpos(current_url(), 'staff/tasks') !== false) ? 'active' : '' ?>" 
               data-section="tasks">
                <i class="fas fa-tasks"></i>
                <span>My Tasks</span>
                <?php 
                // Get active task count from session if available
                $activeTaskCount = 0;
                if (session()->get('staff_active_tasks')) {
                    $activeTaskCount = session()->get('staff_active_tasks');
                } elseif (isset($taskStats) && isset($taskStats['assigned']) && isset($taskStats['in_progress'])) {
                    $activeTaskCount = $taskStats['assigned'] + $taskStats['in_progress'];
                }
                ?>
                <?php if ($activeTaskCount > 0): ?>
                    <span class="badge"><?= $activeTaskCount ?></span>
                <?php endif; ?>
            </a>

           
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Work Management</div>
            
            <a href="<?= base_url('staff/reservation') ?>" 
               class="nav-item <?= (strpos(current_url(), 'staff/reports') !== false) ? 'active' : '' ?>" 
               data-section="reports">
                <i class="fas fa-chart-bar"></i>
                <span>Reservation</span>
            </a>

             <a href="<?= base_url('staff/rooms') ?>" 
               class="nav-item <?= (strpos(current_url(), 'staff/reports') !== false) ? 'active' : '' ?>" 
               data-section="reports">
                <i class="fas fa-chart-bar"></i>
                <span>Room Management</span>
            </a>

            <a href="<?= base_url('staff/bookings') ?>" 
               class="nav-item <?= (strpos(current_url(), 'staff/reports') !== false) ? 'active' : '' ?>" 
               data-section="reports">
                <i class="fas fa-chart-bar"></i>
                <span>Booking</span>
            </a>

             <a href="<?= base_url('staff/payments') ?>" 
               class="nav-item <?= (strpos(current_url(), 'staff/reports') !== false) ? 'active' : '' ?>" 
               data-section="reports">
                <i class="fas fa-chart-bar"></i>
                <span>Payments</span>
            </a>

        </div>

        
        <div class="nav-section">
            <div class="nav-section-title">Settings</div>
            
            <a href="<?= base_url('staff/profile') ?>" 
               class="nav-item <?= (strpos(current_url(), 'staff/profile') !== false) ? 'active' : '' ?>" 
               data-section="profile">
                <i class="fas fa-user-circle"></i>
                <span>Profile</span>
            </a>

           
            <a href="<?= base_url('staff/logout') ?>" class="nav-item logout-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>

        <!-- Staff Status Indicator -->
        <div class="staff-status">
            <div class="status-indicator">
                <div class="status-dot status-online"></div>
                <span class="status-text">Online</span>
            </div>
            <div class="shift-info">
                <i class="fas fa-clock"></i>
                <span>Shift: <?= date('g:i A') ?> - <?= date('g:i A', strtotime('+8 hours')) ?></span>
            </div>
        </div>
    </nav>
</div>

<!-- Sidebar overlay for mobile -->
<div class="sidebar-overlay" onclick="closeSidebar()"></div>

<style>
/* Additional styles for the enhanced sidebar */
.user-info {
    background: var(--light-gray);
    padding: 1rem;
    margin: 1rem;
    border-radius: 8px;
    text-align: center;
}

.user-avatar {
    width: 60px;
    height: 60px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-size: 1.5rem;
    color: var(--white);
    font-weight: 600;
}

.user-name {
    font-weight: 600;
    color: var(--dark-gray);
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.user-role {
    font-size: 0.8rem;
    color: var(--text-gray);
    text-transform: capitalize;
    margin-bottom: 0.5rem;
}

.user-hotel {
    font-size: 0.75rem;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
}

.nav-section {
    margin-bottom: 1.5rem;
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
    position: relative;
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
    background: var(--danger-color);
    color: var(--white);
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
    font-weight: 500;
}

.nav-item .badge.badge-info {
    background: var(--info-color);
}

.nav-item .badge.badge-warning {
    background: var(--warning-color);
    color: var(--dark-gray);
}

.logout-item {
    border-top: 1px solid var(--border-color);
    margin-top: 1rem;
    padding-top: 1rem;
}

.logout-item:hover {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger-color);
}

.staff-status {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-color);
    margin-top: auto;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.status-dot.status-online {
    background: var(--success-color);
}

.status-dot.status-busy {
    background: var(--warning-color);
}

.status-dot.status-offline {
    background: var(--text-gray);
}

.status-text {
    font-size: 0.8rem;
    color: var(--success-color);
    font-weight: 500;
}

.shift-info {
    font-size: 0.75rem;
    color: var(--text-gray);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
    }
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
