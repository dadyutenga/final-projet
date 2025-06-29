<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\StaffTaskModel;
use App\Models\StaffModel;
use App\Models\HotelModel;

class StaffTaskController extends Controller
{
    protected $staffTaskModel;
    protected $staffModel;
    protected $hotelModel;

    public function __construct()
    {
        $this->staffTaskModel = new StaffTaskModel();
        $this->staffModel = new StaffModel();
        $this->hotelModel = new HotelModel();
        
        // Ensure the user is a logged-in manager
        if (!session()->get('manager_id')) {
            return redirect()->to('/manager/login');
        }
    }

    public function index()
    {
        $managerId = session()->get('manager_id');
        
        // Get the hotel managed by this manager
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/dashboard')->with('error', 'No hotel associated with your manager account.');
        }
        
        // Get filter parameters
        $statusFilter = $this->request->getGet('status');
        $staffFilter = $this->request->getGet('staff');
        $searchTerm = $this->request->getGet('search');
        
        // Get tasks assigned by this manager
        $tasks = $this->staffTaskModel->getTasksByManager($managerId, $statusFilter, $hotel['hotel_id']);
        
        // Apply additional filters
        if ($staffFilter) {
            $tasks = array_filter($tasks, function($task) use ($staffFilter) {
                return $task['staff_id'] == $staffFilter;
            });
        }
        
        if ($searchTerm) {
            $tasks = array_filter($tasks, function($task) use ($searchTerm) {
                return stripos($task['task_description'], $searchTerm) !== false ||
                       stripos($task['staff_name'], $searchTerm) !== false;
            });
        }
        
        // Get staff for filter dropdown
        $staff = $this->staffModel->getStaffByHotel($hotel['hotel_id']);
        
        // Get task statistics
        $taskStats = $this->staffTaskModel->getTaskStatistics($hotel['hotel_id'], $managerId);
        
        // Get overdue tasks
        $overdueTasks = $this->staffTaskModel->getOverdueTasks($hotel['hotel_id'], $managerId);
        
        // Get tasks due today
        $tasksDueToday = $this->staffTaskModel->getTasksDueToday($hotel['hotel_id'], $managerId);
        
