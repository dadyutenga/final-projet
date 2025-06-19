<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'password_hash',
        'email',
        'full_name',
        'phone',
        'address'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username'      => 'required|min_length[3]|max_length[50]|is_unique[users.username,user_id,{user_id}]',
        'password_hash' => 'required|min_length[8]',
        'email'         => 'required|valid_email|max_length[100]|is_unique[users.email,user_id,{user_id}]',
        'full_name'     => 'required|max_length[100]',
        'phone'         => 'permit_empty|max_length[20]',
        'address'       => 'permit_empty|max_length[255]'
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
        ],
        'address' => [
            'max_length'  => 'Address cannot exceed 255 characters'
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
     * Verify user credentials
     */
    public function verifyCredentials($username, $password)
    {
        $user = $this->where('username', $username)
                     ->orWhere('email', $username)
                     ->first();

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return false;
    }

    /**
     * Get user with reservation count
     */
    public function getUserWithReservationCount($userId)
    {
        return $this->select('users.*, COUNT(reservations.reservation_id) as reservation_count')
                    ->join('reservations', 'reservations.user_id = users.user_id', 'left')
                    ->where('users.user_id', $userId)
                    ->groupBy('users.user_id')
                    ->first();
    }

    /**
     * Get user reservations
     */
    public function getUserReservations($userId, $limit = null, $offset = null)
    {
        $builder = $this->db->table('reservations')
                           ->select('reservations.*, hotels.name as hotel_name, hotels.city, hotels.country, rooms.room_number')
                           ->join('hotels', 'hotels.hotel_id = reservations.hotel_id')
                           ->join('rooms', 'rooms.room_id = reservations.room_id')
                           ->where('reservations.user_id', $userId)
                           ->orderBy('reservations.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get user reviews
     */
    public function getUserReviews($userId, $limit = null, $offset = null)
    {
        $builder = $this->db->table('reviews')
                           ->select('reviews.*, hotels.name as hotel_name, hotels.city, hotels.country')
                           ->join('hotels', 'hotels.hotel_id = reviews.hotel_id')
                           ->where('reviews.user_id', $userId)
                           ->orderBy('reviews.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Update user profile
     */
    public function updateProfile($userId, $data)
    {
        // Remove password from data if it's empty
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }

        return $this->update($userId, $data);
    }

    /**
     * Search users
     */
    public function searchUsers($searchTerm, $limit = 20, $offset = 0)
    {
        return $this->like('username', $searchTerm)
                    ->orLike('email', $searchTerm)
                    ->orLike('full_name', $searchTerm)
                    ->limit($limit, $offset)
                    ->findAll();
    }

    /**
     * Get users with recent activity
     */
    public function getUsersWithRecentActivity($days = 30)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        return $this->select('users.*, MAX(reservations.created_at) as last_reservation')
                    ->join('reservations', 'reservations.user_id = users.user_id', 'left')
                    ->where('reservations.created_at >=', $date)
                    ->groupBy('users.user_id')
                    ->orderBy('last_reservation', 'DESC')
                    ->findAll();
    }
}
