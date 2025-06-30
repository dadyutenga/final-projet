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
        'hotel_id'    => 'required|is_natural_no_zero',
        'type_name'   => 'required|max_length[100]',
        'description' => 'permit_empty',
        'base_price'  => 'required|decimal|greater_than[0]',
        'capacity'    => 'required|is_natural_no_zero'
    ];
    protected $validationMessages   = [
        'hotel_id' => [
            'required'           => 'Hotel ID is required',
            'is_natural_no_zero' => 'Invalid hotel ID'
        ],
        'type_name' => [
            'required'   => 'Room type name is required',
            'max_length' => 'Room type name cannot exceed 100 characters'
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
     * Get room types with room counts for a hotel
     */
    public function getRoomTypesWithCounts($hotelId)
    {
        return $this->select('room_types.*, 
                             COUNT(rooms.room_id) as total_rooms,
                             SUM(CASE WHEN rooms.status = "available" THEN 1 ELSE 0 END) as available_rooms')
                    ->join('rooms', 'rooms.room_type_id = room_types.room_type_id', 'left')
                    ->where('room_types.hotel_id', $hotelId)
                    ->groupBy('room_types.room_type_id')
                    ->findAll();
    }

    /**
     * Get room types by hotel - FIXED VERSION
     */
    public function getRoomTypesByHotel($hotelId)
    {
        return $this->where('hotel_id', $hotelId)->findAll();
    }

    /**
     * Get room type statistics
     */
    public function getRoomTypeStatistics($roomTypeId)
    {
        $db = \Config\Database::connect();
        
        // Get basic room type info
        $roomType = $this->find($roomTypeId);
        
        if (!$roomType) {
            return null;
        }
        
        // Get room counts by status
        $roomStats = $db->table('rooms')
                       ->select('status, COUNT(*) as count')
                       ->where('room_type_id', $roomTypeId)
                       ->groupBy('status')
                       ->get()
                       ->getResultArray();
        
        // Get booking statistics (if bookings table exists)
        $bookingStats = null;
        if ($db->tableExists('bookings')) {
            $bookingStats = $db->table('bookings b')
                              ->select('COUNT(DISTINCT b.booking_id) as total_bookings,
                                       AVG(DATEDIFF(b.check_out_date, b.check_in_date)) as avg_stay_duration,
                                       SUM(b.total_amount) as total_revenue')
                              ->join('rooms r', 'r.room_id = b.room_id')
                              ->where('r.room_type_id', $roomTypeId)
                              ->where('b.status !=', 'cancelled')
                              ->get()
                              ->getRowArray();
        }
        
        return [
            'room_type' => $roomType,
            'room_stats' => $roomStats,
            'booking_stats' => $bookingStats
        ];
    }

    /**
     * Get room type with rooms
     */
    public function getRoomTypeWithRooms($roomTypeId)
    {
        $roomType = $this->find($roomTypeId);
        
        if (!$roomType) {
            return null;
        }
        
        $db = \Config\Database::connect();
        $rooms = $db->table('rooms')
                   ->where('room_type_id', $roomTypeId)
                   ->get()
                   ->getResultArray();
        
        $roomType['rooms'] = $rooms;
        
        return $roomType;
    }

    /**
     * Check if room type can be deleted
     */
    public function canDelete($roomTypeId)
    {
        $db = \Config\Database::connect();
        
        // Check if there are rooms of this type
        $roomCount = $db->table('rooms')->where('room_type_id', $roomTypeId)->countAllResults();
        
        if ($roomCount > 0) {
            return [
                'can_delete' => false,
                'reason' => 'Cannot delete room type because there are rooms assigned to it.'
            ];
        }
        
        return ['can_delete' => true];
    }
}