        return view('managers/staff-task/index', [
            'tasks' => $tasks,
            'staff' => $staff,
            'hotel' => $hotel,
            'taskStats' => $taskStats,
            'overdueTasks' => $overdueTasks,
            'tasksDueToday' => $tasksDueToday,
            'statusFilter' => $statusFilter,
            'staffFilter' => $staffFilter,
            'searchTerm' => $searchTerm
        ]);
    }

    public function create()
    {
        $managerId = session()->get('manager_id');
        
        // Get the hotel managed by this manager
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/dashboard')->with('error', 'No hotel associated with your manager account.');
        }
        
        // Get staff for this hotel
        $staff = $this->staffModel->getStaffByHotel($hotel['hotel_id']);
        
        if (empty($staff)) {
            return redirect()->to('/manager/staff/create')
                           ->with('error', 'Please add staff members first before assigning tasks.');
        }
        
        return view('managers/staff-task/create', [
            'staff' => $staff,
            'hotel' => $hotel
        ]);
    }

    public function store()
    {
        $managerId = session()->get('manager_id');
        
        // Get the hotel managed by this manager
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->back()->with('error', 'No hotel associated with your manager account.');
        }
        
        // Validation rules
        $rules = [
            'staff_id'         => 'required|is_natural_no_zero',
            'task_description' => 'required|max_length[1000]',
            'due_date'         => 'required|valid_date',
            'priority'         => 'permit_empty|in_list[low,medium,high,urgent]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }
        
        // Verify staff belongs to this hotel
        $staff = $this->staffModel->find($this->request->getPost('staff_id'));
        if (!$staff || $staff['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Invalid staff member selected.');
        }
        
        // Check if due date is in the future
        $dueDate = $this->request->getPost('due_date');
        if (strtotime($dueDate) <= time()) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Due date must be in the future.');
        }
        
        // Prepare data for insertion
        $data = [
            'staff_id'         => $this->request->getPost('staff_id'),
            'manager_id'       => $managerId,
            'hotel_id'         => $hotel['hotel_id'],
            'task_description' => $this->request->getPost('task_description'),
            'due_date'         => $dueDate,
            'assigned_date'    => date('Y-m-d H:i:s'),
            'status'           => 'assigned'
        ];
        
        try {
            if ($this->staffTaskModel->save($data)) {
                return redirect()->to(base_url('manager/staff-tasks'))
                               ->with('success', 'Task assigned successfully to ' . $staff['full_name']);
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to assign task: ' . implode(', ', $this->staffTaskModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', '[Task Assignment] ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'An error occurred while assigning the task');
        }
    }

    public function show($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/dashboard')->with('error', 'No hotel associated with your manager account.');
        }
        
        $task = $this->staffTaskModel->getTaskWithDetails($id);
        
        if (!$task || $task['manager_id'] != $managerId) {
            return redirect()->to('/manager/staff-tasks')->with('error', 'Task not found or not authorized.');
        }
        
        return view('managers/staff-task/show', [
            'task' => $task,
            'hotel' => $hotel
        ]);
    }

    public function edit($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/dashboard')->with('error', 'No hotel associated with your manager account.');
        }
        
        $task = $this->staffTaskModel->find($id);
        
        if (!$task || $task['manager_id'] != $managerId) {
            return redirect()->to('/manager/staff-tasks')->with('error', 'Task not found or not authorized.');
        }
        
        // Don't allow editing completed tasks
        if ($task['status'] == 'completed') {
            return redirect()->to('/manager/staff-tasks')->with('error', 'Cannot edit completed tasks.');
        }
        
        // Get staff for this hotel
        $staff = $this->staffModel->getStaffByHotel($hotel['hotel_id']);
        
        return view('managers/staff-task/edit', [
            'task' => $task,
            'staff' => $staff,
            'hotel' => $hotel
        ]);
    }

    public function update($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->back()->with('error', 'No hotel associated with your manager account.');
        }
        
        $task = $this->staffTaskModel->find($id);
        
        if (!$task || $task['manager_id'] != $managerId) {
            return redirect()->to('/manager/staff-tasks')->with('error', 'Task not found or not authorized.');
        }
        
        // Don't allow editing completed tasks
        if ($task['status'] == 'completed') {
            return redirect()->to('/manager/staff-tasks')->with('error', 'Cannot edit completed tasks.');
        }
        
        // Validation rules
        $rules = [
            'staff_id'         => 'required|is_natural_no_zero',
            'task_description' => 'required|max_length[1000]',
            'due_date'         => 'required|valid_date',
            'status'           => 'required|in_list[assigned,in_progress,completed,overdue]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }
        
        // Verify staff belongs to this hotel
        $staff = $this->staffModel->find($this->request->getPost('staff_id'));
        if (!$staff || $staff['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Invalid staff member selected.');
        }
        
        $data = [
            'staff_id'         => $this->request->getPost('staff_id'),
            'task_description' => $this->request->getPost('task_description'),
            'due_date'         => $this->request->getPost('due_date'),
            'status'           => $this->request->getPost('status')
        ];
        
        try {
            if ($this->staffTaskModel->update($id, $data)) {
                return redirect()->to(base_url('manager/staff-tasks'))
                               ->with('success', 'Task updated successfully');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to update task: ' . implode(', ', $this->staffTaskModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', '[Task Update] ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'An error occurred while updating the task');
        }
    }

    public function destroy($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/staff-tasks')->with('error', 'No hotel associated with your manager account.');
        }
        
        $task = $this->staffTaskModel->find($id);
        
        if (!$task || $task['manager_id'] != $managerId) {
            return redirect()->to('/manager/staff-tasks')->with('error', 'Task not found or not authorized.');
        }
        
        try {
            if ($this->staffTaskModel->delete($id)) {
                return redirect()->to('/manager/staff-tasks')->with('success', 'Task deleted successfully.');
            } else {
                return redirect()->to('/manager/staff-tasks')->with('error', 'Failed to delete task.');
            }
        } catch (\Exception $e) {
            log_message('error', '[Task Deletion] ' . $e->getMessage());
            return redirect()->to('/manager/staff-tasks')->with('error', 'An error occurred while deleting the task.');
        }
    }

    public function updateStatus($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authorized']);
        }
        
        $task = $this->staffTaskModel->find($id);
        
        if (!$task || $task['manager_id'] != $managerId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Task not found']);
        }
        
        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['assigned', 'in_progress', 'completed', 'overdue'])) {
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
            
            if ($this->staffTaskModel->update($id, $data)) {
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
            log_message('error', '[Task Status Update] ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'An error occurred while updating task status'
            ]);
        }
    }

    public function reassign($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->back()->with('error', 'Not authorized');
        }
        
        $task = $this->staffTaskModel->find($id);
        
        if (!$task || $task['manager_id'] != $managerId) {
            return redirect()->back()->with('error', 'Task not found');
        }
        
        $newStaffId = $this->request->getPost('new_staff_id');
        $staff = $this->staffModel->find($newStaffId);
        
        if (!$staff || $staff['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->back()->with('error', 'Invalid staff member selected');
        }
        
        if ($this->staffTaskModel->reassignTask($id, $newStaffId, $managerId)) {
            return redirect()->back()->with('success', 'Task reassigned successfully to ' . $staff['full_name']);
        }
        
        return redirect()->back()->with('error', 'Failed to reassign task');
    }
}