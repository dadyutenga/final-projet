<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingHistoryModel extends Model
{
    protected $table            = 'booking_history';
    protected $primaryKey       = 'history_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'booking_ticket_no',
        'room_id',
        'hotel_id',
        'person_full_name',
        'person_phone',
        'check_in_date',
        'check_out_date',
        'total_price',
        'guests_count',
        'guest_email',
        'status',
        'action_date',
        'checked_in_date',  // ADD THIS
        'checked_out_date'  // ADD THIS
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'booking_ticket_no' => 'required|max_length[50]|is_unique[booking_history.booking_ticket_no]',
        'room_id'          => 'required|is_natural_no_zero',
        'hotel_id'         => 'required|is_natural_no_zero',
        'person_full_name' => 'required|max_length[100]',
        'person_phone'     => 'required|max_length[20]',
        'check_in_date'    => 'required|valid_date',
        'check_out_date'   => 'required|valid_date',
        'total_price'      => 'required|decimal|greater_than[0]',
        'guests_count'     => 'required|is_natural_no_zero',
        'guest_email'      => 'permit_empty|valid_email',
        'status'           => 'permit_empty|in_list[confirmed,cancelled,completed,checked_in]', // FIXED: added check_in
        'action_date'      => 'permit_empty|valid_date',
        'checked_in_date'  => 'permit_empty|valid_date'  // ADD THIS
    ];
    
    protected $validationMessages   = [
        'booking_ticket_no' => [
            'required' => 'Booking ticket number is required',
            'max_length' => 'Booking ticket number cannot exceed 50 characters',
            'is_unique' => 'Booking ticket number already exists'
        ],
        'room_id' => [
            'required' => 'Room ID is required',
            'is_natural_no_zero' => 'Room ID must be a valid number'
        ],
        'hotel_id' => [
            'required' => 'Hotel ID is required',
            'is_natural_no_zero' => 'Hotel ID must be a valid number'
        ],
        'person_full_name' => [
            'required' => 'Person full name is required',
            'max_length' => 'Person full name cannot exceed 100 characters'
        ],
        'person_phone' => [
            'required' => 'Phone number is required',
            'max_length' => 'Phone number cannot exceed 20 characters'
        ],
        'check_in_date' => [
            'required' => 'Check-in date is required',
            'valid_date' => 'Please enter a valid check-in date'
        ],
        'check_out_date' => [
            'required' => 'Check-out date is required',
            'valid_date' => 'Please enter a valid check-out date'
        ],
        'total_price' => [
            'required' => 'Total price is required',
            'decimal' => 'Total price must be a valid decimal number',
            'greater_than' => 'Total price must be greater than 0'
        ],
        'guests_count' => [
            'required' => 'Guest count is required',
            'is_natural_no_zero' => 'Guest count must be a valid number'
        ],
        'guest_email' => [
            'valid_email' => 'Please enter a valid email address'
        ],
        'status' => [
            'in_list' => 'Status must be one of: confirmed, cancelled, completed, checked_in' // UPDATED
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setActionDate'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Set action date if not provided
     */
    protected function setActionDate(array $data)
    {
        if (!isset($data['data']['action_date']) || empty($data['data']['action_date'])) {
            $data['data']['action_date'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    
    /**
     * Get booking by ticket number
     */
    
    /**
     * Get booking with full details
     */
    public function getBookingWithDetails($ticketNo)
    {
        return $this->select('booking_history.*,
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
                            room_types.base_price')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                    ->where('booking_history.booking_ticket_no', $ticketNo)
                    ->first();
    }

    /**
     * Get booking history by phone number
     */
    public function getHistoryByPhone($phone, $limit = 10)
    {
        return $this->select('booking_history.*,
                            hotels.name as hotel_name,
                            hotels.city as hotel_city,
                            rooms.room_number,
                            room_types.type_name')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                    ->where('booking_history.person_phone', $phone)
                    ->orderBy('booking_history.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get booking statistics
   
    
     * Check room availability for dates (used by controller)
     */
    
    /**
     * Calculate total nights between two dates
     */
    public function calculateTotalNights($checkIn, $checkOut)
    {
        $checkInDate = new \DateTime($checkIn);
        $checkOutDate = new \DateTime($checkOut);
        $interval = $checkInDate->diff($checkOutDate);
        return $interval->days;
    }

    /**
     * Get bookings by hotel with filters (simplified for debugging)
     */
    public function getBookingsByHotel($hotelId, $status = null, $dateFrom = null, $dateTo = null, $search = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('booking_history.*,
                                rooms.room_number,
                                room_types.type_name,
                                hotels.name as hotel_name')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->where('booking_history.hotel_id', $hotelId)
                        ->orderBy('booking_history.created_at', 'DESC');

        // Remove all filters temporarily to see all data
        // if ($status) {
        //     $builder->where('booking_history.status', $status);
        // }

        // if ($dateFrom) {
        //     $builder->where('booking_history.check_in_date >=', $dateFrom);
        // }

        // if ($dateTo) {
        //     $builder->where('booking_history.check_out_date <=', $dateTo);
        // }

        // if ($search) {
        //     $builder->groupStart()
        //            ->like('booking_history.person_full_name', $search)
        //            ->orLike('booking_history.person_phone', $search)
        //            ->orLike('booking_history.booking_ticket_no', $search)
        //            ->orLike('rooms.room_number', $search)
        //            ->groupEnd();
        // }

        return $builder->limit($limit, $offset)->findAll();
    }

    /**
     * Get booking statistics (simplified)
     */
    public function getBookingStatistics($hotelId, $dateFrom = null, $dateTo = null)
    {
        // Get all bookings for this hotel
        $allBookings = $this->where('hotel_id', $hotelId)->findAll();
        
        $stats = [
            'total' => ['count' => 0, 'revenue' => 0],
            'pending' => ['count' => 0],
            'confirmed' => ['count' => 0],
            'cancelled' => ['count' => 0],
            'completed' => ['count' => 0]
        ];

        foreach ($allBookings as $booking) {
            $stats['total']['count']++;
            $stats['total']['revenue'] += $booking['total_price'];
            
            $status = $booking['status'] ?? 'confirmed';
            if (isset($stats[$status])) {
                $stats[$status]['count']++;
            }
        }

        return $stats;
    }

    /**
     * Check room availability
     */
    public function checkRoomAvailability($roomId, $checkIn, $checkOut)
    {
        $count = $this->where('room_id', $roomId)
                      ->where('status !=', 'cancelled')
                      ->groupStart()
                          ->where('check_in_date <', $checkOut)
                          ->where('check_out_date >', $checkIn)
                      ->groupEnd()
                      ->countAllResultsExceptDeleted();

        return $count == 0;
    }

    /**
     * Get booking with details by ID
     */
    public function getBookingWithDetailsById($bookingId)
    {
        return $this->select('booking_history.*,
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
                            room_types.base_price')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                    ->where('booking_history.history_id', $bookingId)
                    ->first();
    }

    /**
     * Update booking status
     */
    public function updateBookingStatus($bookingId, $status)
    {
        return $this->update($bookingId, ['status' => $status]);
    }

    /**
     * Get booking by ticket number
     */
    public function getBookingByTicket($ticketNo)
    {
        return $this->where('booking_ticket_no', $ticketNo)->first();
    }

    /**
     * Get today's check-ins
     */
    public function getTodayCheckIns($hotelId)
    {
        return $this->select('booking_history.*,
                            rooms.room_number,
                            room_types.type_name')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                    ->where('booking_history.hotel_id', $hotelId)
                    ->where('booking_history.check_in_date', date('Y-m-d'))
                    ->where('booking_history.status', 'check_in') // CHANGED to check_in
                    ->findAll();
    }

    /**
     * Get today's check-outs
     */
    public function getTodayCheckOuts($hotelId)
    {
        return $this->select('booking_history.*,
                            rooms.room_number,
                            room_types.type_name')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                    ->where('booking_history.hotel_id', $hotelId)
                    ->where('booking_history.check_out_date', date('Y-m-d'))
                    ->where('booking_history.status', 'check_in') // CHANGED to check_in
                    ->findAll();
    }

    /**
     * Get current guests
     */
    public function getCurrentGuests($hotelId)
    {
        return $this->select('booking_history.*,
                            rooms.room_number,
                            room_types.type_name')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                    ->where('booking_history.hotel_id', $hotelId)
                    ->where('booking_history.check_in_date <=', date('Y-m-d'))
                    ->where('booking_history.check_out_date >', date('Y-m-d'))
                    ->where('booking_history.status', 'check_in') // CHANGED to check_in
                    ->findAll();
    }

    /**
     * Generate unique ticket number
     */
    public function generateTicketNumber($hotelId)
    {
        $prefix = 'HTL' . str_pad($hotelId, 3, '0', STR_PAD_LEFT);
        $timestamp = date('ymd');
        $random = mt_rand(1000, 9999);
        
        $ticketNo = $prefix . $timestamp . $random;
        
        // Check if it already exists
        while ($this->where('booking_ticket_no', $ticketNo)->first()) {
            $random = mt_rand(1000, 9999);
            $ticketNo = $prefix . $timestamp . $random;
        }
        
        return $ticketNo;
    }
}
