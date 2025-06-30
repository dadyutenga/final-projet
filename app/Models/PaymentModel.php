<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'payment_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'reservation_id',
        'amount',
        'payment_method',
        'payment_status',
        'payment_date'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'reservation_id'   => 'permit_empty|is_natural_no_zero',
        'amount'           => 'required|decimal|greater_than[0]',
        'payment_method'   => 'required|in_list[credit_card,debit_card,cash,online]',
        'payment_status'   => 'permit_empty|in_list[pending,completed,failed]',
        'payment_date'     => 'permit_empty|valid_date'
    ];
    protected $validationMessages   = [
        'amount' => [
            'required'     => 'Payment amount is required',
            'decimal'      => 'Payment amount must be a valid decimal number',
            'greater_than' => 'Payment amount must be greater than 0'
        ],
        'payment_method' => [
            'required' => 'Payment method is required',
            'in_list'  => 'Payment method must be one of: credit_card, debit_card, cash, online'
        ],
        'payment_status' => [
            'in_list'  => 'Payment status must be one of: pending, completed, failed'
        ],
        'payment_date' => [
            'valid_date' => 'Please enter a valid payment date'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setPaymentDate'];
    protected $afterInsert    = ['updateReservationStatus'];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = ['updateReservationStatus'];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Set payment date if not provided
     */
    protected function setPaymentDate(array $data)
    {
        if (!isset($data['data']['payment_date']) || empty($data['data']['payment_date'])) {
            $data['data']['payment_date'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Update reservation status after payment
     */
    protected function updateReservationStatus(array $data)
    {
        if (isset($data['id'])) {
            $payment = $this->find($data['id']);
            if ($payment && $payment['reservation_id'] && $payment['payment_status'] == 'completed') {
                $reservationModel = new \App\Models\ReservationModel();
                $reservationModel->updateReservationStatus($payment['reservation_id'], 'confirmed');
            }
        }
        return $data;
    }

    /**
     * Get payment with reservation details
     */
    public function getPaymentWithDetails($paymentId)
    {
        return $this->select('payments.*,
                            reservations.check_in_date,
                            reservations.check_out_date,
                            reservations.total_price as reservation_total,
                            booking_history.booking_ticket_no,
                            booking_history.person_full_name as guest_name,
                            hotels.name as hotel_name,
                            rooms.room_number')
                    ->join('reservations', 'reservations.reservation_id = payments.reservation_id', 'left')
                    ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->where('payments.payment_id', $paymentId)
                    ->first();
    }

    /**
     * Get payments by hotel
     */
    public function getPaymentsByHotel($hotelId, $status = null, $dateFrom = null, $dateTo = null, $limit = null, $offset = null)
    {
        $builder = $this->select('payments.*,
                            reservations.check_in_date,
                            reservations.check_out_date,
                            booking_history.booking_ticket_no,
                            booking_history.person_full_name as guest_name,
                            rooms.room_number')
                    ->join('reservations', 'reservations.reservation_id = payments.reservation_id', 'left')
                    ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->where('booking_history.hotel_id', $hotelId)
                    ->orderBy('payments.payment_date', 'DESC');

        if ($status) {
            $builder->where('payments.payment_status', $status);
        }

        if ($dateFrom) {
            $builder->where('payments.payment_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('payments.payment_date <=', $dateTo);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get payments by reservation
     */
    public function getPaymentsByReservation($reservationId)
    {
        return $this->where('reservation_id', $reservationId)
                    ->orderBy('payment_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStatistics($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('payment_status, payment_method, COUNT(*) as count, SUM(amount) as total_amount')
                        ->groupBy('payment_status, payment_method');

        if ($hotelId) {
            $builder->join('reservations', 'reservations.reservation_id = payments.reservation_id')
                   ->join('booking_history', 'booking_history.history_id = reservations.history_id')
                   ->where('booking_history.hotel_id', $hotelId);
        }

        if ($dateFrom) {
            $builder->where('payments.payment_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('payments.payment_date <=', $dateTo);
        }

        $results = $builder->findAll();

        $stats = [
            'status' => [
                'pending' => ['count' => 0, 'amount' => 0],
                'completed' => ['count' => 0, 'amount' => 0],
                'failed' => ['count' => 0, 'amount' => 0]
            ],
            'methods' => [
                'credit_card' => ['count' => 0, 'amount' => 0],
                'debit_card' => ['count' => 0, 'amount' => 0],
                'cash' => ['count' => 0, 'amount' => 0],
                'online' => ['count' => 0, 'amount' => 0]
            ],
            'total' => ['count' => 0, 'amount' => 0]
        ];

        foreach ($results as $result) {
            $status = $result['payment_status'] ?? 'pending';
            $method = $result['payment_method'] ?? 'cash';
            
            $stats['status'][$status] = [
                'count' => $result['count'],
                'amount' => $result['total_amount']
            ];
            $stats['methods'][$method] = [
                'count' => $result['count'],
                'amount' => $result['total_amount']
            ];
            $stats['total']['count'] += $result['count'];
            $stats['total']['amount'] += $result['total_amount'];
        }

        return $stats;
    }

    /**
     * Get daily revenue
     */
    public function getDailyRevenue($hotelId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('DATE(payment_date) as date, SUM(amount) as revenue, COUNT(*) as transactions')
                    ->where('payment_status', 'completed')
                    ->groupBy('DATE(payment_date)')
                    ->orderBy('date', 'ASC');

        if ($hotelId) {
            $builder->join('reservations', 'reservations.reservation_id = payments.reservation_id')
                   ->join('booking_history', 'booking_history.history_id = reservations.history_id')
                   ->where('booking_history.hotel_id', $hotelId);
        }

        if ($dateFrom) {
            $builder->where('payments.payment_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('payments.payment_date <=', $dateTo);
        }

        return $builder->findAll();
    }

    /**
     * Get monthly revenue
     */
    public function getMonthlyRevenue($hotelId = null, $year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $builder = $this->select('MONTH(payment_date) as month,
                                YEAR(payment_date) as year,
                                SUM(amount) as revenue,
                                COUNT(*) as transactions')
                        ->where('payment_status', 'completed')
                        ->where('YEAR(payment_date)', $year)
                        ->groupBy('YEAR(payment_date), MONTH(payment_date)')
                        ->orderBy('month', 'ASC');

        if ($hotelId) {
            $builder->join('reservations', 'reservations.reservation_id = payments.reservation_id')
                   ->join('booking_history', 'booking_history.history_id = reservations.history_id')
                   ->where('booking_history.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Process payment - simplified version
     */
    public function processPayment($paymentData)
    {
        try {
            $paymentId = $this->insert($paymentData);
            
            if ($paymentId) {
                return ['success' => true, 'payment_id' => $paymentId];
            } else {
                return ['success' => false, 'error' => 'Failed to create payment record'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Retry failed payment
     */
    public function retryPayment($paymentId)
    {
        $payment = $this->find($paymentId);
        if (!$payment || $payment['payment_status'] !== 'failed') {
            return ['success' => false, 'error' => 'Payment not found or not in failed status'];
        }

        try {
            // Update status to completed (simplified for demo)
            $this->update($paymentId, [
                'payment_status' => 'completed',
                'payment_date' => date('Y-m-d H:i:s')
            ]);
            
            return ['success' => true, 'payment_id' => $paymentId];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Payment retry failed: ' . $e->getMessage()];
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($paymentId, $status)
    {
        return $this->update($paymentId, ['payment_status' => $status]);
    }

    /**
     * Get failed payments
     */
    public function getFailedPayments($hotelId = null, $limit = null, $offset = null)
    {
        $builder = $this->select('payments.*,
                            reservations.check_in_date,
                            reservations.check_out_date,
                            booking_history.booking_ticket_no,
                            booking_history.person_full_name as guest_name,
                            hotels.name as hotel_name,
                            rooms.room_number')
                    ->join('reservations', 'reservations.reservation_id = payments.reservation_id', 'left')
                    ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->where('payments.payment_status', 'failed')
                    ->orderBy('payments.payment_date', 'DESC');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get pending payments
     */
    public function getPendingPayments($hotelId = null, $limit = null, $offset = null)
    {
        $builder = $this->select('payments.*,
                            reservations.check_in_date,
                            reservations.check_out_date,
                            booking_history.booking_ticket_no,
                            booking_history.person_full_name as guest_name,
                            hotels.name as hotel_name,
                            rooms.room_number')
                    ->join('reservations', 'reservations.reservation_id = payments.reservation_id', 'left')
                    ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                    ->where('payments.payment_status', 'pending')
                    ->orderBy('payments.payment_date', 'ASC');

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Retry failed payment
     */
    
    /**
     * Get refund amount for reservation
     */
    public function getRefundAmount($reservationId)
    {
        $payments = $this->getPaymentsByReservation($reservationId);
        $totalPaid = 0;

        foreach ($payments as $payment) {
            if ($payment['payment_status'] == 'completed') {
                $totalPaid += $payment['amount'];
            }
        }

        return $totalPaid;
    }

    /**
     * Search payments
     */
    public function searchPayments($searchTerm, $hotelId = null, $status = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('payments.*,
                            reservations.check_in_date,
                            reservations.check_out_date,
                            booking_history.booking_ticket_no,
                            booking_history.person_full_name as guest_name,
                            hotels.name as hotel_name,
                            rooms.room_number')
                    ->join('reservations', 'reservations.reservation_id = payments.reservation_id', 'left')
                    ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                    ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('hotels.name', $searchTerm)
                   ->orLike('payments.payment_id', $searchTerm)
                   ->orLike('payments.amount', $searchTerm)
                   ->orLike('booking_history.booking_ticket_no', $searchTerm)
                   ->orLike('booking_history.person_full_name', $searchTerm)
                   ->orLike('rooms.room_number', $searchTerm)
                   ->groupEnd();
        }

        if ($hotelId) {
            $builder->where('booking_history.hotel_id', $hotelId);
        }

        if ($status) {
            $builder->where('payments.payment_status', $status);
        }

        return $builder->orderBy('payments.payment_date', 'DESC')
                      ->limit($limit, $offset)
                      ->findAll();
    }
}
