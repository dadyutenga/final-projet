<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomTypeModel extends Model
{
    protected $table            = 'room_types';
    protected $primaryKey       = 'room_type_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'hotel_id',
        'type_name',
        'description',
        'base_price',
        'capacity'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'hotel_id'    => 'permit_empty|is_natural_no_zero',
        'type_name'   => 'required|max_length[50]',
        'description' => 'permit_empty',
        'base_price'  => 'required|decimal|greater_than[0]',
        'capacity'    => 'required|is_natural_no_zero'
    ];
    protected $validationMessages   = [
        'type_name' => [
            'required'    => 'Room type name is required',
            'max_length'  => 'Room type name cannot exceed 50 characters'
        ],
        'base_price' => [
            'required'     => 'Base price is required',
            'decimal'      => 'Base price must be a valid decimal number',
            'greater_than' => 'Base price must be greater than 0'
        ],
        'capacity' => [
            'required'           => 'Capacity is required',
            'is_natural_no_zero' => 'Capacity must be a positive number'
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
     * Get room type with hotel details
     */
    public function getRoomTypeWithHotel($roomTypeId)
    {
        return $this->select('room_types.*, hotels.name as hotel_name, hotels.city, hotels.country')
                    ->join('hotels', 'hotels.hotel_id = room_types.hotel_id', 'left')
                    ->where('room_types.room_type_id', $roomTypeId)
                    ->first();
    }

    /**
     * Get room types by hotel
     */
    public function getRoomTypesByHotel($hotelId)
    {
        return $this->where('hotel_id', $hotelId)
                    ->orderBy('base_price', 'ASC')
                    ->findAll();
    }

    /**
     * Get room types with room counts
     */
    public function getRoomTypesWithCounts($hotelId = null)
    {
        $builder = $this->select('room_types.*,
                                COUNT(rooms.room_id) as total_rooms,
                                COUNT(CASE WHEN rooms.status = "available" THEN 1 END) as available_rooms,
                                COUNT(CASE WHEN rooms.status = "occupied" THEN 1 END) as occupied_rooms,
                                COUNT(CASE WHEN rooms.status = "maintenance" THEN 1 END) as maintenance_rooms')
                        ->join('rooms', 'rooms.room_type_id = room_types.room_type_id', 'left')
                        ->groupBy('room_types.room_type_id')
                        ->orderBy('room_types.base_price', 'ASC');

        if ($hotelId) {
            $builder->where('room_types.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get available room types for date range
     */
    public function getAvailableRoomTypes($hotelId, $checkIn, $checkOut, $guestCount = 1)
    {
        return $this->select('room_types.*,
                            COUNT(rooms.room_id) as total_rooms,
                            COUNT(CASE WHEN rooms.status = "available" THEN 1 END) as available_rooms,
                            (COUNT(CASE WHEN rooms.status = "available" THEN 1 END) -
                             COALESCE(occupied_count.occupied, 0)) as rooms_available_for_dates')
                    ->join('rooms', 'rooms.room_type_id = room_types.room_type_id', 'left')
                    ->join('(SELECT room_type_id, COUNT(*) as occupied
                             FROM rooms r
                             INNER JOIN reservations res ON r.room_id = res.room_id
                             WHERE res.status NOT IN ("cancelled")
                             AND res.check_in_date <= "' . $checkOut . '"
                             AND res.check_out_date >= "' . $checkIn . '"
                             GROUP BY room_type_id) occupied_count',
                           'occupied_count.room_type_id = room_types.room_type_id', 'left')
                    ->where('room_types.hotel_id', $hotelId)
                    ->where('room_types.capacity >=', $guestCount)
                    ->groupBy('room_types.room_type_id')
                    ->having('rooms_available_for_dates >', 0)
                    ->orderBy('room_types.base_price', 'ASC')
                    ->findAll();
    }

    /**
     * Get room type statistics
     */
    public function getRoomTypeStatistics($roomTypeId)
    {
        $roomType = $this->find($roomTypeId);
        if (!$roomType) {
            return null;
        }

        $stats = [];

        // Room counts
        $roomCounts = $this->db->table('rooms')
                              ->select('status, COUNT(*) as count')
                              ->where('room_type_id', $roomTypeId)
                              ->groupBy('status')
                              ->get()
                              ->getResultArray();

        $stats['room_counts'] = [
            'available' => 0,
            'occupied' => 0,
            'maintenance' => 0,
            'total' => 0
        ];

        foreach ($roomCounts as $count) {
            $stats['room_counts'][$count['status']] = $count['count'];
            $stats['room_counts']['total'] += $count['count'];
        }

        // Reservation statistics
        $stats['total_reservations'] = $this->db->table('reservations')
                                               ->join('rooms', 'rooms.room_id = reservations.room_id')
                                               ->where('rooms.room_type_id', $roomTypeId)
                                               ->countAllResults();

        // Revenue statistics (last 30 days)
        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
        $stats['revenue_last_30_days'] = $this->db->table('reservations')
                                                 ->selectSum('total_price')
                                                 ->join('rooms', 'rooms.room_id = reservations.room_id')
                                                 ->where('rooms.room_type_id', $roomTypeId)
                                                 ->where('reservations.status', 'completed')
                                                 ->where('reservations.check_out_date >=', $thirtyDaysAgo)
                                                 ->get()
                                                 ->getRow()
                                                 ->total_price ?? 0;

        return $stats;
    }

    /**
     * Search room types
     */
    public function searchRoomTypes($searchTerm, $hotelId = null, $minPrice = null, $maxPrice = null, $minCapacity = null)
    {
        $builder = $this->select('room_types.*, hotels.name as hotel_name')
                        ->join('hotels', 'hotels.hotel_id = room_types.hotel_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('room_types.type_name', $searchTerm)
                   ->orLike('room_types.description', $searchTerm)
                   ->groupEnd();
        }

        if ($hotelId) {
            $builder->where('room_types.hotel_id', $hotelId);
        }

        if ($minPrice !== null) {
            $builder->where('room_types.base_price >=', $minPrice);
        }

        if ($maxPrice !== null) {
            $builder->where('room_types.base_price <=', $maxPrice);
        }

        if ($minCapacity !== null) {
            $builder->where('room_types.capacity >=', $minCapacity);
        }

        return $builder->orderBy('room_types.base_price', 'ASC')->findAll();
    }

    /**
     * Get most popular room types
     */
    public function getMostPopularRoomTypes($hotelId = null, $limit = 10)
    {
        $builder = $this->select('room_types.*, COUNT(reservations.reservation_id) as booking_count')
                        ->join('rooms', 'rooms.room_type_id = room_types.room_type_id')
                        ->join('reservations', 'reservations.room_id = rooms.room_id')
                        ->where('reservations.status !=', 'cancelled')
                        ->groupBy('room_types.room_type_id')
                        ->orderBy('booking_count', 'DESC')
                        ->limit($limit);

        if ($hotelId) {
            $builder->where('room_types.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get room types by price range
     */
    public function getRoomTypesByPriceRange($minPrice, $maxPrice, $hotelId = null)
    {
        $builder = $this->where('base_price >=', $minPrice)
                        ->where('base_price <=', $maxPrice)
                        ->orderBy('base_price', 'ASC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get room types by capacity
     */
    public function getRoomTypesByCapacity($capacity, $hotelId = null)
    {
        $builder = $this->where('capacity >=', $capacity)
                        ->orderBy('capacity', 'ASC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Clone room type to another hotel
     */
    public function cloneRoomType($roomTypeId, $targetHotelId)
    {
        $roomType = $this->find($roomTypeId);
        if (!$roomType) {
            return false;
        }

        // Remove the primary key and set new hotel_id
        unset($roomType['room_type_id']);
        $roomType['hotel_id'] = $targetHotelId;

        return $this->insert($roomType);
    }
}
