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
        'hire_date',
        'username',
        'password_hash'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'hotel_id'      => 'permit_empty|is_natural_no_zero',
        'manager_id'    => 'permit_empty|is_natural_no_zero',
        'full_name'     => 'required|max_length[100]',
        'role'          => 'required|max_length[50]',
        'phone'         => 'permit_empty|max_length[20]',
        'email'         => 'permit_empty|valid_email|max_length[100]',
        'hire_date'     => 'permit_empty|valid_date',
        'username'      => 'required|min_length[3]|max_length[50]|is_unique[staff.username,staff_id,{staff_id}]',
        'password_hash' => 'permit_empty'
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
        'username' => [
            'required'    => 'Username is required',
            'min_length'  => 'Username must be at least 3 characters long',
            'max_length'  => 'Username cannot exceed 50 characters',
            'is_unique'   => 'Username already exists'
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address',
            'max_length'  => 'Email cannot exceed 100 characters'
        ],
        'phone' => [
            'max_length'  => 'Phone number cannot exceed 20 characters'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks - Remove password hashing callback since we do it manually
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
     * Verify staff credentials
     */
    public function verifyCredentials($username, $password)
    {
        $staff = $this->where('username', $username)->first();

        if ($staff && password_verify($password, $staff['password_hash'])) {
            return $staff;
        }

        return false;
    }

    /**
     * Get staff with details
     */
    public function getStaffWithDetails($staffId)
    {
        return $this->select('staff.*, hotels.name as hotel_name, managers.username as manager_name')
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
        $builder = $this->where('hotel_id', $hotelId);
        
        if ($role) {
            $builder->where('role', $role);
        }
        
        if ($managerId) {
            $builder->where('manager_id', $managerId);
        }
        
        return $builder->findAll();
    }

    /**
     * Get staff by manager
     */
    public function getStaffByManager($managerId)
    {
        return $this->where('manager_id', $managerId)->findAll();
    }

    /**
     * Get staff by role
     */
    public function getStaffByRole($role, $hotelId = null)
    {
        $builder = $this->where('role', $role);
        
        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }
        
        return $builder->findAll();
    }

    /**
     * Get available roles
     */
    public function getAvailableRoles($hotelId = null)
    {
        $builder = $this->select('role')->distinct();
        
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
                        ->groupBy('role');
        
        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }
        
        if ($managerId) {
            $builder->where('manager_id', $managerId);
        }
        
        return $builder->findAll();
    }

    /**
     * Get staff with task counts
     */
    public function getStaffWithTaskCounts($hotelId = null, $managerId = null)
    {
        $builder = $this->select('staff.*, COUNT(staff_tasks.task_id) as task_count')
                        ->join('staff_tasks', 'staff_tasks.staff_id = staff.staff_id', 'left')
                        ->groupBy('staff.staff_id');
        
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
                           ->select('staff_tasks.*, staff.full_name as staff_name')
                           ->join('staff', 'staff.staff_id = staff_tasks.staff_id')
                           ->where('staff_tasks.staff_id', $staffId);
        
        if ($status) {
            $builder->where('staff_tasks.status', $status);
        }
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResultArray();
    }
}
