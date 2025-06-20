<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">Hotel Management System</div>
        <div class="sidebar-subtitle">Admin Dashboard</div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Main</div>
            <a href="<?= base_url('admin/dashboard') ?>" class="nav-item <?= current_url() == base_url('admin/dashboard') ? 'active' : '' ?>" data-section="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= base_url('admin/hotels') ?>" class="nav-item <?= current_url() == base_url('admin/hotels') ? 'active' : '' ?>" data-section="hotels">
                <i class="fas fa-hotel"></i>
                <span>Hotels</span>
            </a>
            <a href="<?= base_url('admin/managers') ?>" class="nav-item <?= current_url() == base_url('admin/managers') ? 'active' : '' ?>" data-section="managers">
                <i class="fas fa-user-tie"></i>
                <span>Managers</span>
            </a>
            <a href="<?= base_url('admin/users') ?>" class="nav-item <?= current_url() == base_url('admin/users') ? 'active' : '' ?>" data-section="users">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Reports</div>
            <a href="<?= base_url('admin/bookings') ?>" class="nav-item <?= current_url() == base_url('admin/bookings') ? 'active' : '' ?>" data-section="bookings">
                <i class="fas fa-calendar-check"></i>
                <span>Bookings</span>
            </a>
            <a href="<?= base_url('admin/revenue') ?>" class="nav-item <?= current_url() == base_url('admin/revenue') ? 'active' : '' ?>" data-section="revenue">
                <i class="fas fa-chart-line"></i>
                <span>Revenue</span>
            </a>
            <a href="<?= base_url('admin/reviews') ?>" class="nav-item <?= current_url() == base_url('admin/reviews') ? 'active' : '' ?>" data-section="reviews">
                <i class="fas fa-star"></i>
                <span>Reviews</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Settings</div>
            <a href="<?= base_url('admin/profile') ?>" class="nav-item <?= current_url() == base_url('admin/profile') ? 'active' : '' ?>" data-section="profile">
                <i class="fas fa-user-circle"></i>
                <span>Profile</span>
            </a>
            <a href="<?= base_url('admin/settings') ?>" class="nav-item <?= current_url() == base_url('admin/settings') ? 'active' : '' ?>" data-section="settings">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <a href="<?= base_url('admin/logout') ?>" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</div>
