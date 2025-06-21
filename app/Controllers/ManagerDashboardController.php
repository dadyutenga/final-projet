<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ManagerDashboardController extends BaseController
{
    protected $session;
    protected $managerModel;  // Assuming you have a ManagerModel for fetching data

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->managerModel = new \App\Models\ManagerModel();  // Load the model if needed for data
        helper(['url']);  // Load helpers if required
    }

    public function index()
    {
        // Check if the manager is logged in
        if (!$this->session->get('manager_logged_in')) {
            return redirect()->to('/manager/login');  // Redirect to login if not authenticated
        }

        // Fetch data for the dashboard (e.g., total hotels managed, total staff, recent activities, pending tasks)
        // This is placeholder data; replace with actual logic based on your models
        $data = [
            'manager_name' => $this->session->get('manager_full_name'),
            'total_hotels_managed' => $this->managerModel->getManagerWithStaffCount($this->session->get('manager_id'))['hotel_count'] ?? 0,
            'total_staff' => $this->managerModel->getManagerStaff($this->session->get('manager_id'), 10, 0)['count'] ?? 0,  // Example: Get first 10 staff
            'recent_activities' => [],  // Fetch from a model, e.g., $this->someModel->getRecentActivities()
            'pending_tasks' => [],  // Fetch from a model, e.g., $this->managerModel->getManagerTasks($this->session->get('manager_id'), 'pending')
        ];

        // You may need to implement or call methods in ManagerModel to populate recent_activities and pending_tasks
        return view('managers/Dashboard', $data);
    }
}
