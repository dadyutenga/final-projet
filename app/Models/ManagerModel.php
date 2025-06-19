<?php

namespace App\Models;

use CodeIgniter\Model;

class ManagerModel extends Model
{
    protected $table            = 'managers';
    protected $primaryKey       = 'manager_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'hotel_id',
        'username',
        'password_hash',
        'email',
        'full_name',
        'phone'
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
        'username'      => 'required|min_length[3]|max_length[50]|is_unique[managers.username,manager_id,{manager_id}]',
        'password_hash' => 'required|min_length[8]',
        'email'         => 'required|valid_email|max_length[100]|is_unique[managers.email,manager_id,{manager_id}]',
        'full_name'     => 'required|max_length[100]',
        'phone'         => 'permit_empty|max_length[20]'
    ];
    protected $validationMessages   = [
        'username' => [
            'required'    => 'Username is required',
            'min_length'  => 'Username must be at least 3 characters long',
            'max_length'  => 'Username cannot exceed 50 characters',
            'is_unique'   => 'Username already exists'
        ],
        'email' => [
            'required'     => 'Email is required',
            'valid_email'  => 'Please enter a valid email address',
            'max_length'   => 'Email cannot exceed 100 characters',
            'is_unique'    => 'Email already exists'
        ],
        'password_hash' => [
            'required'    => 'Password is required',
            'min_length'  => 'Password must be at least 8 characters long'
        ],
        'full_name' => [
            'required'    => 'Full name is required',
            'max_length'  => 'Full name cannot exceed 100 characters'
        ],
        'phone' => [
            'max_length'  => 'Phone number cannot exceed 20 characters'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Hash password before insert/update
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            unset($data['data']['password']);
        }
        return $data;
    }

    /**
     * Get manager with hotel details
     */
    public function getManagerWithHotel($managerId)
    {
        return $this->select('managers.*, hotels.name as hotel_name, hotels.city, hotels.country, hotels.address')
                    ->join('hotels', 'hotels.hotel_id = managers.hotel_id', 'left')
                    ->where('managers.manager_id', $managerId)
                    ->first();
    }

    /**
     * Get managers by hotel
     */
    public function getManagersByHotel($hotelId)
    {
        return $this->where('hotel_id', $hotelId)->findAll();
    }

    /**
     * Verify manager credentials
     */
    public function verifyCredentials($username, $password)
    {
        $manager = $this->where('username', $username)
                        ->orWhere('email', $username)
                        ->first();

        if ($manager && password_verify($password, $manager['password_hash'])) {
            return $manager;
        }

        return false;
    }

    /**
     * Get manager with staff count
     */
    public function getManagerWithStaffCount($managerId)
    {
        return $this->select('managers.*, COUNT(staff.staff_id) as staff_count')
                    ->join('staff', 'staff.manager_id = managers.manager_id', 'left')
                    ->where('managers.manager_id', $managerId)
                    ->groupBy('managers.manager_id')
                    ->first();
    }

    /**
     * Get manager staff
     */
    public function getManagerStaff($managerId, $limit = null, $offset = null)
    {
        $builder = $this->db->table('staff')
                           ->select('staff.*, hotels.name as hotel_name')
                           ->join('hotels', 'hotels.hotel_id = staff.hotel_id', 'left')
                           ->where('staff.manager_id', $managerId)
                           ->orderBy('staff.full_name', 'ASC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get manager tasks
     */
    public function getManagerTasks($managerId, $status = null, $limit = null, $offset = null)
    {
        $builder = $this->db->table('staff_tasks')
                           ->select('staff_tasks.*, staff.full_name as staff_name, staff.role as staff_role, hotels.name as hotel_name')
                           ->join('staff', 'staff.staff_id = staff_tasks.staff_id', 'left')
                           ->join('hotels', 'hotels.hotel_id = staff_tasks.hotel_id', 'left')
                           ->where('staff_tasks.manager_id', $managerId)
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
     * Get manager statistics
     */
    public function getManagerStatistics($managerId)
    {
        $manager = $this->find($managerId);
        if (!$manager) {
            return null;
        }

        $stats = [];

        // Staff count
        $stats['staff_count'] = $this->db->table('staff')
                                        ->where('manager_id', $managerId)
                                        ->countAllResults();

        // Tasks statistics
        $stats['total_tasks'] = $this->db->table('staff_tasks')
                                        ->where('manager_id', $managerId)
                                        ->countAllResults();

        $stats['completed_tasks'] = $this->db->table('staff_tasks')
                                            ->where('manager_id', $managerId)
                                            ->where('status', 'completed')
                                            ->countAllResults();

        $stats['pending_tasks'] = $this->db->table('staff_tasks')
                                          ->where('manager_id', $managerId)
                                          ->whereIn('status', ['assigned', 'in_progress'])
                                          ->countAllResults();

        $stats['overdue_tasks'] = $this->db->table('staff_tasks')
                                          ->where('manager_id', $managerId)
                                          ->where('status', 'overdue')
                                          ->countAllResults();

        // Hotel reservations (if manager has a hotel)
        if ($manager['hotel_id']) {
            $stats['hotel_reservations'] = $this->db->table('reservations')
                                                   ->where('hotel_id', $manager['hotel_id'])
                                                   ->countAllResults();

            $stats['hotel_rooms'] = $this->db->table('rooms')
                                            ->where('hotel_id', $manager['hotel_id'])
                                            ->countAllResults();
        }

        return $stats;
    }

    /**
     * Update manager profile
     */
    public function updateProfile($managerId, $data)
    {
        // Remove password from data if it's empty
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }

        return $this->update($managerId, $data);
    }

    /**
     * Search managers
     */
    public function searchManagers($searchTerm, $hotelId = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('managers.*, hotels.name as hotel_name')
                        ->join('hotels', 'hotels.hotel_id = managers.hotel_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('managers.username', $searchTerm)
                   ->orLike('managers.email', $searchTerm)
                   ->orLike('managers.full_name', $searchTerm)
                   ->groupEnd();
        }

        if ($hotelId) {
            $builder->where('managers.hotel_id', $hotelId);
        }

        return $builder->limit($limit, $offset)->findAll();
    }

    /**
     * Get managers with recent activity
     */
    public function getManagersWithRecentActivity($days = 30)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        return $this->select('managers.*, hotels.name as hotel_name, MAX(staff_tasks.assigned_date) as last_task_assigned')
                    ->join('hotels', 'hotels.hotel_id = managers.hotel_id', 'left')
                    ->join('staff_tasks', 'staff_tasks.manager_id = managers.manager_id', 'left')
                    ->where('staff_tasks.assigned_date >=', $date)
                    ->groupBy('managers.manager_id')
                    ->orderBy('last_task_assigned', 'DESC')
                    ->findAll();
    }
}
