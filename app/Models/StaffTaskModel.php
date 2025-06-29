<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffTaskModel extends Model
{
    protected $table            = 'staff_tasks';
    protected $primaryKey       = 'task_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'staff_id',
        'manager_id',
        'hotel_id',
        'task_description',
        'assigned_date',
        'due_date',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'staff_id'         => 'permit_empty|is_natural_no_zero',
        'manager_id'       => 'permit_empty|is_natural_no_zero',
        'hotel_id'         => 'permit_empty|is_natural_no_zero',
        'task_description' => 'required|max_length[1000]',
        'assigned_date'    => 'permit_empty|valid_date',
        'due_date'         => 'permit_empty|valid_date',
        'status'           => 'permit_empty|in_list[assigned,in_progress,completed,overdue]'
    ];
    protected $validationMessages   = [
        'task_description' => [
            'required'    => 'Task description is required',
            'max_length'  => 'Task description cannot exceed 1000 characters'
        ],
        'assigned_date' => [
            'valid_date'  => 'Please enter a valid assigned date'
        ],
        'due_date' => [
            'valid_date'  => 'Please enter a valid due date'
        ],
        'status' => [
            'in_list'     => 'Status must be one of: assigned, in_progress, completed, overdue'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setAssignedDate', 'validateDueDate'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['validateDueDate', 'checkOverdueStatus'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Set assigned date if not provided
     */
    protected function setAssignedDate(array $data)
    {
        if (!isset($data['data']['assigned_date']) || empty($data['data']['assigned_date'])) {
            $data['data']['assigned_date'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Validate due date
     */
    protected function validateDueDate(array $data)
    {
        if (isset($data['data']['due_date']) && isset($data['data']['assigned_date'])) {
            $assignedDate = strtotime($data['data']['assigned_date']);
            $dueDate = strtotime($data['data']['due_date']);

            if ($dueDate <= $assignedDate) {
                throw new \RuntimeException('Due date must be after assigned date');
            }
        }

        return $data;
    }

    /**
     * Check and update overdue status
     */
    protected function checkOverdueStatus(array $data)
    {
        // Only check overdue status if we have the due_date field
        if (!isset($data['data']['due_date'])) {
            return $data; // Skip overdue check if due_date is not provided
        }
        
        $dueDate = $data['data']['due_date'];
        $currentDate = date('Y-m-d');
        
        // If task is past due date and not completed, mark as overdue
        if ($dueDate < $currentDate && 
            isset($data['data']['status']) && 
            $data['data']['status'] !== 'completed') {
            $data['data']['status'] = 'overdue';
        }
        
        return $data;
    }

    /**
     * Get task with full details
     */
    public function getTaskWithDetails($taskId)
    {
        return $this->select('staff_tasks.*,
                            staff.full_name as staff_name,
                            staff.role as staff_role,
                            staff.phone as staff_phone,
                            staff.email as staff_email,
                            managers.full_name as manager_name,
                            managers.email as manager_email,
                            hotels.name as hotel_name,
                            hotels.city as hotel_city')
                    ->join('staff', 'staff.staff_id = staff_tasks.staff_id', 'left')
                    ->join('managers', 'managers.manager_id = staff_tasks.manager_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = staff_tasks.hotel_id', 'left')
                    ->where('staff_tasks.task_id', $taskId)
                    ->first();
    }

    /**
     * Get tasks by staff
     */
    public function getTasksByStaff($staffId, $status = null, $limit = null, $offset = null)
    {
        $builder = $this->select('staff_tasks.*,
                                managers.full_name as manager_name,
                                hotels.name as hotel_name')
                        ->join('managers', 'managers.manager_id = staff_tasks.manager_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = staff_tasks.hotel_id', 'left')
                        ->where('staff_tasks.staff_id', $staffId)
                        ->orderBy('staff_tasks.due_date', 'ASC');

        if ($status) {
            $builder->where('staff_tasks.status', $status);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get tasks assigned by a manager
     */
    public function getTasksByManager($managerId, $status = null, $hotelId = null)
    {
        $builder = $this->select('staff_tasks.*, staff.full_name as staff_name, staff.role as staff_role')
                       ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
                       ->where('staff_tasks.manager_id', $managerId);
        
        if ($status) {
            $builder->where('staff_tasks.status', $status);
        }
        
        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }
        
        return $builder->orderBy('staff_tasks.assigned_date', 'DESC')->findAll();
    }

    /**
     * Get tasks by hotel
     */
    public function getTasksByHotel($hotelId, $status = null, $managerId = null, $limit = null, $offset = null)
    {
        $builder = $this->select('staff_tasks.*,
                                staff.full_name as staff_name,
                                staff.role as staff_role,
                                managers.full_name as manager_name')
                        ->join('staff', 'staff.staff_id = staff_tasks.staff_id', 'left')
                        ->join('managers', 'managers.manager_id = staff_tasks.manager_id', 'left')
                        ->where('staff_tasks.hotel_id', $hotelId)
                        ->orderBy('staff_tasks.due_date', 'ASC');

        if ($status) {
            $builder->where('staff_tasks.status', $status);
        }

        if ($managerId) {
            $builder->where('staff_tasks.manager_id', $managerId);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get task statistics for a hotel/manager
     */
    public function getTaskStatistics($hotelId = null, $managerId = null)
    {
        $builder = $this->select('status, COUNT(*) as count')
                       ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
                       ->groupBy('staff_tasks.status');
        
        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }
        
        if ($managerId) {
            $builder->where('staff_tasks.manager_id', $managerId);
        }
        
        $results = $builder->findAll();
        
        $stats = [
            'assigned' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'overdue' => 0,
            'total' => 0
        ];
        
        foreach ($results as $result) {
            $stats[$result['status']] = $result['count'];
            $stats['total'] += $result['count'];
        }
        
        return $stats;
    }

    /**
     * Get overdue tasks
     */
    public function getOverdueTasks($hotelId = null, $managerId = null)
    {
        $builder = $this->select('staff_tasks.*, staff.full_name as staff_name')
                       ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
                       ->where('staff_tasks.due_date <', date('Y-m-d'))
                       ->where('staff_tasks.status !=', 'completed');
        
        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }
        
        if ($managerId) {
            $builder->where('staff_tasks.manager_id', $managerId);
        }
        
        return $builder->findAll();
    }

    /**
     * Get tasks due today
     */
    public function getTasksDueToday($hotelId = null, $managerId = null)
    {
        $builder = $this->select('staff_tasks.*, staff.full_name as staff_name')
                       ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
                       ->where('DATE(staff_tasks.due_date)', date('Y-m-d'))
                       ->where('staff_tasks.status !=', 'completed');
        
        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }
        
        if ($managerId) {
            $builder->where('staff_tasks.manager_id', $managerId);
        }
        
        return $builder->findAll();
    }

    /**
     * Update task status
     */
    public function updateTaskStatus($taskId, $status)
    {
        // Get the current task data first
        $currentTask = $this->find($taskId);
        
        if (!$currentTask) {
            return false;
        }
        
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // If marking as completed, also set completion date
        if ($status === 'completed') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }
        
        // Use the base update method directly to avoid the beforeUpdate callback
        return $this->update($taskId, $data);
    }

    /**
     * Reassign task
     */
    public function reassignTask($taskId, $newStaffId, $managerId)
    {
        return $this->update($taskId, [
            'staff_id' => $newStaffId,
            'status' => 'assigned',
            'assigned_date' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update overdue tasks
     */
    public function updateOverdueTasks()
    {
        $today = date('Y-m-d');

        return $this->where('due_date <', $today)
                    ->whereNotIn('status', ['completed'])
                    ->set(['status' => 'overdue'])
                    ->update();
    }

    /**
     * Search tasks
     */
    public function searchTasks($searchTerm, $hotelId = null, $managerId = null, $staffId = null, $status = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('staff_tasks.*,
                                staff.full_name as staff_name,
                                staff.role as staff_role,
                                managers.full_name as manager_name,
                                hotels.name as hotel_name')
                        ->join('staff', 'staff.staff_id = staff_tasks.staff_id', 'left')
                        ->join('managers', 'managers.manager_id = staff_tasks.manager_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = staff_tasks.hotel_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('staff_tasks.task_description', $searchTerm)
                   ->orLike('staff.full_name', $searchTerm)
                   ->orLike('managers.full_name', $searchTerm)
                   ->groupEnd();
        }

        if ($hotelId) {
            $builder->where('staff_tasks.hotel_id', $hotelId);
        }

        if ($managerId) {
            $builder->where('staff_tasks.manager_id', $managerId);
        }

        if ($staffId) {
            $builder->where('staff_tasks.staff_id', $staffId);
        }

        if ($status) {
            $builder->where('staff_tasks.status', $status);
        }

        return $builder->orderBy('staff_tasks.due_date', 'ASC')
                      ->limit($limit, $offset)
                      ->findAll();
    }

    /**
     * Get task completion rate
     */
    public function getTaskCompletionRate($hotelId = null, $managerId = null, $staffId = null, $dateFrom = null, $dateTo = null)
    {
        $stats = $this->getTaskStatistics($hotelId, $managerId, $staffId, $dateFrom, $dateTo);

        if ($stats['total'] == 0) {
            return 0;
        }

        return ($stats['completed'] / $stats['total']) * 100;
    }

    /**
     * Get most productive staff (by completed tasks)
     */
    public function getMostProductiveStaff($hotelId = null, $managerId = null, $dateFrom = null, $dateTo = null, $limit = 10)
    {
        $builder = $this->select('staff.staff_id,
                                staff.full_name,
                                staff.role,
                                COUNT(staff_tasks.task_id) as completed_tasks,
                                hotels.name as hotel_name')
                        ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
                        ->join('hotels', 'hotels.hotel_id = staff_tasks.hotel_id', 'left')
                        ->where('staff_tasks.status', 'completed')
                        ->groupBy('staff.staff_id')
                        ->orderBy('completed_tasks', 'DESC')
                        ->limit($limit);

        if ($hotelId) {
            $builder->where('staff_tasks.hotel_id', $hotelId);
        }

        if ($managerId) {
            $builder->where('staff_tasks.manager_id', $managerId);
        }

        if ($dateFrom) {
            $builder->where('staff_tasks.assigned_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('staff_tasks.assigned_date <=', $dateTo);
        }

        return $builder->findAll();
    }

    /**
     * Get task workload distribution
     */
    public function getWorkloadDistribution($hotelId = null, $managerId = null)
    {
        $builder = $this->select('staff.staff_id,
                                staff.full_name,
                                staff.role,
                                COUNT(CASE WHEN staff_tasks.status IN ("assigned", "in_progress") THEN 1 END) as active_tasks,
                                COUNT(CASE WHEN staff_tasks.status = "completed" THEN 1 END) as completed_tasks,
                                COUNT(CASE WHEN staff_tasks.status = "overdue" THEN 1 END) as overdue_tasks')
                        ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
                        ->groupBy('staff.staff_id')
                        ->orderBy('active_tasks', 'DESC');

        if ($hotelId) {
            $builder->where('staff_tasks.hotel_id', $hotelId);
        }

        if ($managerId) {
            $builder->where('staff_tasks.manager_id', $managerId);
        }

        return $builder->findAll();
    }
    



}
