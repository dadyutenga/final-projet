<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table            = 'staff';
    protected $primaryKey       = 'staff_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'hotel_id',
        'manager_id',
        'full_name',
        'role',
        'phone',
        'email',
        'hire_date'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'hotel_id'   => 'permit_empty|is_natural_no_zero',
        'manager_id' => 'permit_empty|is_natural_no_zero',
        'full_name'  => 'required|max_length[100]',
        'role'       => 'required|max_length[50]',
        'phone'      => 'permit_empty|max_length[20]',
        'email'      => 'permit_empty|valid_email|max_length[100]',
        'hire_date'  => 'permit_empty|valid_date'
    ];
    protected $validationMessages   = [
        'full_name' => [
            'required'    => 'Full name is required',
            'max_length'  => 'Full name cannot exceed 100 characters'
        ],
        'role' => [
            'required'    => 'Role is required',
            'max_length'  => 'Role cannot exceed 50 characters'
        ],
        'phone' => [
            'max_length'  => 'Phone number cannot exceed 20 characters'
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address',
            'max_length'  => 'Email cannot exceed 100 characters'
        ],
        'hire_date' => [
            'valid_date'  => 'Please enter a valid hire date'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get staff with hotel and manager details
     */
    public function getStaffWithDetails($staffId)
    {
        return $this->select('staff.*,
                            hotels.name as hotel_name,
                            hotels.city as hotel_city,
                            hotels.country as hotel_country,
                            managers.full_name as manager_name,
                            managers.email as manager_email')
                    ->join('hotels', 'hotels.hotel_id = staff.hotel_id', 'left')
                    ->join('managers', 'managers.manager_id = staff.manager_id', 'left')
                    ->where('staff.staff_id', $staffId)
                    ->first();
    }

    /**
     * Get staff by hotel
     */
    public function getStaffByHotel($hotelId, $role = null, $managerId = null)
    {
        $builder = $this->select('staff.*,
                                managers.full_name as manager_name')
                        ->join('managers', 'managers.manager_id = staff.manager_id', 'left')
                        ->where('staff.hotel_id', $hotelId)
                        ->orderBy('staff.role', 'ASC')
                        ->orderBy('staff.full_name', 'ASC');

        if ($role) {
            $builder->where('staff.role', $role);
        }

        if ($managerId) {
            $builder->where('staff.manager_id', $managerId);
        }

        return $builder->findAll();
    }

    /**
     * Get staff by manager
     */
    public function getStaffByManager($managerId)
    {
        return $this->select('staff.*,
                            hotels.name as hotel_name')
                    ->join('hotels', 'hotels.hotel_id = staff.hotel_id', 'left')
                    ->where('staff.manager_id', $managerId)
                    ->orderBy('staff.role', 'ASC')
                    ->orderBy('staff.full_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get staff by role
     */
    public function getStaffByRole($role, $hotelId = null)
    {
        $builder = $this->select('staff.*,
                                hotels.name as hotel_name,
                                managers.full_name as manager_name')
                        ->join('hotels', 'hotels.hotel_id = staff.hotel_id', 'left')
                        ->join('managers', 'managers.manager_id = staff.manager_id', 'left')
                        ->where('staff.role', $role)
                        ->orderBy('staff.full_name', 'ASC');

        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get available roles
     */
    public function getAvailableRoles($hotelId = null)
    {
        $builder = $this->select('role')
                        ->distinct()
                        ->orderBy('role', 'ASC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get staff statistics
     */
    public function getStaffStatistics($hotelId = null, $managerId = null)
    {
        $builder = $this->select('role, COUNT(*) as count')
                        ->groupBy('role')
                        ->orderBy('role', 'ASC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        if ($managerId) {
            $builder->where('manager_id', $managerId);
        }

        $results = $builder->findAll();

        $stats = [
            'total_staff' => 0,
            'by_role' => []
        ];

        foreach ($results as $result) {
            $stats['by_role'][$result['role']] = $result['count'];
            $stats['total_staff'] += $result['count'];
        }

        return $stats;
    }

    /**
     * Get staff with task counts
     */
    public function getStaffWithTaskCounts($hotelId = null, $managerId = null)
    {
        $builder = $this->select('staff.*,
                                COUNT(staff_tasks.task_id) as total_tasks,
                                COUNT(CASE WHEN staff_tasks.status = "assigned" THEN 1 END) as assigned_tasks,
                                COUNT(CASE WHEN staff_tasks.status = "in_progress" THEN 1 END) as inprogress_tasks,
                                COUNT(CASE WHEN staff_tasks.status = "completed" THEN 1 END) as completed_tasks,
                                COUNT(CASE WHEN staff_tasks.status = "overdue" THEN 1 END) as overdue_tasks,
                                hotels.name as hotel_name,
                                managers.full_name as manager_name')
                        ->join('staff_tasks', 'staff_tasks.staff_id = staff.staff_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = staff.hotel_id', 'left')
                        ->join('managers', 'managers.manager_id = staff.manager_id', 'left')
                        ->groupBy('staff.staff_id')
                        ->orderBy('staff.full_name', 'ASC');

        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }

        if ($managerId) {
            $builder->where('staff.manager_id', $managerId);
        }

        return $builder->findAll();
    }

    /**
     * Get staff tasks
     */
    public function getStaffTasks($staffId, $status = null, $limit = null, $offset = null)
    {
        $builder = $this->db->table('staff_tasks')
                           ->select('staff_tasks.*,
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

        return $builder->get()->getResultArray();
    }

    /**
     * Get staff performance metrics
     */
    public function getStaffPerformance($staffId, $dateFrom = null, $dateTo = null)
    {
        $staff = $this->find($staffId);
        if (!$staff) {
            return null;
        }

        $builder = $this->db->table('staff_tasks')
                           ->select('status, COUNT(*) as count')
                           ->where('staff_id', $staffId)
                           ->groupBy('status');

        if ($dateFrom) {
            $builder->where('assigned_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('assigned_date <=', $dateTo);
        }

        $results = $builder->get()->getResultArray();

        $performance = [
            'staff_info' => $staff,
            'task_stats' => [
                'assigned' => 0,
                'in_progress' => 0,
                'completed' => 0,
                'overdue' => 0,
                'total' => 0
            ]
        ];

        foreach ($results as $result) {
            $performance['task_stats'][$result['status']] = $result['count'];
            $performance['task_stats']['total'] += $result['count'];
        }

        // Calculate completion rate
        $performance['completion_rate'] = $performance['task_stats']['total'] > 0 ?
            ($performance['task_stats']['completed'] / $performance['task_stats']['total']) * 100 : 0;

        return $performance;
    }

    /**
     * Search staff
     */
    public function searchStaff($searchTerm, $hotelId = null, $role = null, $managerId = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('staff.*,
                                hotels.name as hotel_name,
                                managers.full_name as manager_name')
                        ->join('hotels', 'hotels.hotel_id = staff.hotel_id', 'left')
                        ->join('managers', 'managers.manager_id = staff.manager_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('staff.full_name', $searchTerm)
                   ->orLike('staff.role', $searchTerm)
                   ->orLike('staff.email', $searchTerm)
                   ->orLike('staff.phone', $searchTerm)
                   ->groupEnd();
        }

        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }

        if ($role) {
            $builder->where('staff.role', $role);
        }

        if ($managerId) {
            $builder->where('staff.manager_id', $managerId);
        }

        return $builder->orderBy('staff.full_name', 'ASC')
                      ->limit($limit, $offset)
                      ->findAll();
    }

    /**
     * Get recent hires
     */
    public function getRecentHires($hotelId = null, $days = 30, $limit = 10)
    {
        $dateFrom = date('Y-m-d', strtotime("-{$days} days"));

        $builder = $this->select('staff.*,
                                hotels.name as hotel_name,
                                managers.full_name as manager_name')
                        ->join('hotels', 'hotels.hotel_id = staff.hotel_id', 'left')
                        ->join('managers', 'managers.manager_id = staff.manager_id', 'left')
                        ->where('staff.hire_date >=', $dateFrom)
                        ->orderBy('staff.hire_date', 'DESC')
                        ->limit($limit);

        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get staff anniversaries
     */
    public function getStaffAnniversaries($hotelId = null, $month = null)
    {
        if (!$month) {
            $month = date('m');
        }

        $builder = $this->select('staff.*,
                                hotels.name as hotel_name,
                                managers.full_name as manager_name,
                                YEAR(CURDATE()) - YEAR(hire_date) as years_of_service')
                        ->join('hotels', 'hotels.hotel_id = staff.hotel_id', 'left')
                        ->join('managers', 'managers.manager_id = staff.manager_id', 'left')
                        ->where('MONTH(hire_date)', $month)
                        ->where('hire_date IS NOT NULL')
                        ->orderBy('hire_date', 'ASC');

        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Update staff role
     */
    public function updateStaffRole($staffId, $newRole)
    {
        return $this->update($staffId, ['role' => $newRole]);
    }

    /**
     * Transfer staff to different manager
     */
    public function transferStaff($staffId, $newManagerId)
    {
        return $this->update($staffId, ['manager_id' => $newManagerId]);
    }

    /**
     * Get staff workload (based on active tasks)
     */
    public function getStaffWorkload($hotelId = null, $managerId = null)
    {
        $builder = $this->select('staff.*,
                                COUNT(CASE WHEN staff_tasks.status IN ("assigned", "in_progress") THEN 1 END) as active_tasks,
                                hotels.name as hotel_name')
                        ->join('staff_tasks', 'staff_tasks.staff_id = staff.staff_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = staff.hotel_id', 'left')
                        ->groupBy('staff.staff_id')
                        ->orderBy('active_tasks', 'DESC');

        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }

        if ($managerId) {
            $builder->where('staff.manager_id', $managerId);
        }

        return $builder->findAll();
    }

    /**
     * Get staff without manager
     */
    public function getStaffWithoutManager($hotelId = null)
    {
        $builder = $this->select('staff.*,
                                hotels.name as hotel_name')
                        ->join('hotels', 'hotels.hotel_id = staff.hotel_id', 'left')
                        ->where('staff.manager_id IS NULL')
                        ->orderBy('staff.full_name', 'ASC');

        if ($hotelId) {
            $builder->where('staff.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }
}
