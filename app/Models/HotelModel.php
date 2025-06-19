<?php

namespace App\Models;

use CodeIgniter\Model;

class HotelModel extends Model
{
    protected $table            = 'hotels';
    protected $primaryKey       = 'hotel_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'admin_id',
        'name',
        'address',
        'hotel_logo',
        'city',
        'country',
        'phone',
        'email'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'admin_id'    => 'permit_empty|is_natural_no_zero',
        'name'        => 'required|max_length[100]',
        'address'     => 'required|max_length[255]',
        'hotel_logo'  => 'required|max_length[255]',
        'city'        => 'required|max_length[50]',
        'country'     => 'required|max_length[50]',
        'phone'       => 'permit_empty|max_length[20]',
        'email'       => 'permit_empty|valid_email|max_length[100]'
    ];
    protected $validationMessages   = [
        'name' => [
            'required'    => 'Hotel name is required',
            'max_length'  => 'Hotel name cannot exceed 100 characters'
        ],
        'address' => [
            'required'    => 'Address is required',
            'max_length'  => 'Address cannot exceed 255 characters'
        ],
        'hotel_logo' => [
            'required'    => 'Hotel logo is required',
            'max_length'  => 'Hotel logo path cannot exceed 255 characters'
        ],
        'city' => [
            'required'    => 'City is required',
            'max_length'  => 'City cannot exceed 50 characters'
        ],
        'country' => [
            'required'    => 'Country is required',
            'max_length'  => 'Country cannot exceed 50 characters'
        ],
        'phone' => [
            'max_length'  => 'Phone number cannot exceed 20 characters'
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address',
            'max_length'  => 'Email cannot exceed 100 characters'
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
     * Get hotel with admin details
     */
    public function getHotelWithAdmin($hotelId)
    {
        return $this->select('hotels.*, admins.full_name as admin_name, admins.email as admin_email')
                    ->join('admins', 'admins.admin_id = hotels.admin_id', 'left')
                    ->where('hotels.hotel_id', $hotelId)
                    ->first();
    }

    /**
     * Get hotels by admin
     */
    public function getHotelsByAdmin($adminId)
    {
        return $this->where('admin_id', $adminId)->findAll();
    }

    /**
     * Get hotels with room counts
     */
    public function getHotelsWithRoomCounts()
    {
        return $this->select('hotels.*, COUNT(rooms.room_id) as room_count')
                    ->join('rooms', 'rooms.hotel_id = hotels.hotel_id', 'left')
                    ->groupBy('hotels.hotel_id')
                    ->findAll();
    }

    /**
     * Get hotel with statistics
     */
    public function getHotelWithStats($hotelId)
    {
        return $this->select('hotels.*,
                            COUNT(DISTINCT rooms.room_id) as total_rooms,
                            COUNT(DISTINCT reservations.reservation_id) as total_reservations,
                            COUNT(DISTINCT reviews.review_id) as total_reviews,
                            AVG(reviews.rating) as average_rating')
                    ->join('rooms', 'rooms.hotel_id = hotels.hotel_id', 'left')
                    ->join('reservations', 'reservations.hotel_id = hotels.hotel_id', 'left')
                    ->join('reviews', 'reviews.hotel_id = hotels.hotel_id', 'left')
                    ->where('hotels.hotel_id', $hotelId)
                    ->groupBy('hotels.hotel_id')
                    ->first();
    }

    /**
     * Search hotels
     */
    public function searchHotels($searchTerm, $city = null, $country = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('hotels.*, AVG(reviews.rating) as average_rating, COUNT(reviews.review_id) as review_count')
                        ->join('reviews', 'reviews.hotel_id = hotels.hotel_id', 'left')
                        ->groupBy('hotels.hotel_id');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('hotels.name', $searchTerm)
                   ->orLike('hotels.address', $searchTerm)
                   ->orLike('hotels.city', $searchTerm)
                   ->orLike('hotels.country', $searchTerm)
                   ->groupEnd();
        }

        if (!empty($city)) {
            $builder->where('hotels.city', $city);
        }

        if (!empty($country)) {
            $builder->where('hotels.country', $country);
        }

        return $builder->limit($limit, $offset)->findAll();
    }

    /**
     * Get hotels by city
     */
    public function getHotelsByCity($city, $limit = null, $offset = null)
    {
        $builder = $this->select('hotels.*, AVG(reviews.rating) as average_rating, COUNT(reviews.review_id) as review_count')
                        ->join('reviews', 'reviews.hotel_id = hotels.hotel_id', 'left')
                        ->where('hotels.city', $city)
                        ->groupBy('hotels.hotel_id')
                        ->orderBy('average_rating', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get hotels by country
     */
    public function getHotelsByCountry($country, $limit = null, $offset = null)
    {
        $builder = $this->select('hotels.*, AVG(reviews.rating) as average_rating, COUNT(reviews.review_id) as review_count')
                        ->join('reviews', 'reviews.hotel_id = hotels.hotel_id', 'left')
                        ->where('hotels.country', $country)
                        ->groupBy('hotels.hotel_id')
                        ->orderBy('average_rating', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get top rated hotels
     */
    public function getTopRatedHotels($limit = 10)
    {
        return $this->select('hotels.*, AVG(reviews.rating) as average_rating, COUNT(reviews.review_id) as review_count')
                    ->join('reviews', 'reviews.hotel_id = hotels.hotel_id')
                    ->groupBy('hotels.hotel_id')
                    ->having('review_count >', 0)
                    ->orderBy('average_rating', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get available cities
     */
    public function getAvailableCities()
    {
        return $this->select('city')
                    ->distinct()
                    ->orderBy('city', 'ASC')
                    ->findAll();
    }

    /**
     * Get available countries
     */
    public function getAvailableCountries()
    {
        return $this->select('country')
                    ->distinct()
                    ->orderBy('country', 'ASC')
                    ->findAll();
    }

    /**
     * Get hotel availability for date range
     */
    public function getHotelAvailability($hotelId, $checkIn, $checkOut)
    {
        $totalRooms = $this->db->table('rooms')
                              ->where('hotel_id', $hotelId)
                              ->where('status', 'available')
                              ->countAllResults();

        $occupiedRooms = $this->db->table('reservations')
                                 ->where('hotel_id', $hotelId)
                                 ->where('status !=', 'cancelled')
                                 ->groupStart()
                                     ->where('check_in_date <=', $checkOut)
                                     ->where('check_out_date >=', $checkIn)
                                 ->groupEnd()
                                 ->countAllResults();

        return [
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'available_rooms' => $totalRooms - $occupiedRooms
        ];
    }

    /**
     * Get recent hotels
     */
    public function getRecentHotels($limit = 10)
    {
        return $this->select('hotels.*, AVG(reviews.rating) as average_rating, COUNT(reviews.review_id) as review_count')
                    ->join('reviews', 'reviews.hotel_id = hotels.hotel_id', 'left')
                    ->groupBy('hotels.hotel_id')
                    ->orderBy('hotels.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
