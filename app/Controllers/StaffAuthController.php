<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\StaffModel;
use App\Models\HotelModel;

class StaffAuthController extends Controller
{
    protected $staffModel;
    protected $hotelModel;

    public function __construct()
    {
        $this->staffModel = new StaffModel();
        $this->hotelModel = new HotelModel();
        helper(['form', 'url']);
    }

    /**
     * Display staff login form
     */
    public function login()
    {
        // If staff is already logged in, redirect to dashboard
        if (session()->get('staff_id')) {
            return redirect()->to('/staff/dashboard');
        }

        return view('auth/login2');
    }

    /**
     * Process staff login
     */
    public function processLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Validation rules
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Verify credentials
        $staff = $this->staffModel->verifyCredentials($username, $password);

        if (!$staff) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Invalid username or password');
        }

        // Get staff details with hotel information
        $staffDetails = $this->staffModel->getStaffWithDetails($staff['staff_id']);

        if (!$staffDetails) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Staff account not found or inactive');
        }

        // Set session data
        $sessionData = [
            'staff_id'      => $staff['staff_id'],
            'staff_name'    => $staff['full_name'],
            'staff_role'    => $staff['role'],
            'staff_username'=> $staff['username'],
            'hotel_id'      => $staff['hotel_id'],
            'hotel_name'    => $staffDetails['hotel_name'] ?? 'Unknown Hotel',
            'manager_id'    => $staff['manager_id'],
            'is_staff_logged_in' => true,
            'login_time'    => time()
        ];

        session()->set($sessionData);

        // Log successful login
        log_message('info', 'Staff login successful: ' . $username . ' (ID: ' . $staff['staff_id'] . ')');

        // Redirect to dashboard
        return redirect()->to('/staff/dashboard')
                         ->with('success', 'Welcome back, ' . $staff['full_name'] . '!');
    }

    /**
     * Staff logout
     */
    public function logout()
    {
        $staffName = session()->get('staff_name');
        
        // Log logout
        log_message('info', 'Staff logout: ' . $staffName . ' (ID: ' . session()->get('staff_id') . ')');

        // Destroy session
        session()->destroy();

        return redirect()->to('/staff/login')
                         ->with('success', 'You have been logged out successfully');
    }

    /**
     * Staff dashboard
     */
    public function dashboard()
    {
        // Check if staff is logged in
        if (!session()->get('staff_id')) {
            return redirect()->to('/staff/login')
                           ->with('error', 'Please login to access the dashboard');
        }

        $staffId = session()->get('staff_id');
        $hotelId = session()->get('hotel_id');

        // Get staff details
        $staff = $this->staffModel->getStaffWithDetails($staffId);

        if (!$staff) {
            session()->destroy();
            return redirect()->to('/staff/login')
                           ->with('error', 'Staff account not found');
        }

        // Get staff tasks
        $tasks = $this->getStaffTasks($staffId);

        // Get task statistics
        $taskStats = $this->getTaskStatistics($staffId);

        // Get recent tasks (last 10)
        $recentTasks = $this->getRecentTasks($staffId, 10);

        // Get upcoming tasks (next 7 days)
        $upcomingTasks = $this->getUpcomingTasks($staffId, 7);

        // Get overdue tasks
        $overdueTasks = $this->getOverdueTasks($staffId);

        return view('staff/dashboard', [
            'staff'         => $staff,
            'tasks'         => $tasks,
            'taskStats'     => $taskStats,
            'recentTasks'   => $recentTasks,
            'upcomingTasks' => $upcomingTasks,
            'overdueTasks'  => $overdueTasks
        ]);
    }

    /**
     * Staff profile
     */
    public function profile()
    {
        if (!session()->get('staff_id')) {
            return redirect()->to('/staff/login');
        }

        $staffId = session()->get('staff_id');
        $staff = $this->staffModel->getStaffWithDetails($staffId);

        return view('staff/profile', ['staff' => $staff]);
    }

    /**
     * Update staff profile
     */
    public function updateProfile()
    {
        if (!session()->get('staff_id')) {
            return redirect()->to('/staff/login');
        }

        $staffId = session()->get('staff_id');

        // Validation rules
        $rules = [
            'full_name' => 'required|max_length[100]',
            'phone'     => 'permit_empty|max_length[20]',
            'email'     => 'permit_empty|valid_email|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'phone'     => $this->request->getPost('phone'),
            'email'     => $this->request->getPost('email')
        ];

        if ($this->staffModel->update($staffId, $data)) {
            // Update session data
            session()->set('staff_name', $data['full_name']);
            
            return redirect()->back()
                           ->with('success', 'Profile updated successfully');
        }

        return redirect()->back()
                         ->with('error', 'Failed to update profile');
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        if (!session()->get('staff_id')) {
            return redirect()->to('/staff/login');
        }

        $staffId = session()->get('staff_id');

        // Validation rules
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors());
        }

        $staff = $this->staffModel->find($staffId);
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Verify current password
        if (!password_verify($currentPassword, $staff['password_hash'])) {
            return redirect()->back()
                           ->with('error', 'Current password is incorrect');
        }

        // Update password
        $data = [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
        ];

        if ($this->staffModel->update($staffId, $data)) {
            return redirect()->back()
                           ->with('success', 'Password changed successfully');
        }

        return redirect()->back()
                         ->with('error', 'Failed to change password');
    }

    // Helper methods for dashboard data

    private function getStaffTasks($staffId)
    {
        return $this->staffModel->getStaffTasks($staffId);
    }

    private function getTaskStatistics($staffId)
    {
        $db = \Config\Database::connect();
        
        $stats = $db->table('staff_tasks')
                   ->select('status, COUNT(*) as count')
                   ->where('staff_id', $staffId)
                   ->groupBy('status')
                   ->get()
                   ->getResultArray();

        $result = [
            'assigned'    => 0,
            'in_progress' => 0,
            'completed'   => 0,
            'overdue'     => 0,
            'total'       => 0
        ];

        foreach ($stats as $stat) {
            $result[$stat['status']] = $stat['count'];
            $result['total'] += $stat['count'];
        }

        return $result;
    }

    private function getRecentTasks($staffId, $limit = 10)
    {
        $db = \Config\Database::connect();
        
        return $db->table('staff_tasks')
                 ->select('staff_tasks.*, managers.username as assigned_by')
                 ->join('managers', 'managers.manager_id = staff_tasks.manager_id', 'left')
                 ->where('staff_tasks.staff_id', $staffId)
                 ->orderBy('staff_tasks.assigned_date', 'DESC')
                 ->limit($limit)
                 ->get()
                 ->getResultArray();
    }

    private function getUpcomingTasks($staffId, $days = 7)
    {
        $db = \Config\Database::connect();
        
        return $db->table('staff_tasks')
                 ->select('staff_tasks.*, managers.username as assigned_by')
                 ->join('managers', 'managers.manager_id = staff_tasks.manager_id', 'left')
                 ->where('staff_tasks.staff_id', $staffId)
                 ->where('staff_tasks.status !=', 'completed')
                 ->where('staff_tasks.due_date >=', date('Y-m-d'))
                 ->where('staff_tasks.due_date <=', date('Y-m-d', strtotime('+' . $days . ' days')))
                 ->orderBy('staff_tasks.due_date', 'ASC')
                 ->get()
                 ->getResultArray();
    }

    private function getOverdueTasks($staffId)
    {
        $db = \Config\Database::connect();
        
        return $db->table('staff_tasks')
                 ->select('staff_tasks.*, managers.username as assigned_by')
                 ->join('managers', 'managers.manager_id = staff_tasks.manager_id', 'left')
                 ->where('staff_tasks.staff_id', $staffId)
                 ->where('staff_tasks.status !=', 'completed')
                 ->where('staff_tasks.due_date <', date('Y-m-d'))
                 ->orderBy('staff_tasks.due_date', 'ASC')
                 ->get()
                 ->getResultArray();
    }
}