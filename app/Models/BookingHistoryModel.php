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
        'reservation_id',
        'user_id',
        'hotel_id',
        'action',
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
        'reservation_id' => 'permit_empty|is_natural_no_zero',
        'user_id'        => 'permit_empty|is_natural_no_zero',
        'hotel_id'       => 'permit_empty|is_natural_no_zero',
        'action'         => 'required|in_list[created,updated,cancelled,completed]',
        'action_date'    => 'permit_empty|valid_date'
    ];
    protected $validationMessages   = [
        'action' => [
            'required' => 'Action is required',
            'in_list'  => 'Action must be one of: created, updated, cancelled, completed'
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
                            reservations.check_in_date,
                            reservations.check_out_date,
                            reservations.total_price,
                            reservations.status as reservation_status,
                            users.full_name as user_name,
                            users.email as user_email,
                            hotels.name as hotel_name,
                            hotels.city as hotel_city,
                            hotels.country as hotel_country,
                            rooms.room_number')
                    ->join('reservations', 'reservations.reservation_id = booking_history.reservation_id', 'left')
                    ->join('users', 'users.user_id = booking_history.user_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->join('rooms', 'rooms.room_id = reservations.room_id', 'left')
                    ->where('booking_history.history_id', $historyId)
                    ->first();
    }

    /**
     * Get booking history by reservation
     */
    public function getHistoryByReservation($reservationId)
    {
        return $this->select('booking_history.*,
                            users.full_name as user_name,
                            hotels.name as hotel_name')
                    ->join('users', 'users.user_id = booking_history.user_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->where('booking_history.reservation_id', $reservationId)
                    ->orderBy('booking_history.action_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get booking history by user
     */
    public function getHistoryByUser($userId, $action = null, $limit = null, $offset = null)
    {
        $builder = $this->select('booking_history.*,
                                reservations.check_in_date,
                                reservations.check_out_date,
                                reservations.total_price,
                                hotels.name as hotel_name,
                                hotels.city as hotel_city,
                                rooms.room_number')
                        ->join('reservations', 'reservations.reservation_id = booking_history.reservation_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = reservations.room_id', 'left')
                        ->where('booking_history.user_id', $userId)
                        ->orderBy('booking_history.action_date', 'DESC');

        if ($action) {
            $builder->where('booking_history.action', $action);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get booking history by hotel
     */
    public function getHistoryByHotel($hotelId, $action = null, $dateFrom = null, $dateTo = null, $limit = null, $offset = null)
    {
        $builder = $this->select('booking_history.*,
                                reservations.check_in_date,
                                reservations.check_out_date,
                                reservations.total_price,
                                users.full_name as user_name,
                                users.email as user_email,
                                rooms.room_number')
                        ->join('reservations', 'reservations.reservation_id = booking_history.reservation_id', 'left')
                        ->join('users', 'users.user_id = booking_history.user_id', 'left')
                        ->join('rooms', 'rooms.room_id = reservations.room_id', 'left')
                        ->where('booking_history.hotel_id', $hotelId)
                        ->orderBy('booking_history.action_date', 'DESC');

        if ($action) {
            $builder->where('booking_history.action', $action);
        }

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
     * Log booking action
     */
    public function logAction($reservationId, $userId, $hotelId, $action)
    {
        return $this->insert([
            'reservation_id' => $reservationId,
            'user_id' => $userId,
            'hotel_id' => $hotelId,
            'action' => $action,
            'action_date' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get booking history statistics
     */
    public function getHistoryStatistics($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('action, COUNT(*) as count')
                        ->groupBy('action');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        if ($dateFrom) {
            $builder->where('action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('action_date <=', $dateTo);
        }

        $results = $builder->findAll();

        $stats = [
            'created' => 0,
            'updated' => 0,
            'cancelled' => 0,
            'completed' => 0,
            'total' => 0
        ];

        foreach ($results as $result) {
            $stats[$result['action']] = $result['count'];
            $stats['total'] += $result['count'];
        }

        return $stats;
    }

    /**
     * Get recent booking activities
     */
    public function getRecentActivities($hotelId = null, $userId = null, $limit = 20)
    {
        $builder = $this->select('booking_history.*,
                                reservations.check_in_date,
                                reservations.check_out_date,
                                users.full_name as user_name,
                                hotels.name as hotel_name,
                                hotels.city as hotel_city,
                                rooms.room_number')
                        ->join('reservations', 'reservations.reservation_id = booking_history.reservation_id', 'left')
                        ->join('users', 'users.user_id = booking_history.user_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = reservations.room_id', 'left')
                        ->orderBy('booking_history.action_date', 'DESC')
                        ->limit($limit);

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($userId) {
            $builder->where('booking_history.user_id', $userId);
        }

        return $builder->findAll();
    }

    /**
     * Get daily booking activities
     */
    public function getDailyActivities($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('DATE(action_date) as date,
                                action,
                                COUNT(*) as count')
                        ->groupBy('DATE(action_date), action')
                        ->orderBy('date', 'DESC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
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
                                action,
                                COUNT(*) as count')
                        ->where('action_date >=', date('Y-m-d', strtotime("-{$months} months")))
                        ->groupBy('YEAR(action_date), MONTH(action_date), action')
                        ->orderBy('year', 'DESC')
                        ->orderBy('month', 'DESC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get cancellation reasons analysis
     */
    public function getCancellationAnalysis($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('DATE(action_date) as date,
                                COUNT(*) as cancelled_count,
                                hotels.name as hotel_name')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->where('action', 'cancelled')
                        ->groupBy('DATE(action_date)');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        } else {
            $builder->groupBy('booking_history.hotel_id');
        }

        if ($dateFrom) {
            $builder->where('action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('action_date <=', $dateTo);
        }

        return $builder->orderBy('date', 'DESC')->findAll();
    }

    /**
     * Search booking history
     */
    public function searchHistory($searchTerm, $hotelId = null, $action = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('booking_history.*,
                                reservations.check_in_date,
                                reservations.check_out_date,
                                users.full_name as user_name,
                                users.email as user_email,
                                hotels.name as hotel_name,
                                rooms.room_number')
                        ->join('reservations', 'reservations.reservation_id = booking_history.reservation_id', 'left')
                        ->join('users', 'users.user_id = booking_history.user_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                        ->join('rooms', 'rooms.room_id = reservations.room_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('users.full_name', $searchTerm)
                   ->orLike('users.email', $searchTerm)
                   ->orLike('hotels.name', $searchTerm)
                   ->orLike('rooms.room_number', $searchTerm)
                   ->orLike('booking_history.reservation_id', $searchTerm)
                   ->groupEnd();
        }

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($action) {
            $builder->where('booking_history.action', $action);
        }

        return $builder->orderBy('booking_history.action_date', 'DESC')
                      ->limit($limit, $offset)
                      ->findAll();
    }

    /**
     * Get user booking frequency
     */
    public function getUserBookingFrequency($userId, $months = 12)
    {
        return $this->select('YEAR(action_date) as year,
                            MONTH(action_date) as month,
                            COUNT(CASE WHEN action = "created" THEN 1 END) as bookings_created,
                            COUNT(CASE WHEN action = "cancelled" THEN 1 END) as bookings_cancelled,
                            COUNT(CASE WHEN action = "completed" THEN 1 END) as bookings_completed')
                    ->where('user_id', $userId)
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
        $builder = $this->select('action,
                                COUNT(*) as count,
                                DATE(action_date) as date')
                        ->where('hotel_id', $hotelId)
                        ->groupBy('DATE(action_date), action')
                        ->orderBy('date', 'DESC');

        if ($dateFrom) {
            $builder->where('action_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('action_date <=', $dateTo);
        }

        $results = $builder->findAll();

        // Process results to calculate performance metrics
        $performance = [
            'conversion_rate' => 0,
            'cancellation_rate' => 0,
            'completion_rate' => 0,
            'daily_stats' => []
        ];

        $totalCreated = 0;
        $totalCancelled = 0;
        $totalCompleted = 0;

        foreach ($results as $result) {
            if (!isset($performance['daily_stats'][$result['date']])) {
                $performance['daily_stats'][$result['date']] = [
                    'created' => 0,
                    'updated' => 0,
                    'cancelled' => 0,
                    'completed' => 0
                ];
            }

            $performance['daily_stats'][$result['date']][$result['action']] = $result['count'];

            if ($result['action'] == 'created') {
                $totalCreated += $result['count'];
            } elseif ($result['action'] == 'cancelled') {
                $totalCancelled += $result['count'];
            } elseif ($result['action'] == 'completed') {
                $totalCompleted += $result['count'];
            }
        }

        if ($totalCreated > 0) {
            $performance['cancellation_rate'] = ($totalCancelled / $totalCreated) * 100;
            $performance['completion_rate'] = ($totalCompleted / $totalCreated) * 100;
            $performance['conversion_rate'] = (($totalCreated - $totalCancelled) / $totalCreated) * 100;
        }

        return $performance;
    }

    /**
     * Clean old history records
     */
    public function cleanOldHistory($daysToKeep = 365)
    {
        $cutoffDate = date('Y-m-d', strtotime("-{$daysToKeep} days"));

        return $this->where('action_date <', $cutoffDate)->delete();
    }
}
