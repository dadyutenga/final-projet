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
        'user_id',
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
        'user_id'        => 'permit_empty|is_natural_no_zero',
        'check_in_date'  => 'required|valid_date',
        'check_out_date' => 'required|valid_date',
        'total_price'    => 'required|decimal|greater_than[0]',
        'status'         => 'permit_empty|in_list[pending,confirmed,cancelled,completed]'
    ];
    
    protected $validationMessages   = [
        'history_id' => [
            'is_natural_no_zero' => 'History ID must be a valid number'
        ],
        'check_in_date' => [
            'required'    => 'Check-in date is required',
            'valid_date'  => 'Please enter a valid check-in date'
        ],
        'check_out_date' => [
            'required'    => 'Check-out date is required',
            'valid_date'  => 'Please enter a valid check-out date'  
        ],
        'total_price' => [
            'required'     => 'Total price is required',
            'decimal'      => 'Total price must be a valid decimal number',
            'greater_than' => 'Total price must be greater than 0'
        ],
        'status' => [
            'in_list'      => 'Status must be one of: pending, confirmed, cancelled, completed'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['validateDates', 'createBookingHistory'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['validateDates'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Create booking history entry before reservation insert
     */
    protected function createBookingHistory(array $data)
    {
        // Get guest information
        $guestName = session()->get('guest_name') ?? 'Guest';
        $guestPhone = session()->get('guest_phone') ?? '';
        
        // Get hotel_id and room_id from session or data
        $hotelId = session()->get('booking_hotel_id') ?? null;
        $roomId = session()->get('booking_room_id') ?? null;
        
        // If user_id is provided, get user details
        if (isset($data['data']['user_id']) && $data['data']['user_id']) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($data['data']['user_id']);
            if ($user) {
                $guestName = $user['full_name'];
                $guestPhone = $user['phone'] ?? '';
            }
        }

        if (!$hotelId || !$roomId) {
            throw new \RuntimeException('Hotel ID and Room ID are required for booking history');
        }

        // Create booking history entry
        $historyModel = new \App\Models\BookingHistoryModel();
        $bookingTicketNo = $historyModel->generateTicketNumber($hotelId);
        
        $historyId = $historyModel->insert([
            'booking_ticket_no' => $bookingTicketNo,
            'room_id' => $roomId,
            'hotel_id' => $hotelId,
            'person_full_name' => $guestName,
            'person_phone' => $guestPhone,
            'action_date' => date('Y-m-d H:i:s')
        ]);

        // Set the history_id for the reservation
        $data['data']['history_id'] = $historyId;

        return $data;
    }

    /**
     * Validate check-in and check-out dates
     */
    protected function validateDates(array $data)
    {
        if (isset($data['data']['check_in_date']) && isset($data['data']['check_out_date'])) {
            $checkIn = strtotime($data['data']['check_in_date']);
            $checkOut = strtotime($data['data']['check_out_date']);

            if ($checkOut <= $checkIn) {
                throw new \RuntimeException('Check-out date must be after check-in date');
            }

            if ($checkIn < strtotime(date('Y-m-d'))) {
                throw new \RuntimeException('Check-in date cannot be in the past');
            }
        }

        return $data;
    }

    /**
     * Get reservation with full details including booking history
     */
    public function getReservationWithDetails($reservationId)
    {
        return $this->select('reservations.*,
                            users.full_name as guest_name,
                            users.email as guest_email,
                            users.phone as guest_phone,
                            hotels.name as hotel_name,
                            hotels.address as hotel_address,
                            hotels.city as hotel_city,
                            hotels.country as hotel_country,
                            hotels.phone as hotel_phone,
                            rooms.room_number,
                            rooms.floor,
                            room_types.type_name,
                            room_types.description as room_description,
                            room_types.capacity,
                            booking_history.booking_ticket_no,
                            booking_history.person_full_name as booked_by_name,
                            booking_history.person_phone as booked_by_phone,
                            booking_history.action_date as booking_date,
                            booking_history.hotel_id,
                            booking_history.room_id')
                    ->join('users', 'users.user_id = reservations.user_id', 'left')
                    ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                    ->where('reservations.reservation_id', $reservationId)
                    ->first();
    }

    /**
     * Get reservation by booking ticket number
     */
    public function getReservationByTicket($ticketNo)
    {
        return $this->select('reservations.*,
                            users.full_name as guest_name,
                            users.email as guest_email,
                            users.phone as guest_phone,
                            hotels.name as hotel_name,
                            hotels.address as hotel_address,
                            hotels.city as hotel_city,
                            rooms.room_number,
                            room_types.type_name,
                            booking_history.booking_ticket_no,
                            booking_history.person_full_name as booked_by_name,
                            booking_history.person_phone as booked_by_phone,
                            booking_history.hotel_id,
                            booking_history.room_id')
                    ->join('users', 'users.user_id = reservations.user_id', 'left')
                    ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                    ->where('booking_history.booking_ticket_no', $ticketNo)
                    ->first();
    }

    /**
     * Get reservations by user
     */
    public function getReservationsByUser($userId, $status = null, $limit = null, $offset = null)
    {
        $builder = $this->select('reservations.*,
                                hotels.name as hotel_name,
                                hotels.city as hotel_city,
                                hotels.country as hotel_country,
                                rooms.room_number,
                                room_types.type_name,
                                booking_history.booking_ticket_no,
                                booking_history.hotel_id,
                                booking_history.room_id')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('reservations.user_id', $userId)
                        ->orderBy('reservations.check_in_date', 'DESC');

        if ($status) {
            $builder->where('reservations.status', $status);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get reservations by hotel
     */
    public function getReservationsByHotel($hotelId, $status = null, $dateFrom = null, $dateTo = null, $limit = null, $offset = null)
    {
        $builder = $this->select('reservations.*,
                                users.full_name as guest_name,
                                users.email as guest_email,
                                users.phone as guest_phone,
                                rooms.room_number,
                                room_types.type_name,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone')
                        ->join('users', 'users.user_id = reservations.user_id', 'left')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('booking_history.hotel_id', $hotelId)
                        ->orderBy('reservations.check_in_date', 'DESC');

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
     * Get today's check-ins
     */
    public function getTodayCheckIns($hotelId = null)
    {
        $today = date('Y-m-d');

        $builder = $this->select('reservations.*,
                                users.full_name as guest_name,
                                users.phone as guest_phone,
                                hotels.name as hotel_name,
                                rooms.room_number,
                                room_types.type_name,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone')
                        ->join('users', 'users.user_id = reservations.user_id', 'left')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('reservations.check_in_date', $today)
                        ->where('reservations.status', 'confirmed')
                        ->orderBy('reservations.created_at', 'ASC');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get today's check-outs
     */
    public function getTodayCheckOuts($hotelId = null)
    {
        $today = date('Y-m-d');

        $builder = $this->select('reservations.*,
                                users.full_name as guest_name,
                                users.phone as guest_phone,
                                hotels.name as hotel_name,
                                rooms.room_number,
                                room_types.type_name,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone')
                        ->join('users', 'users.user_id = reservations.user_id', 'left')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('reservations.check_out_date', $today)
                        ->where('reservations.status', 'confirmed')
                        ->orderBy('reservations.created_at', 'ASC');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get current guests (checked in)
     */
    public function getCurrentGuests($hotelId = null)
    {
        $today = date('Y-m-d');

        $builder = $this->select('reservations.*,
                                users.full_name as guest_name,
                                users.phone as guest_phone,
                                users.email as guest_email,
                                hotels.name as hotel_name,
                                rooms.room_number,
                                room_types.type_name,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone')
                        ->join('users', 'users.user_id = reservations.user_id', 'left')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('reservations.check_in_date <=', $today)
                        ->where('reservations.check_out_date >=', $today)
                        ->where('reservations.status', 'confirmed')
                        ->orderBy('reservations.check_out_date', 'ASC');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get reservation statistics
     */
    public function getReservationStatistics($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('reservations.status, COUNT(*) as count, SUM(reservations.total_price) as revenue')
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
            'pending' => ['count' => 0, 'revenue' => 0],
            'confirmed' => ['count' => 0, 'revenue' => 0],
            'cancelled' => ['count' => 0, 'revenue' => 0],
            'completed' => ['count' => 0, 'revenue' => 0],
            'total' => ['count' => 0, 'revenue' => 0]
        ];

        foreach ($results as $result) {
            $stats[$result['status']] = [
                'count' => $result['count'],
                'revenue' => $result['revenue'] ?? 0
            ];
            $stats['total']['count'] += $result['count'];
            $stats['total']['revenue'] += $result['revenue'] ?? 0;
        }

        return $stats;
    }

    /**
     * Check room availability for dates
     */
    public function checkRoomAvailability($roomId, $checkIn, $checkOut, $excludeReservationId = null)
    {
        $builder = $this->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->where('booking_history.room_id', $roomId)
                        ->where('reservations.status !=', 'cancelled')
                        ->groupStart()
                            ->where('reservations.check_in_date <=', $checkOut)
                            ->where('reservations.check_out_date >=', $checkIn)
                        ->groupEnd();

        if ($excludeReservationId) {
            $builder->where('reservations.reservation_id !=', $excludeReservationId);
        }

        return $builder->countAllResults() == 0;
    }

    /**
     * Get upcoming reservations
     */
    public function getUpcomingReservations($hotelId = null, $days = 7)
    {
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));

        $builder = $this->select('reservations.*,
                                users.full_name as guest_name,
                                users.phone as guest_phone,
                                hotels.name as hotel_name,
                                rooms.room_number,
                                room_types.type_name,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone')
                        ->join('users', 'users.user_id = reservations.user_id', 'left')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->where('reservations.check_in_date >=', $today)
                        ->where('reservations.check_in_date <=', $futureDate)
                        ->where('reservations.status', 'confirmed')
                        ->orderBy('reservations.check_in_date', 'ASC');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Update reservation status
     */
    public function updateReservationStatus($reservationId, $status)
    {
        return $this->update($reservationId, ['status' => $status]);
    }

    /**
     * Cancel reservation
     */
    public function cancelReservation($reservationId, $reason = '')
    {
        $reservation = $this->getReservationWithDetails($reservationId);
        if (!$reservation) {
            return false;
        }

        // Update reservation status
        $updated = $this->update($reservationId, ['status' => 'cancelled']);

        if ($updated) {
            // Update room status if it was occupied
            $roomModel = new \App\Models\RoomModel();
            $room = $roomModel->find($reservation['room_id']);
            if ($room && $room['status'] == 'occupied') {
                $roomModel->updateRoomStatus($reservation['room_id'], 'available');
            }
        }

        return $updated;
    }

    /**
     * Complete reservation (check-out)
     */
    public function completeReservation($reservationId)
    {
        $reservation = $this->getReservationWithDetails($reservationId);
        if (!$reservation) {
            return false;
        }

        // Update reservation status
        $updated = $this->update($reservationId, ['status' => 'completed']);

        if ($updated) {
            // Update room status to available
            $roomModel = new \App\Models\RoomModel();
            $roomModel->updateRoomStatus($reservation['room_id'], 'available');
        }

        return $updated;
    }

    /**
     * Search reservations
     */
    public function searchReservations($searchTerm, $hotelId = null, $status = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('reservations.*,
                                users.full_name as guest_name,
                                users.email as guest_email,
                                hotels.name as hotel_name,
                                rooms.room_number,
                                booking_history.booking_ticket_no,
                                booking_history.person_full_name as booked_by_name,
                                booking_history.person_phone as booked_by_phone')
                        ->join('users', 'users.user_id = reservations.user_id', 'left')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('users.full_name', $searchTerm)
                   ->orLike('users.email', $searchTerm)
                   ->orLike('hotels.name', $searchTerm)
                   ->orLike('rooms.room_number', $searchTerm)
                   ->orLike('booking_history.booking_ticket_no', $searchTerm)
                   ->orLike('booking_history.person_full_name', $searchTerm)
                   ->orLike('booking_history.person_phone', $searchTerm)
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
     * Get revenue report
     */
    public function getRevenueReport($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('DATE(reservations.check_in_date) as date,
                                COUNT(*) as bookings,
                                SUM(reservations.total_price) as revenue')
                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                        ->where('reservations.status', 'completed')
                        ->groupBy('DATE(reservations.check_in_date)')
                        ->orderBy('date', 'ASC');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($dateFrom) {
            $builder->where('reservations.check_in_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('reservations.check_out_date <=', $dateTo);
        }

        return $builder->findAll();
    }

    /**
     * Calculate total nights
     */
    public function calculateTotalNights($checkIn, $checkOut)
    {
        $checkInDate = new \DateTime($checkIn);
        $checkOutDate = new \DateTime($checkOut);
        $interval = $checkInDate->diff($checkOutDate);

        return $interval->days;
    }

    /**
     * Create reservation with guest info
     */
    public function createReservationWithGuest($reservationData, $guestName, $guestPhone, $hotelId, $roomId)
    {
        // Set guest info and booking details in session for the callback
        session()->set([
            'guest_name' => $guestName,
            'guest_phone' => $guestPhone,
            'booking_hotel_id' => $hotelId,
            'booking_room_id' => $roomId
        ]);

        // Create reservation (this will trigger the history creation)
        $reservationId = $this->insert($reservationData);

        // Clear session data
        session()->remove(['guest_name', 'guest_phone', 'booking_hotel_id', 'booking_room_id']);

        return $reservationId;
    }

    /**
     * Get reservation with booking history
     */
    public function getReservationWithHistory($reservationId)
    {
        $reservation = $this->getReservationWithDetails($reservationId);
        
        if ($reservation && $reservation['history_id']) {
            // Get additional booking history if needed
            $historyModel = new \App\Models\BookingHistoryModel();
            $reservation['full_booking_history'] = $historyModel->getHistoryWithDetails($reservation['history_id']);
        }

        return $reservation;
    }

    /**
     * Get booking ticket from reservation
     */
    public function getBookingTicket($reservationId)
    {
        $reservation = $this->select('reservations.history_id, booking_history.booking_ticket_no')
                          ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                          ->where('reservations.reservation_id', $reservationId)
                          ->first();

        return $reservation ? $reservation['booking_ticket_no'] : null;
    }

    /**
     * Get history ID from reservation
     */
    public function getHistoryId($reservationId)
    {
        $reservation = $this->select('history_id')
                          ->where('reservation_id', $reservationId)
                          ->first();

        return $reservation ? $reservation['history_id'] : null;
    }

    /**
     * Get hotel and room ID from reservation
     */
    public function getHotelAndRoomIds($reservationId)
    {
        $reservation = $this->select('booking_history.hotel_id, booking_history.room_id')
                          ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                          ->where('reservations.reservation_id', $reservationId)
                          ->first();

        return $reservation ? [
            'hotel_id' => $reservation['hotel_id'],
            'room_id' => $reservation['room_id']
        ] : null;
    }
}
