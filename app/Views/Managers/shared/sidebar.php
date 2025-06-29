<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">Hotel Management System</div>
        <div class="sidebar-subtitle">Admin Dashboard</div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Main</div>
            <a href="<?= base_url('manager/dashboard') ?>" class="nav-item <?= current_url() == base_url('manager/dashboard') ? 'active' : '' ?>" data-section="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="<?= base_url('manager/staff') ?>" class="nav-item <?= current_url() == base_url('manager/staff') ? 'active' : '' ?>" data-section="managers">
                <i class="fas fa-user-tie"></i>
                <span>Staff</span>
            </a>

            <a href="<?= base_url('manager/staff-tasks') ?>" class="nav-item <?= current_url() == base_url('manager/staff') ? 'active' : '' ?>" data-section="managers">
                <i class="fas fa-user-tie"></i>
                <span> Assign Staff Tasks</span>
            </a>
            
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Bookings</div>
            
            <a href="<?= base_url('manager/rooms') ?>" class="nav-item <?= current_url() == base_url('manager/rooms') ? 'active' : '' ?>" data-section="bookings">
                <i class="fas fa-calendar-alt"></i>
                <span>Rooms</span>
            </a>
              <a href="<?= base_url('manager/roomtypes') ?>" class="nav-item <?= current_url() == base_url('manager/rooms') ? 'active' : '' ?>" data-section="bookings">
                <i class="fas fa-calendar-alt"></i>
                <span>Rooms Types</span>
            </a>
            <a href="<?= base_url('manager/reserve') ?>" class="nav-item <?= current_url() == base_url('manager/rooms') ? 'active' : '' ?>" data-section="bookings">
                <i class="fas fa-calendar-alt"></i>
                <span>Reservations</span>
            </a>
        </div>
        
        
        <div class="nav-section">
            <div class="nav-section-title">Settings</div>
            <a href="<?= base_url('manager/profile') ?>" class="nav-item <?= current_url() == base_url('admin/profile') ? 'active' : '' ?>" data-section="profile">
                <i class="fas fa-user-circle"></i>
                <span>Profile</span>
            </a>
           
            <a href="<?= base_url('manager/logout') ?>" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</div>
