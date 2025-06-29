<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\StaffTaskModel;
use App\Models\StaffModel;
use App\Models\HotelModel;

class TaskViewerController extends Controller
{
    protected $staffTaskModel;
    protected $staffModel;
    protected $hotelModel;

    public function __construct()
    {
        $this->staffTaskModel = new StaffTaskModel();
        $this->staffModel = new StaffModel();
        $this->hotelModel = new HotelModel();
        
        // Ensure the user is a logged-in staff member
        if (!session()->get('staff_id')) {
            redirect()->to('/staff/login')->send();
            exit();
        }
    }

    public function index()
    {
        $staffId = session()->get('staff_id');
        
        // Get filter parameters
        $statusFilter = $this->request->getGet('status');
        $searchTerm = $this->request->getGet('search') ?? '';

        // Build query for staff's tasks
        $builder = $this->staffTaskModel->select('
            staff_tasks.*,
            staff.full_name as staff_name,
            staff.role as staff_role
        ')
        ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
        ->where('staff_tasks.staff_id', $staffId);

        // Apply filters
        if ($statusFilter) {
            $builder->where('staff_tasks.status', $statusFilter);
        }

        if (!empty($searchTerm)) {
            $builder->like('staff_tasks.task_description', $searchTerm);
        }

        // Order by due date and creation date (removed priority ordering)
        $builder->orderBy('staff_tasks.due_date', 'ASC')
                ->orderBy('staff_tasks.created_at', 'DESC');

        $tasks = $builder->findAll();

        // Get task statistics for this staff member
        $taskStats = [
            'assigned' => $this->staffTaskModel->where('staff_id', $staffId)->where('status', 'assigned')->countAllResults(),
            'in_progress' => $this->staffTaskModel->where('staff_id', $staffId)->where('status', 'in_progress')->countAllResults(),
            'completed' => $this->staffTaskModel->where('staff_id', $staffId)->where('status', 'completed')->countAllResults(),
            'overdue' => $this->staffTaskModel->where('staff_id', $staffId)->where('status', 'overdue')->countAllResults()
        ];

        // Get overdue tasks
        $overdueTasks = $this->staffTaskModel->select('
            staff_tasks.*,
            staff.full_name as staff_name
        ')
        ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
        ->where('staff_tasks.staff_id', $staffId)
        ->where('staff_tasks.due_date <', date('Y-m-d'))
        ->where('staff_tasks.status !=', 'completed')
        ->findAll();

        // Get tasks due today
        $tasksDueToday = $this->staffTaskModel->select('
            staff_tasks.*,
            staff.full_name as staff_name
        ')
        ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
        ->where('staff_tasks.staff_id', $staffId)
        ->where('staff_tasks.due_date', date('Y-m-d'))
        ->where('staff_tasks.status !=', 'completed')
        ->findAll();

        // Update session with active task count
        session()->set('staff_active_tasks', $taskStats['assigned'] + $taskStats['in_progress']);

        $data = [
            'tasks' => $tasks,
            'taskStats' => $taskStats,
            'overdueTasks' => $overdueTasks,
            'tasksDueToday' => $tasksDueToday,
            'statusFilter' => $statusFilter,
            'searchTerm' => $searchTerm
        ];

        return view('staff/tasks/view', $data);
    }

    public function show($id = null)
    {
        $staffId = session()->get('staff_id');
        
        $task = $this->staffTaskModel->select('
            staff_tasks.*,
            staff.full_name as staff_name,
            staff.role as staff_role,
            staff.email as staff_email,
            staff.phone as staff_phone
        ')
        ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
        ->where('staff_tasks.task_id', $id)
        ->where('staff_tasks.staff_id', $staffId)
        ->first();

        if (!$task) {
            session()->setFlashdata('error', 'Task not found or you do not have permission to view it.');
            return redirect()->to('/staff/tasks');
        }

        $data = [
            'task' => $task
        ];

        return view('staff/tasks/show', $data);
    }

    public function updateStatus($id = null)
    {
        $staffId = session()->get('staff_id');
        
        $task = $this->staffTaskModel->where('task_id', $id)
                                   ->where('staff_id', $staffId)
                                   ->first();
        
        if (!$task) {
            return $this->response->setJSON(['success' => false, 'message' => 'Task not found']);
        }
        
        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['assigned', 'in_progress', 'completed'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }
        
        try {
            $data = [
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // If marking as completed, also set completion date
            if ($status === 'completed') {
                $data['completed_at'] = date('Y-m-d H:i:s');
            }
            
            // Use direct database update to avoid model callbacks
            $db = \Config\Database::connect();
            $builder = $db->table('staff_tasks');
            $result = $builder->where('task_id', $id)->update($data);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Task status updated successfully',
                    'new_status' => $status
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Failed to update task status'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', '[Staff Task Status Update] ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'An error occurred while updating task status'
            ]);
        }
    }

    public function markComplete($id = null)
    {
        return $this->updateStatus($id);
    }
}