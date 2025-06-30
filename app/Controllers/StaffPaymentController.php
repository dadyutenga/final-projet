<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Models\ReservationModel;
use App\Models\StaffModel;

class StaffPaymentController extends BaseController
{
    protected $paymentModel;
    protected $reservationModel;
    protected $staffModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->reservationModel = new ReservationModel();
        $this->staffModel = new StaffModel();
        
        // Ensure the user is a logged-in staff member
        if (!session()->get('staff_id')) {
            redirect()->to('/staff/login')->send();
            exit();
        }
    }

    /**
     * Helper method to get hotel_id for current staff
     */
    private function getStaffHotelId()
    {
        $staffId = session()->get('staff_id');
        $db = \Config\Database::connect();
        $staffQuery = $db->table('staff')->where('staff_id', $staffId)->get();
        $staffData = $staffQuery->getRowArray();
        
        return $staffData['hotel_id'] ?? null;
    }

    /**
     * Display all payments for staff (index)
     */
    public function index()
    {
        $hotelId = $this->getStaffHotelId();

        // Get filter parameters
        $status = $this->request->getGet('status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $search = $this->request->getGet('search');

        // Get payments with filters
        $payments = $this->paymentModel->getPaymentsByHotel($hotelId, $status, $dateFrom, $dateTo);

        // Apply search filter if provided
        if (!empty($search)) {
            $payments = $this->paymentModel->searchPayments($search, $hotelId, $status);
        }

        // Get statistics
        $stats = $this->paymentModel->getPaymentStatistics($hotelId, $dateFrom, $dateTo);

        $data = [
            'title' => 'Manage Payments',
            'payments' => $payments,
            'stats' => $stats,
            'current_status' => $status,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'search' => $search
        ];

        return view('staff/payments/index', $data);
    }

    /**
     * Show create payment form
     */
    public function create()
    {
        $hotelId = $this->getStaffHotelId();

        // Get confirmed reservations that don't have completed payments
        $availableReservations = $this->reservationModel->select('reservations.*,
                                                                 booking_history.booking_ticket_no,
                                                                 booking_history.person_full_name as guest_name,
                                                                 booking_history.person_phone as guest_phone,
                                                                 hotels.name as hotel_name,
                                                                 rooms.room_number,
                                                                 room_types.type_name')
                                                        ->join('booking_history', 'booking_history.history_id = reservations.history_id', 'left')
                                                        ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                                                        ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                                                        ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                                                        ->where('booking_history.hotel_id', $hotelId)
                                                        ->whereIn('reservations.status', ['confirmed', 'pending'])
                                                        ->whereNotIn('reservations.reservation_id', function($builder) {
                                                            $builder->select('reservation_id')
                                                                   ->from('payments')
                                                                   ->where('payment_status', 'completed')
                                                                   ->where('reservation_id IS NOT NULL');
                                                        })
                                                        ->orderBy('reservations.check_in_date', 'ASC')
                                                        ->findAll();

        $data = [
            'title' => 'Process Payment',
            'availableReservations' => $availableReservations
        ];

        return view('staff/payments/create', $data);
    }

    /**
     * Store new payment
     */
    public function store()
    {
        $hotelId = $this->getStaffHotelId();

        // Validation rules
        $rules = [
            'reservation_id' => 'required|is_natural_no_zero',
            'amount' => 'required|decimal|greater_than[0]',
            'payment_method' => 'required|in_list[credit_card,debit_card,cash,online]',
            'payment_status' => 'permit_empty|in_list[pending,completed,failed]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();

        try {
            // Get the reservation details
            $reservation = $this->reservationModel->getReservationWithDetails($data['reservation_id']);
            if (!$reservation) {
                return redirect()->back()->withInput()->with('error', 'Reservation not found');
            }

            // Check if reservation belongs to staff's hotel
            if ($reservation['hotel_id'] != $hotelId) {
                return redirect()->back()->withInput()->with('error', 'Access denied');
            }

            // Check if payment already exists for this reservation
            $existingPayment = $this->paymentModel->where('reservation_id', $data['reservation_id'])
                                                 ->where('payment_status', 'completed')
                                                 ->first();
            if ($existingPayment) {
                return redirect()->back()->withInput()->with('error', 'Payment already completed for this reservation');
            }

            // Create payment directly instead of using processPayment method
            $paymentData = [
                'reservation_id' => $data['reservation_id'],
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_status'] ?? 'completed',
                'payment_date' => date('Y-m-d H:i:s')
            ];

            $paymentId = $this->paymentModel->insert($paymentData);

            if ($paymentId) {
                // Update reservation status if payment is completed
                if ($paymentData['payment_status'] == 'completed') {
                    $this->reservationModel->updateReservationStatus($data['reservation_id'], 'confirmed');
                }
                
                return redirect()->to('/staff/payments')->with('success', 'Payment processed successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to create payment record');
            }

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }

    /**
     * View payment details
     */
    public function view($paymentId)
    {
        $hotelId = $this->getStaffHotelId();

        $payment = $this->paymentModel->getPaymentWithDetails($paymentId);

        if (!$payment) {
            return redirect()->to('/staff/payments')->with('error', 'Payment not found');
        }

        // Get full reservation details
        $reservation = $this->reservationModel->getReservationWithDetails($payment['reservation_id']);

        // Check if this payment belongs to staff's hotel
        if ($reservation['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/payments')->with('error', 'Access denied');
        }

        $data = [
            'title' => 'Payment Details',
            'payment' => $payment,
            'reservation' => $reservation
        ];

        return view('staff/payments/view', $data);
    }

    /**
     * Retry failed payment
     */
    public function retry($paymentId)
    {
        $hotelId = $this->getStaffHotelId();

        $payment = $this->paymentModel->find($paymentId);
        if (!$payment) {
            return redirect()->to('/staff/payments')->with('error', 'Payment not found');
        }

        // Get reservation to check hotel access
        $reservation = $this->reservationModel->getReservationWithDetails($payment['reservation_id']);
        if ($reservation['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/payments')->with('error', 'Access denied');
        }

        if ($payment['payment_status'] !== 'failed') {
            return redirect()->back()->with('error', 'Only failed payments can be retried');
        }

        try {
            $result = $this->paymentModel->retryPayment($paymentId);

            if ($result['success']) {
                return redirect()->back()->with('success', 'Payment retry successful');
            } else {
                return redirect()->back()->with('error', $result['error']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to retry payment: ' . $e->getMessage());
        }
    }

    /**
     * Update payment status
     */
    public function updateStatus($paymentId)
    {
        $hotelId = $this->getStaffHotelId();
        $status = $this->request->getPost('status');

        if (!in_array($status, ['pending', 'completed', 'failed'])) {
            return redirect()->back()->with('error', 'Invalid payment status');
        }

        $payment = $this->paymentModel->find($paymentId);
        if (!$payment) {
            return redirect()->to('/staff/payments')->with('error', 'Payment not found');
        }

        try {
            // Get reservation to check hotel access
            $reservation = $this->reservationModel->getReservationWithDetails($payment['reservation_id']);
            if (!$reservation || $reservation['hotel_id'] != $hotelId) {
                return redirect()->to('/staff/payments')->with('error', 'Access denied');
            }

            // Update payment status
            $this->paymentModel->updatePaymentStatus($paymentId, $status);
            
            // Update reservation status if payment is completed
            if ($status == 'completed') {
                $this->reservationModel->updateReservationStatus($payment['reservation_id'], 'confirmed');
            }
            
            return redirect()->back()->with('success', 'Payment status updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update payment status: ' . $e->getMessage());
        }
    }

    /**
     * Delete payment
     */
    public function delete($paymentId)
    {
        $hotelId = $this->getStaffHotelId();

        $payment = $this->paymentModel->find($paymentId);
        if (!$payment) {
            return redirect()->to('/staff/payments')->with('error', 'Payment not found');
        }

        // Get reservation to check hotel access
        $reservation = $this->reservationModel->getReservationWithDetails($payment['reservation_id']);
        if ($reservation['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/payments')->with('error', 'Access denied');
        }

        // Only allow deletion of failed or pending payments
        if ($payment['payment_status'] == 'completed') {
            return redirect()->back()->with('error', 'Cannot delete completed payments');
        }

        try {
            $this->paymentModel->delete($paymentId);
            return redirect()->to('/staff/payments')->with('success', 'Payment deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete payment: ' . $e->getMessage());
        }
    }

    /**
     * Get reservation details via AJAX
     */
    public function getReservationDetails()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $reservationId = $this->request->getPost('reservation_id');
        $hotelId = $this->getStaffHotelId();

        try {
            $reservation = $this->reservationModel->getReservationWithDetails($reservationId);

            if (!$reservation || $reservation['hotel_id'] != $hotelId) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Reservation not found'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'reservation' => $reservation
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error loading reservation details: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get payment statistics for dashboard
     */
    public function getStats()
    {
        $hotelId = $this->getStaffHotelId();
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $stats = $this->paymentModel->getPaymentStatistics($hotelId, $dateFrom, $dateTo);
        $dailyRevenue = $this->paymentModel->getDailyRevenue($hotelId, $dateFrom, $dateTo);

        return $this->response->setJSON([
            'success' => true,
            'stats' => $stats,
            'daily_revenue' => $dailyRevenue
        ]);
    }
}