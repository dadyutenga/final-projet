<?php

namespace App\Controllers;

use App\Models\StaffModel;
use App\Models\HotelModel;

class StaffProfileController extends BaseController
{
    protected $staffModel;
    protected $hotelModel;

    public function __construct()
    {
        $this->staffModel = new StaffModel();
        $this->hotelModel = new HotelModel();
        
        // Ensure the user is a logged-in staff member
        if (!session()->get('staff_id')) {
            redirect()->to('/staff/login')->send();
            exit();
        }
    }

    /**
     * Display staff profile
     */
    public function index()
    {
        $staffId = session()->get('staff_id');
        
        // Get staff details with hotel information
        $staff = $this->staffModel->getStaffWithDetails($staffId);
        
        if (!$staff) {
            return redirect()->to('/staff/login')->with('error', 'Staff profile not found');
        }

        $data = [
            'title' => 'My Profile',
            'staff' => $staff
        ];

        return view('staff/profile', $data);
    }

    /**
     * Update staff profile
     */
    public function update()
    {
        $staffId = session()->get('staff_id');
        
        // Validation rules for profile update
        $rules = [
            'full_name' => 'required|max_length[100]',
            'phone' => 'permit_empty|max_length[20]',
            'email' => 'permit_empty|valid_email|max_length[100]',
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
            $this->staffModel->update($staffId, $data);
            
            // Update session data
            session()->set('staff_name', $data['full_name']);
            
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
        $staffId = session()->get('staff_id');
        
        // Validation rules for password change
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('password_errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        try {
            // Get current staff data
            $staff = $this->staffModel->find($staffId);
            
            if (!$staff) {
                return redirect()->back()->with('password_error', 'Staff not found');
            }

            // Verify current password
            if (!password_verify($currentPassword, $staff['password_hash'])) {
                return redirect()->back()->with('password_error', 'Current password is incorrect');
            }

            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->staffModel->update($staffId, ['password_hash' => $hashedPassword]);

            return redirect()->back()->with('password_success', 'Password changed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('password_error', 'Failed to change password: ' . $e->getMessage());
        }
    }

    /**
     * Get staff statistics for profile dashboard
     */
    public function getStats()
    {
        $staffId = session()->get('staff_id');
        
        try {
            // Get staff tasks statistics
            $totalTasks = count($this->staffModel->getStaffTasks($staffId));
            $completedTasks = count($this->staffModel->getStaffTasks($staffId, 'completed'));
            $pendingTasks = count($this->staffModel->getStaffTasks($staffId, 'pending'));
            $inProgressTasks = count($this->staffModel->getStaffTasks($staffId, 'in_progress'));

            $stats = [
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'pending_tasks' => $pendingTasks,
                'in_progress_tasks' => $inProgressTasks,
                'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0
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
}