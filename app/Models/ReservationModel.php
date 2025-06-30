<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table            = 'reservations';
    protected $primaryKey       = 'reservation_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'history_id',
        'staff_id',
        'check_in_date',
        'check_out_date',
        'total_price',
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
        'history_id'     => 'permit_empty|is_natural_no_zero',
        'staff_id'       => 'permit_empty|is_natural_no_zero',
        'check_in_date'  => 'required|valid_date',
        'check_out_date' => 'required|valid_date',
        'total_price'    => 'required|decimal|greater_than[0]',
        'status'         => 'permit_empty|in_list[pending,confirmed,cancelled,completed]'
    ];
    protected $validationMessages   = [
        'check_in_date' => [
            'required'   => 'Check-in date is required',
            'valid_date' => 'Please enter a valid check-in date'
        ],
        'check_out_date' => [
            'required'   => 'Check-out date is required',
            'valid_date' => 'Please enter a valid check-out date'
        ],
        'total_price' => [
            'required'     => 'Total price is required',
            'decimal'      => 'Total price must be a valid decimal number',
            'greater_than' => 'Total price must be greater than 0'
        ],
        'status' => [
            'in_list' => 'Status must be one of: pending, confirmed, cancelled, completed'
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
     * Get reservations by hotel with details
     */
    public function getReservationsByHotel($hotelId, $status = null, $dateFrom = null, $dateTo = null, $limit = null, $offset = null)
    {
        $builder = $this->select('reservations.*,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone,
                                hotels.name as hotel_name,
                                rooms.room_number,
                                room_types.type_name,
                                staff.full_name as assigned_staff_name')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->join('staff', 'staff.staff_id = reservations.staff_id', 'left')
                        ->where('booking_history.hotel_id', $hotelId)
                        ->orderBy('reservations.created_at', 'DESC');

        if ($status) {
            $builder->where('reservations.status', $status);
        }

        if ($dateFrom) {
            $builder->where('reservations.check_in_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('reservations.check_out_date <=', $dateTo);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get reservation with full details
     */
    public function getReservationWithDetails($reservationId)
    {
        return $this->select('reservations.*,
                            booking_history.booking_ticket_no,
                            booking_history.person_full_name as booked_by_name,
                            booking_history.person_phone as booked_by_phone,
                            booking_history.guest_email as booked_by_email,
                            booking_history.guests_count,
                            hotels.hotel_id,
                            hotels.name as hotel_name,
                            hotels.address as hotel_address,
                            hotels.phone as hotel_phone,
                            rooms.room_id,
                            rooms.room_number,
                            rooms.floor_number,
                            room_types.type_name,
                            room_types.capacity,
                            room_types.base_price,
                            staff.full_name as assigned_staff_name,
                            staff.role as assigned_staff_role')
                     ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                     ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                     ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                     ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                     ->join('staff', 'staff.staff_id = reservations.staff_id', 'left')
                     ->where('reservations.reservation_id', $reservationId)
                     ->first();
    }

    /**
     * Get reservation statistics
     */
    public function getReservationStatistics($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('reservations.status, COUNT(*) as count, SUM(reservations.total_price) as total_amount')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->groupBy('reservations.status');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($dateFrom) {
            $builder->where('reservations.check_in_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('reservations.check_out_date <=', $dateTo);
        }

        $results = $builder->findAll();

        $stats = [
            'pending' => ['count' => 0, 'amount' => 0],
            'confirmed' => ['count' => 0, 'amount' => 0],
            'cancelled' => ['count' => 0, 'amount' => 0],
            'completed' => ['count' => 0, 'amount' => 0],
            'total' => ['count' => 0, 'amount' => 0]
        ];

        foreach ($results as $result) {
            $status = $result['status'] ?? 'pending';
            $stats[$status] = [
                'count' => $result['count'],
                'amount' => $result['total_amount']
            ];
            $stats['total']['count'] += $result['count'];
            $stats['total']['amount'] += $result['total_amount'];
        }

        return $stats;
    }

    /**
     * Search reservations
     */
    public function searchReservations($searchTerm, $hotelId = null, $status = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('reservations.*,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone,
                                hotels.name as hotel_name,
                                rooms.room_number,
                                room_types.type_name,
                                staff.full_name as assigned_staff_name')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->join('staff', 'staff.staff_id = reservations.staff_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('booking_history.booking_ticket_no', $searchTerm)
                   ->orLike('booking_history.person_full_name', $searchTerm)
                   ->orLike('booking_history.person_phone', $searchTerm)
                   ->orLike('rooms.room_number', $searchTerm)
                   ->orLike('reservations.reservation_id', $searchTerm)
                   ->groupEnd();
        }

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($status) {
            $builder->where('reservations.status', $status);
        }

        return $builder->orderBy('reservations.created_at', 'DESC')
                      ->limit($limit, $offset)
                      ->findAll();
    }

    /**
     * Cancel reservation
     */
    public function cancelReservation($reservationId)
    {
        return $this->update($reservationId, [
            'status' => 'cancelled'
        ]);
    }

    /**
     * Complete reservation
     */
    public function completeReservation($reservationId)
    {
        return $this->update($reservationId, [
            'status' => 'completed'
        ]);
    }

    /**
     * Update reservation status
     */
    public function updateReservationStatus($reservationId, $status)
    {
        return $this->update($reservationId, ['status' => $status]);
    }

    /**
     * Get reservations by staff
     */
    public function getReservationsByStaff($staffId, $status = null, $limit = null, $offset = null)
    {
        $builder = $this->select('reservations.*,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone,
                                hotels.name as hotel_name,
                                rooms.room_number,
                                room_types.type_name')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('reservations.staff_id', $staffId)
                        ->orderBy('reservations.created_at', 'DESC');

        if ($status) {
            $builder->where('reservations.status', $status);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get upcoming reservations
     */
    public function getUpcomingReservations($hotelId = null, $days = 7, $limit = 10)
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$days} days"));

        $builder = $this->select('reservations.*,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone,
                                hotels.name as hotel_name,
                                rooms.room_number,
                                room_types.type_name,
                                staff.full_name as assigned_staff_name')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->join('staff', 'staff.staff_id = reservations.staff_id', 'left')
                        ->where('reservations.check_in_date >=', $startDate)
                        ->where('reservations.check_in_date <=', $endDate)
                        ->where('reservations.status !=', 'cancelled')
                        ->orderBy('reservations.check_in_date', 'ASC');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get reservations for today
     */
    public function getTodayReservations($hotelId = null)
    {
        $today = date('Y-m-d');

        $builder = $this->select('reservations.*,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone,
                                hotels.name as hotel_name,
                                rooms.room_number,
                                room_types.type_name,
                                staff.full_name as assigned_staff_name')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->join('staff', 'staff.staff_id = reservations.staff_id', 'left')
                        ->where('reservations.check_in_date <=', $today)
                        ->where('reservations.check_out_date >=', $today)
                        ->where('reservations.status !=', 'cancelled')
                        ->orderBy('reservations.check_in_date', 'ASC');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get reservation revenue
     */
    public function getReservationRevenue($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('SUM(reservations.total_price) as total_revenue, COUNT(*) as total_reservations')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->where('reservations.status', 'completed');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($dateFrom) {
            $builder->where('reservations.check_in_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('reservations.check_out_date <=', $dateTo);
        }

        return $builder->first();
    }

    /**
     * Check if reservation exists for booking
     */
    public function reservationExistsForBooking($historyId)
    {
        return $this->where('history_id', $historyId)->first() !== null;
    }

    /**
     * Get reservation by booking history ID
     */
    public function getReservationByBooking($historyId)
    {
        return $this->where('history_id', $historyId)->first();
    }
}
