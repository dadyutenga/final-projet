<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table            = 'rooms';
    protected $primaryKey       = 'room_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'hotel_id',
        'room_type_id',
        'room_number',
        'floor',
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
        'hotel_id'     => 'required|is_natural_no_zero',
        'room_type_id' => 'required|is_natural_no_zero',
        'room_number'  => 'required|max_length[10]',
        'floor'        => 'permit_empty|is_natural',
        'status'       => 'required|in_list[available,occupied,maintenance]'
    ];
    protected $validationMessages   = [
        'hotel_id' => [
            'required'           => 'Hotel ID is required',
            'is_natural_no_zero' => 'Invalid hotel ID'
        ],
        'room_type_id' => [
            'required'           => 'Room type is required',
            'is_natural_no_zero' => 'Invalid room type'
        ],
        'room_number' => [
            'required'   => 'Room number is required',
            'max_length' => 'Room number cannot exceed 10 characters'
        ],
        'floor' => [
            'is_natural' => 'Floor must be a valid number'
        ],
        'status' => [
            'required' => 'Room status is required',
            'in_list'  => 'Invalid room status'
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
     * Search rooms with filters
     */
    public function searchRooms($searchTerm = null, $hotelId = null, $statusFilter = null, $typeFilter = null)
    {
        $builder = $this->select('rooms.*, room_types.type_name, room_types.capacity, room_types.base_price')
                       ->join('room_types', 'room_types.room_type_id = rooms.room_type_id');

        if ($hotelId) {
            $builder->where('rooms.hotel_id', $hotelId);
        }

        if ($searchTerm) {
            $builder->groupStart()
                   ->like('rooms.room_number', $searchTerm)
                   ->orLike('room_types.type_name', $searchTerm)
                   ->groupEnd();
        }

        if ($statusFilter) {
            $builder->where('rooms.status', $statusFilter);
        }

        if ($typeFilter) {
            $builder->where('rooms.room_type_id', $typeFilter);
        }

        return $builder->orderBy('rooms.room_number', 'ASC')->findAll();
    }

    /**
     * Get room status statistics
     */
    public function getRoomStatusStats($hotelId)
    {
        $db = \Config\Database::connect();
        
        $stats = $db->table($this->table)
                   ->select('status, COUNT(*) as count')
                   ->where('hotel_id', $hotelId)
                   ->groupBy('status')
                   ->get()
                   ->getResultArray();

        $result = [
            'available' => 0,
            'occupied' => 0,
            'maintenance' => 0,
            'total' => 0
        ];

        foreach ($stats as $stat) {
            $result[$stat['status']] = (int)$stat['count'];
            $result['total'] += (int)$stat['count'];
        }

        return $result;
    }

    /**
     * Get room with full details including room type
     */
    public function getRoomWithDetails($roomId)
    {
        return $this->select('rooms.*, room_types.type_name, room_types.description, room_types.capacity, room_types.base_price')
                   ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                   ->where('rooms.room_id', $roomId)
                   ->first();
    }

    /**
     * Check if room number exists in hotel
     */
    public function roomNumberExists($hotelId, $roomNumber, $excludeRoomId = null)
    {
        $builder = $this->where('hotel_id', $hotelId)
                       ->where('room_number', $roomNumber);
        
        if ($excludeRoomId) {
            $builder->where('room_id !=', $excludeRoomId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Get current reservation for a room
     */
    public function getRoomCurrentReservation($roomId)
    {
        $db = \Config\Database::connect();
        
        // Check if bookings table exists
        if (!$db->tableExists('bookings')) {
            return null;
        }
        
        return $db->table('bookings b')
                 ->select('b.*, c.full_name as customer_name, c.email as customer_email, c.phone as customer_phone')
                 ->join('customers c', 'c.customer_id = b.customer_id', 'left')
                 ->where('b.room_id', $roomId)
                 ->where('b.status !=', 'cancelled')
                 ->where('b.check_in_date <=', date('Y-m-d'))
                 ->where('b.check_out_date >=', date('Y-m-d'))
                 ->orderBy('b.check_in_date', 'DESC')
                 ->get()
                 ->getRowArray();
    }

    /**
     * Get reservation history for a room
     */
    public function getRoomReservationHistory($roomId, $limit = 10)
    {
        $db = \Config\Database::connect();
        
        // Check if bookings table exists
        if (!$db->tableExists('bookings')) {
            return [];
        }
        
        return $db->table('bookings b')
                 ->select('b.booking_id, b.check_in_date, b.check_out_date, b.total_amount, b.status, 
                          c.full_name as customer_name, c.email as customer_email')
                 ->join('customers c', 'c.customer_id = b.customer_id', 'left')
                 ->where('b.room_id', $roomId)
                 ->orderBy('b.check_in_date', 'DESC')
                 ->limit($limit)
                 ->get()
                 ->getResultArray();
    }

    /**
     * Bulk update room status
     */
    public function bulkUpdateStatus($roomIds, $status)
    {
        if (empty($roomIds) || !in_array($status, ['available', 'occupied', 'maintenance'])) {
            return false;
        }

        return $this->whereIn('room_id', $roomIds)
                   ->set(['status' => $status, 'updated_at' => date('Y-m-d H:i:s')])
                   ->update();
    }

    /**
     * Get available rooms by hotel and date range
     */
    public function getAvailableRooms($hotelId, $checkIn = null, $checkOut = null, $roomTypeId = null)
    {
        $builder = $this->select('rooms.*, room_types.type_name, room_types.capacity, room_types.base_price')
                       ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                       ->where('rooms.hotel_id', $hotelId)
                       ->where('rooms.status', 'available');

        if ($roomTypeId) {
            $builder->where('rooms.room_type_id', $roomTypeId);
        }

        // If dates are provided, exclude rooms that are booked
        if ($checkIn && $checkOut) {
            $db = \Config\Database::connect();
            
            // Check if bookings table exists
            if ($db->tableExists('bookings')) {
                $bookedRoomIds = $db->table('bookings')
                                   ->select('DISTINCT room_id')
                                   ->where('status !=', 'cancelled')
                                   ->groupStart()
                                       ->where('check_in_date <=', $checkOut)
                                       ->where('check_out_date >=', $checkIn)
                                   ->groupEnd()
                                   ->get()
                                   ->getResultArray();

                if (!empty($bookedRoomIds)) {
                    $bookedIds = array_column($bookedRoomIds, 'room_id');
                    $builder->whereNotIn('rooms.room_id', $bookedIds);
                }
            }
        }

        return $builder->orderBy('rooms.room_number', 'ASC')->findAll();
    }

    /**
     * Get rooms by hotel
     */
    public function getRoomsByHotel($hotelId)
    {
        return $this->select('rooms.*, room_types.type_name, room_types.capacity, room_types.base_price')
                   ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                   ->where('rooms.hotel_id', $hotelId)
                   ->orderBy('rooms.room_number', 'ASC')
                   ->findAll();
    }

    /**
     * Get rooms by type
     */
    public function getRoomsByType($roomTypeId)
    {
        return $this->where('room_type_id', $roomTypeId)
                   ->orderBy('room_number', 'ASC')
                   ->findAll();
    }

    /**
     * Get room occupancy rate
     */
    public function getRoomOccupancyRate($hotelId, $startDate = null, $endDate = null)
    {
        $totalRooms = $this->where('hotel_id', $hotelId)->countAllResults();
        
        if ($totalRooms == 0) {
            return 0;
        }
        
        $occupiedRooms = $this->where('hotel_id', $hotelId)
                             ->where('status', 'occupied')
                             ->countAllResults();
        
        return round(($occupiedRooms / $totalRooms) * 100, 2);
    }

    /**
     * Get rooms maintenance schedule
     */
    public function getMaintenanceRooms($hotelId)
    {
        return $this->select('rooms.*, room_types.type_name')
                   ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                   ->where('rooms.hotel_id', $hotelId)
                   ->where('rooms.status', 'maintenance')
                   ->orderBy('rooms.updated_at', 'DESC')
                   ->findAll();
    }

    /**
     * Update room status
     */
    public function updateRoomStatus($roomId, $status)
    {
        if (!in_array($status, ['available', 'occupied', 'maintenance'])) {
            return false;
        }

        return $this->update($roomId, ['status' => $status]);
    }

    /**
     * Check if room can be deleted
     */
    public function canDelete($roomId)
    {
        $db = \Config\Database::connect();
        
        // Check if there are any bookings for this room
        if ($db->tableExists('bookings')) {
            $bookingCount = $db->table('bookings')
                              ->where('room_id', $roomId)
                              ->where('status !=', 'cancelled')
                              ->countAllResults();
            
            if ($bookingCount > 0) {
                return [
                    'can_delete' => false,
                    'reason' => 'Cannot delete room because it has active or past bookings.'
                ];
            }
        }
        
        return ['can_delete' => true];
    }

    /**
     * Get unique floors for a hotel
     */
    public function getUniqueFloors($hotelId)
    {
        return $this->distinct()
                   ->select('floor')
                   ->where('hotel_id', $hotelId)
                   ->where('floor IS NOT NULL')
                   ->orderBy('floor', 'ASC')
                   ->findAll();
    }
}
