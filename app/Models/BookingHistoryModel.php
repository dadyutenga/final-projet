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
        'action_date'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'booking_ticket_no' => 'required|max_length[50]',
        'room_id'          => 'required|is_natural_no_zero',
        'hotel_id'         => 'required|is_natural_no_zero',
        'person_full_name' => 'required|max_length[100]',
        'person_phone'     => 'required|max_length[20]',
        'action_date'      => 'permit_empty|valid_date'
    ];
    
    protected $validationMessages   = [
        'booking_ticket_no' => [
            'required' => 'Booking ticket number is required',
            'max_length' => 'Booking ticket number cannot exceed 50 characters'
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
            'required' => 'Person phone number is required',
            'max_length' => 'Phone number cannot exceed 20 characters'
        ],
        'action_date' => [
            'valid_date' => 'Please enter a valid action date'
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
     * Get booking history with full details
     */
    public function getHistoryWithDetails($historyId)
    {
        return $this->select('booking_history.*,
                            rooms.room_number,
                            rooms.room_type,
                            rooms.price_per_night,
                            hotels.name as hotel_name,
                            hotels.city as hotel_city,
                            hotels.country as hotel_country,
                            hotels.address as hotel_address')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->where('booking_history.history_id', $historyId)
                    ->first();
    }

    /**
     * Get booking history by ticket number
     */
    public function getHistoryByTicket($ticketNo)
    {
        return $this->select('booking_history.*,
                            rooms.room_number,
                            rooms.room_type,
                            hotels.name as hotel_name,
                            hotels.city as hotel_city')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->where('booking_history.booking_ticket_no', $ticketNo)
                    ->orderBy('booking_history.action_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get booking history by person phone
     */
    public function getHistoryByPhone($phone, $limit = null, $offset = null)
    {
        $builder = $this->select('booking_history.*,
                                rooms.room_number,
                                rooms.room_type,
                                rooms.price_per_night,
                                hotels.name as hotel_name,
                                hotels.city as hotel_city')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->where('booking_history.person_phone', $phone)
                        ->orderBy('booking_history.action_date', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get booking history by person name
     */
    public function getHistoryByName($name, $limit = null, $offset = null)
    {
        $builder = $this->select('booking_history.*,
                                rooms.room_number,
                                rooms.room_type,
                                rooms.price_per_night,
                                hotels.name as hotel_name,
                                hotels.city as hotel_city')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->like('booking_history.person_full_name', $name)
                        ->orderBy('booking_history.action_date', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get booking history by hotel
     */
    public function getHistoryByHotel($hotelId, $dateFrom = null, $dateTo = null, $limit = null, $offset = null)
    {
        $builder = $this->select('booking_history.*,
                                rooms.room_number,
                                rooms.room_type,
                                rooms.price_per_night')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->where('booking_history.hotel_id', $hotelId)
                        ->orderBy('booking_history.action_date', 'DESC');

        if ($dateFrom) {
            $builder->where('booking_history.action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('booking_history.action_date <=', $dateTo);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get booking history by room
     */
    public function getHistoryByRoom($roomId, $dateFrom = null, $dateTo = null, $limit = null, $offset = null)
    {
        $builder = $this->select('booking_history.*,
                                rooms.room_number,
                                rooms.room_type,
                                hotels.name as hotel_name')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->where('booking_history.room_id', $roomId)
                        ->orderBy('booking_history.action_date', 'DESC');

        if ($dateFrom) {
            $builder->where('booking_history.action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('booking_history.action_date <=', $dateTo);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Log booking entry
     */
    public function logBooking($bookingTicketNo, $roomId, $hotelId, $personName, $personPhone)
    {
        return $this->insert([
            'booking_ticket_no' => $bookingTicketNo,
            'room_id' => $roomId,
            'hotel_id' => $hotelId,
            'person_full_name' => $personName,
            'person_phone' => $personPhone,
            'action_date' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get booking history statistics
     */
    public function getHistoryStatistics($hotelId = null, $roomId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('COUNT(*) as total_bookings')
                        ->select('COUNT(DISTINCT person_phone) as unique_guests')
                        ->select('COUNT(DISTINCT room_id) as rooms_used');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        if ($roomId) {
            $builder->where('room_id', $roomId);
        }

        if ($dateFrom) {
            $builder->where('action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('action_date <=', $dateTo);
        }

        return $builder->first();
    }

    /**
     * Get recent booking activities
     */
    public function getRecentActivities($hotelId = null, $roomId = null, $limit = 20)
    {
        $builder = $this->select('booking_history.*,
                                rooms.room_number,
                                rooms.room_type,
                                hotels.name as hotel_name,
                                hotels.city as hotel_city')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->orderBy('booking_history.action_date', 'DESC')
                        ->limit($limit);

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($roomId) {
            $builder->where('booking_history.room_id', $roomId);
        }

        return $builder->findAll();
    }

    /**
     * Get daily booking activities
     */
    public function getDailyActivities($hotelId = null, $roomId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('DATE(action_date) as date,
                                COUNT(*) as booking_count')
                        ->groupBy('DATE(action_date)')
                        ->orderBy('date', 'DESC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        if ($roomId) {
            $builder->where('room_id', $roomId);
        }

        if ($dateFrom) {
            $builder->where('action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('action_date <=', $dateTo);
        }

        return $builder->findAll();
    }

    /**
     * Get booking trends
     */
    public function getBookingTrends($hotelId = null, $months = 12)
    {
        $builder = $this->select('YEAR(action_date) as year,
                                MONTH(action_date) as month,
                                COUNT(*) as booking_count')
                        ->where('action_date >=', date('Y-m-d', strtotime("-{$months} months")))
                        ->groupBy('YEAR(action_date), MONTH(action_date)')
                        ->orderBy('year', 'DESC')
                        ->orderBy('month', 'DESC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Search booking history
     */
    public function searchHistory($searchTerm, $hotelId = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('booking_history.*,
                                rooms.room_number,
                                rooms.room_type,
                                hotels.name as hotel_name')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('booking_history.person_full_name', $searchTerm)
                   ->orLike('booking_history.person_phone', $searchTerm)
                   ->orLike('booking_history.booking_ticket_no', $searchTerm)
                   ->orLike('hotels.name', $searchTerm)
                   ->orLike('rooms.room_number', $searchTerm)
                   ->groupEnd();
        }

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        return $builder->orderBy('booking_history.action_date', 'DESC')
                      ->limit($limit, $offset)
                      ->findAll();
    }

    /**
     * Get person booking frequency by phone
     */
    public function getPersonBookingFrequency($phone, $months = 12)
    {
        return $this->select('YEAR(action_date) as year,
                            MONTH(action_date) as month,
                            COUNT(*) as booking_count')
                    ->where('person_phone', $phone)
                    ->where('action_date >=', date('Y-m-d', strtotime("-{$months} months")))
                    ->groupBy('YEAR(action_date), MONTH(action_date)')
                    ->orderBy('year', 'DESC')
                    ->orderBy('month', 'DESC')
                    ->findAll();
    }

    /**
     * Get hotel performance metrics
     */
    public function getHotelPerformance($hotelId, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('COUNT(*) as total_bookings,
                                COUNT(DISTINCT person_phone) as unique_guests,
                                COUNT(DISTINCT room_id) as rooms_booked,
                                DATE(action_date) as date')
                        ->where('hotel_id', $hotelId)
                        ->groupBy('DATE(action_date)')
                        ->orderBy('date', 'DESC');

        if ($dateFrom) {
            $builder->where('action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('action_date <=', $dateTo);
        }

        return $builder->findAll();
    }

    /**
     * Get room occupancy history
     */
    public function getRoomOccupancyHistory($roomId, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('booking_history.*,
                                hotels.name as hotel_name')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->where('booking_history.room_id', $roomId)
                        ->orderBy('booking_history.action_date', 'ASC');

        if ($dateFrom) {
            $builder->where('action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('action_date <=', $dateTo);
        }

        return $builder->findAll();
    }

    /**
     * Get guest history by phone number
     */
    public function getGuestHistory($phone)
    {
        return $this->select('booking_history.*,
                            rooms.room_number,
                            rooms.room_type,
                            hotels.name as hotel_name,
                            hotels.city as hotel_city')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->where('booking_history.person_phone', $phone)
                    ->orderBy('booking_history.action_date', 'DESC')
                    ->findAll();
    }

    /**
     * Generate unique booking ticket number
     */
    public function generateTicketNumber($hotelId)
    {
        do {
            $ticketNo = 'BK' . $hotelId . date('Ymd') . rand(1000, 9999);
        } while ($this->where('booking_ticket_no', $ticketNo)->first());
        
        return $ticketNo;
    }

    /**
     * Check if ticket number exists
     */
    public function ticketExists($ticketNo)
    {
        return $this->where('booking_ticket_no', $ticketNo)->first() !== null;
    }

    /**
     * Clean old history records
     */
    public function cleanOldHistory($daysToKeep = 365)
    {
        $cutoffDate = date('Y-m-d', strtotime("-{$daysToKeep} days"));
        return $this->where('action_date <', $cutoffDate)->delete();
    }

    /**
     * Get frequent guests
     */
    public function getFrequentGuests($hotelId = null, $minBookings = 3, $limit = 50)
    {
        $builder = $this->select('person_full_name,
                                person_phone,
                                COUNT(*) as total_bookings,
                                MAX(action_date) as last_booking_date,
                                MIN(action_date) as first_booking_date')
                        ->groupBy('person_phone')
                        ->having('total_bookings >=', $minBookings)
                        ->orderBy('total_bookings', 'DESC')
                        ->limit($limit);

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get booking count by date range
     */
    public function getBookingCountByDateRange($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('COUNT(*) as total_bookings');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        if ($dateFrom) {
            $builder->where('action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('action_date <=', $dateTo);
        }

        $result = $builder->first();
        return $result ? $result['total_bookings'] : 0;
    }

    /**
     * Get monthly booking summary
     */
    public function getMonthlyBookingSummary($hotelId = null, $year = null)
    {
        $year = $year ?: date('Y');
        
        $builder = $this->select('MONTH(action_date) as month,
                                COUNT(*) as booking_count,
                                COUNT(DISTINCT person_phone) as unique_guests')
                        ->where('YEAR(action_date)', $year)
                        ->groupBy('MONTH(action_date)')
                        ->orderBy('month', 'ASC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get guest loyalty data
     */
    public function getGuestLoyaltyData($hotelId = null)
    {
        $builder = $this->select('person_full_name,
                                person_phone,
                                COUNT(*) as visit_count,
                                MIN(action_date) as first_visit,
                                MAX(action_date) as last_visit,
                                DATEDIFF(MAX(action_date), MIN(action_date)) as days_as_customer')
                        ->groupBy('person_phone')
                        ->having('visit_count >', 1)
                        ->orderBy('visit_count', 'DESC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get room popularity statistics
     */
    public function getRoomPopularityStats($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('rooms.room_number,
                                rooms.room_type,
                                COUNT(*) as booking_count')
                        ->join('rooms', 'rooms.room_id = booking_history.room_id')
                        ->groupBy('booking_history.room_id')
                        ->orderBy('booking_count', 'DESC');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($dateFrom) {
            $builder->where('booking_history.action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('booking_history.action_date <=', $dateTo);
        }

        return $builder->findAll();
    }
}
