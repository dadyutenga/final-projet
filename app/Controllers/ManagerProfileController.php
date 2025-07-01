<?php

namespace App\Controllers;

use App\Models\ManagerModel;
use App\Models\HotelModel;
use App\Models\StaffModel;

class ManagerProfileController extends BaseController
{
    protected $managerModel;
    protected $hotelModel;
    protected $staffModel;

    public function __construct()
    {
        $this->managerModel = new ManagerModel();
        $this->hotelModel = new HotelModel();
        $this->staffModel = new StaffModel();
        
        // Ensure the user is a logged-in manager
        if (!session()->get('manager_id')) {
            redirect()->to('/manager/login')->send();
            exit();
        }
    }

    /**
     * Display manager profile
     */
    public function index()
    {
        $managerId = session()->get('manager_id');
        
        // Get manager details with statistics
        $manager = $this->managerModel->getManagerWithStaffCount($managerId);
        
        if (!$manager) {
            return redirect()->to('/manager/login')->with('error', 'Manager profile not found');
        }

        // Get manager's hotel
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();

        $data = [
            'title' => 'My Profile',
            'manager' => $manager,
            'hotel' => $hotel
        ];

        return view('managers/profile', $data);
    }

    /**
     * Update manager profile
     */
    public function update()
    {
        $managerId = session()->get('manager_id');
        
        // Validation rules for profile update
        $rules = [
            'full_name' => 'required|max_length[100]',
            'phone' => 'permit_empty|max_length[20]',
            'email' => 'permit_empty|valid_email|max_length[100]|is_unique[managers.email,manager_id,' . $managerId . ']',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email')
        ];

        try {
            $this->managerModel->update($managerId, $data);
            
            // Update session data
            session()->set('manager_name', $data['full_name']);
            
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
        $managerId = session()->get('manager_id');
        
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
            // Get current manager data
            $manager = $this->managerModel->find($managerId);
            
            if (!$manager) {
                return redirect()->back()->with('password_error', 'Manager not found');
            }

            // Verify current password
            if (!password_verify($currentPassword, $manager['password_hash'])) {
                return redirect()->back()->with('password_error', 'Current password is incorrect');
            }

            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->managerModel->update($managerId, ['password_hash' => $hashedPassword]);

            return redirect()->back()->with('password_success', 'Password changed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('password_error', 'Failed to change password: ' . $e->getMessage());
        }
    }

    /**
     * Get manager statistics for profile dashboard
     */
    public function getStats()
    {
        $managerId = session()->get('manager_id');
        
        try {
            $stats = $this->managerModel->getManagerStatistics($managerId);

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
     * Get recent activity for manager
     */
    public function getRecentActivity()
    {
        $managerId = session()->get('manager_id');
        
        try {
            // Get recent tasks
            $recentTasks = $this->managerModel->getManagerTasks($managerId, null, 5, 0);
            
            // Get recent staff activities
            $recentStaff = $this->managerModel->getManagerStaff($managerId, 5, 0);

            $activity = [
                'recent_tasks' => $recentTasks,
                'recent_staff' => $recentStaff
            ];

            return $this->response->setJSON([
                'success' => true,
                'activity' => $activity
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}