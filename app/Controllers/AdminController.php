<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\HotelModel;
use App\Models\ManagerModel;

class AdminController extends BaseController
{
    protected $adminModel;
    protected $hotelModel;
    protected $managerModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->hotelModel = new HotelModel();
        $this->managerModel = new ManagerModel();
        
        // Ensure the user is a logged-in admin
        if (!session()->get('admin_id')) {
            redirect()->to('/admin/login')->send();
            exit();
        }
    }

    /**
     * Admin Dashboard
     */
    public function index()
    {
        $adminId = session()->get('admin_id');
        
        // Get admin statistics
        $stats = $this->getAdminStats($adminId);
        
        $data = [
            'title' => 'Admin Dashboard',
            'stats' => $stats
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Display admin profile
     */
    public function profile()
    {
        $adminId = session()->get('admin_id');
        
        // Get admin details with hotel count
        $admin = $this->adminModel->getAdminWithHotels($adminId);
        
        if (!$admin) {
            return redirect()->to('/admin/login')->with('error', 'Admin profile not found');
        }

        $data = [
            'title' => 'My Profile',
            'admin' => $admin
        ];

        return view('admin/profile', $data);
    }

    /**
     * Update admin profile
     */
    public function updateProfile()
    {
        $adminId = session()->get('admin_id');
        
        // Validation rules for profile update
        $rules = [
            'full_name' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]|is_unique[admins.email,admin_id,' . $adminId . ']',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[admins.username,admin_id,' . $adminId . ']',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username')
        ];

        try {
            $this->adminModel->updateProfile($adminId, $data);
            
            // Update session data
            session()->set([
                'admin_name' => $data['full_name'],
                'admin_email' => $data['email']
            ]);
            
            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        $adminId = session()->get('admin_id');
        
        // Validation rules for password change
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('password_errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        try {
            // Get current admin data
            $admin = $this->adminModel->find($adminId);
            
            if (!$admin) {
                return redirect()->back()->with('password_error', 'Admin not found');
            }

            // Verify current password
            if (!password_verify($currentPassword, $admin['password_hash'])) {
                return redirect()->back()->with('password_error', 'Current password is incorrect');
            }

            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->adminModel->update($adminId, ['password_hash' => $hashedPassword]);

            return redirect()->back()->with('password_success', 'Password changed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('password_error', 'Failed to change password: ' . $e->getMessage());
        }
    }

    /**
     * Get admin statistics for profile dashboard
     */
    public function getStats()
    {
        $adminId = session()->get('admin_id');
        
        try {
            $stats = $this->getAdminStats($adminId);

            return $this->response->setJSON([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get system overview statistics
     */
    public function getSystemStats()
    {
        try {
            // Get total counts
            $totalHotels = $this->hotelModel->countAll();
            $totalManagers = $this->managerModel->countAll();
            $totalAdmins = $this->adminModel->countAll();
            
            // Get recent activity
            $recentHotels = $this->hotelModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
            $recentManagers = $this->managerModel->orderBy('created_at', 'DESC')->limit(5)->findAll();

            $stats = [
                'totals' => [
                    'hotels' => $totalHotels,
                    'managers' => $totalManagers,
                    'admins' => $totalAdmins
                ],
                'recent' => [
                    'hotels' => $recentHotels,
                    'managers' => $recentManagers
                ]
            ];

            return $this->response->setJSON([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get admin statistics helper
     */
    private function getAdminStats($adminId)
    {
        // Get hotels managed by this admin
        $adminHotels = $this->hotelModel->where('admin_id', $adminId)->findAll();
        $hotelCount = count($adminHotels);
        
        // Get managers under this admin
        $managerCount = 0;
        foreach ($adminHotels as $hotel) {
            if (!empty($hotel['manager_id'])) {
                $managerCount++;
            }
        }
        
        // Get total system stats (for super admin view)
        $totalHotels = $this->hotelModel->countAll();
        $totalManagers = $this->managerModel->countAll();
        $totalAdmins = $this->adminModel->countAll();

        return [
            'admin_hotels' => $hotelCount,
            'admin_managers' => $managerCount,
            'total_hotels' => $totalHotels,
            'total_managers' => $totalManagers,
            'total_admins' => $totalAdmins,
            'hotel_details' => $adminHotels
        ];
    }
}