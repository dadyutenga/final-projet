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
        'hotel_id'     => 'permit_empty|is_natural_no_zero',
        'room_type_id' => 'permit_empty|is_natural_no_zero',
        'room_number'  => 'required|max_length[10]',
        'floor'        => 'permit_empty|is_natural',
        'status'       => 'permit_empty|in_list[available,occupied,maintenance]'
    ];
    protected $validationMessages   = [
        'room_number' => [
            'required'    => 'Room number is required',
            'max_length'  => 'Room number cannot exceed 10 characters'
        ],
        'floor' => [
            'is_natural'  => 'Floor must be a valid number'
        ],
        'status' => [
            'in_list'     => 'Status must be one of: available, occupied, maintenance'
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
     * Get room with hotel and room type details
     */
    public function getRoomWithDetails($roomId)
    {
        return $this->select('rooms.*,
                            hotels.name as hotel_name,
                            hotels.city,
                            hotels.country,
                            room_types.type_name,
                            room_types.description,
                            room_types.base_price,
                            room_types.capacity')
                    ->join('hotels', 'hotels.hotel_id = rooms.hotel_id', 'left')
                    ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                    ->where('rooms.room_id', $roomId)
                    ->first();
    }

    /**
     * Get rooms by hotel
     */
    public function getRoomsByHotel($hotelId, $status = null)
    {
        $builder = $this->select('rooms.*,
                                room_types.type_name,
                                room_types.base_price,
                                room_types.capacity')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('rooms.hotel_id', $hotelId)
                        ->orderBy('rooms.floor', 'ASC')
                        ->orderBy('rooms.room_number', 'ASC');

        if ($status) {
            $builder->where('rooms.status', $status);
        }

        return $builder->findAll();
    }

    /**
     * Get rooms by room type
     */
    public function getRoomsByType($roomTypeId, $status = null)
    {
        $builder = $this->select('rooms.*,
                                hotels.name as hotel_name,
                                room_types.type_name,
                                room_types.base_price,
                                room_types.capacity')
                        ->join('hotels', 'hotels.hotel_id = rooms.hotel_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('rooms.room_type_id', $roomTypeId)
                        ->orderBy('rooms.floor', 'ASC')
                        ->orderBy('rooms.room_number', 'ASC');

        if ($status) {
            $builder->where('rooms.status', $status);
        }

        return $builder->findAll();
    }

    /**
     * Get available rooms for date range
     */
    public function getAvailableRooms($hotelId, $checkIn, $checkOut, $roomTypeId = null)
    {
        $builder = $this->select('rooms.*,
                                room_types.type_name,
                                room_types.description,
                                room_types.base_price,
                                room_types.capacity')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('rooms.hotel_id', $hotelId)
                        ->where('rooms.status', 'available')
                        ->whereNotIn('rooms.room_id', function($builder) use ($checkIn, $checkOut) {
                            return $builder->select('room_id')
                                          ->from('reservations')
                                          ->where('status !=', 'cancelled')
                                          ->where('check_in_date <=', $checkOut)
                                          ->where('check_out_date >=', $checkIn);
                        })
                        ->orderBy('room_types.base_price', 'ASC')
                        ->orderBy('rooms.floor', 'ASC')
                        ->orderBy('rooms.room_number', 'ASC');

        if ($roomTypeId) {
            $builder->where('rooms.room_type_id', $roomTypeId);
        }

        return $builder->findAll();
    }

    /**
     * Get rooms by floor
     */
    public function getRoomsByFloor($hotelId, $floor)
    {
        return $this->select('rooms.*,
                            room_types.type_name,
                            room_types.base_price,
                            room_types.capacity')
                    ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                    ->where('rooms.hotel_id', $hotelId)
                    ->where('rooms.floor', $floor)
                    ->orderBy('rooms.room_number', 'ASC')
                    ->findAll();
    }

    /**
     * Get room status statistics
     */
    public function getRoomStatusStats($hotelId = null)
    {
        $builder = $this->select('status, COUNT(*) as count')
                        ->groupBy('status');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        $results = $builder->findAll();

        $stats = [
            'available' => 0,
            'occupied' => 0,
            'maintenance' => 0,
            'total' => 0
        ];

        foreach ($results as $result) {
            $stats[$result['status']] = $result['count'];
            $stats['total'] += $result['count'];
        }

        return $stats;
    }

    /**
     * Get room occupancy rate
     */
    public function getRoomOccupancyRate($hotelId, $startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = date('Y-m-d', strtotime('-30 days'));
        }
        if (!$endDate) {
            $endDate = date('Y-m-d');
        }

        $totalRooms = $this->where('hotel_id', $hotelId)->countAllResults();

        if ($totalRooms == 0) {
            return 0;
        }

        $occupiedRoomDays = $this->db->table('reservations')
                                   ->join('rooms', 'rooms.room_id = reservations.room_id')
                                   ->where('rooms.hotel_id', $hotelId)
                                   ->where('reservations.status !=', 'cancelled')
                                   ->where('reservations.check_in_date <=', $endDate)
                                   ->where('reservations.check_out_date >=', $startDate)
                                   ->selectSum('DATEDIFF(
                                       LEAST(reservations.check_out_date, "' . $endDate . '"),
                                       GREATEST(reservations.check_in_date, "' . $startDate . '")
                                   )', 'total_days')
                                   ->get()
                                   ->getRow()
                                   ->total_days ?? 0;

        $totalDays = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) + 1;
        $totalRoomDays = $totalRooms * $totalDays;

        return $totalRoomDays > 0 ? ($occupiedRoomDays / $totalRoomDays) * 100 : 0;
    }

    /**
     * Update room status
     */
    public function updateRoomStatus($roomId, $status)
    {
        return $this->update($roomId, ['status' => $status]);
    }

    /**
     * Get room current reservation
     */
    public function getRoomCurrentReservation($roomId)
    {
        $today = date('Y-m-d');

        return $this->db->table('reservations')
                       ->select('reservations.*, users.full_name as guest_name, users.phone as guest_phone')
                       ->join('users', 'users.user_id = reservations.user_id', 'left')
                       ->where('reservations.room_id', $roomId)
                       ->where('reservations.status', 'confirmed')
                       ->where('reservations.check_in_date <=', $today)
                       ->where('reservations.check_out_date >=', $today)
                       ->get()
                       ->getRowArray();
    }

    /**
     * Get room reservation history
     */
    public function getRoomReservationHistory($roomId, $limit = null, $offset = null)
    {
        $builder = $this->db->table('reservations')
                           ->select('reservations.*, users.full_name as guest_name, users.email as guest_email')
                           ->join('users', 'users.user_id = reservations.user_id', 'left')
                           ->where('reservations.room_id', $roomId)
                           ->orderBy('reservations.check_in_date', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Search rooms
     */
    public function searchRooms($searchTerm, $hotelId = null, $status = null, $roomTypeId = null)
    {
        $builder = $this->select('rooms.*,
                                hotels.name as hotel_name,
                                room_types.type_name,
                                room_types.base_price,
                                room_types.capacity')
                        ->join('hotels', 'hotels.hotel_id = rooms.hotel_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('rooms.room_number', $searchTerm)
                   ->orLike('room_types.type_name', $searchTerm)
                   ->groupEnd();
        }

        if ($hotelId) {
            $builder->where('rooms.hotel_id', $hotelId);
        }

        if ($status) {
            $builder->where('rooms.status', $status);
        }

        if ($roomTypeId) {
            $builder->where('rooms.room_type_id', $roomTypeId);
        }

        return $builder->orderBy('rooms.floor', 'ASC')
                      ->orderBy('rooms.room_number', 'ASC')
                      ->findAll();
    }

    /**
     * Get rooms needing maintenance
     */
    public function getRoomsNeedingMaintenance($hotelId = null)
    {
        $builder = $this->select('rooms.*,
                                hotels.name as hotel_name,
                                room_types.type_name')
                        ->join('hotels', 'hotels.hotel_id = rooms.hotel_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('rooms.status', 'maintenance');

        if ($hotelId) {
            $builder->where('rooms.hotel_id', $hotelId);
        }

        return $builder->orderBy('rooms.updated_at', 'ASC')->findAll();
    }

    /**
     * Get rooms by status with details
     */
    public function getRoomsByStatusWithDetails($status, $hotelId = null)
    {
        $builder = $this->select('rooms.*,
                                hotels.name as hotel_name,
                                room_types.type_name,
                                room_types.base_price,
                                room_types.capacity')
                        ->join('hotels', 'hotels.hotel_id = rooms.hotel_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('rooms.status', $status);

        if ($hotelId) {
            $builder->where('rooms.hotel_id', $hotelId);
        }

        return $builder->orderBy('rooms.floor', 'ASC')
                      ->orderBy('rooms.room_number', 'ASC')
                      ->findAll();
    }

    /**
     * Bulk update room status
     */
    public function bulkUpdateStatus($roomIds, $status)
    {
        return $this->whereIn('room_id', $roomIds)
                    ->set(['status' => $status])
                    ->update();
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
}
