<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admins';
    protected $primaryKey       = 'admin_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'password_hash',
        'email',
        'full_name'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username'      => 'required|min_length[3]|max_length[50]|is_unique[admins.username,admin_id,{admin_id}]',
        'password_hash' => 'required|min_length[8]',
        'email'         => 'required|valid_email|max_length[100]|is_unique[admins.email,admin_id,{admin_id}]',
        'full_name'     => 'required|max_length[100]'
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
     * Get admin with hotels
     */
    public function getAdminWithHotels($adminId)
    {
        return $this->select('admins.*, COUNT(hotels.hotel_id) as hotel_count')
                    ->join('hotels', 'hotels.admin_id = admins.admin_id', 'left')
                    ->where('admins.admin_id', $adminId)
                    ->groupBy('admins.admin_id')
                    ->first();
    }

    /**
     * Get all admins with hotel counts
     */
    public function getAdminsWithHotelCounts()
    {
        return $this->select('admins.*, COUNT(hotels.hotel_id) as hotel_count')
                    ->join('hotels', 'hotels.admin_id = admins.admin_id', 'left')
                    ->groupBy('admins.admin_id')
                    ->findAll();
    }

    /**
     * Verify admin credentials
     */
    public function verifyCredentials($username, $password)
    {
        $admin = $this->where('username', $username)
                      ->orWhere('email', $username)
                      ->first();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            return $admin;
        }

        return false;
    }

    /**
     * Update admin profile
     */
    public function updateProfile($adminId, $data)
    {
        // Remove password from data if it's empty
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }

        return $this->update($adminId, $data);
    }
}
