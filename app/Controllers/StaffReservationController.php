<?php

namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\BookingHistoryModel;
use App\Models\RoomModel;
use App\Models\HotelModel;
use App\Models\StaffModel;

class StaffReservationController extends BaseController
{
    protected $reservationModel;
    protected $bookingHistoryModel;
    protected $roomModel;
    protected $hotelModel;
    protected $staffModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->bookingHistoryModel = new BookingHistoryModel();
        $this->roomModel = new RoomModel();
        $this->hotelModel = new HotelModel();
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
     * Display all reservations for staff (index)
     */
    public function index()
    {
        $hotelId = $this->getStaffHotelId();

        // Get filter parameters
        $status = $this->request->getGet('status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $search = $this->request->getGet('search');

        // Get reservations with filters
        $reservations = $this->reservationModel->getReservationsByHotel($hotelId, $status, $dateFrom, $dateTo);

        // Apply search filter if provided
        if (!empty($search)) {
            $reservations = $this->reservationModel->searchReservations($search, $hotelId, $status);
        }

        // Get statistics
        $stats = $this->reservationModel->getReservationStatistics($hotelId, $dateFrom, $dateTo);

        $data = [
            'title' => 'Manage Reservations',
            'reservations' => $reservations,
            'stats' => $stats,
            'current_status' => $status,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'search' => $search
        ];

        return view('staff/reservations/index', $data);
    }

    /**
     * Show create reservation form
     */
    public function create()
    {
        $hotelId = $this->getStaffHotelId();

        // Get confirmed bookings that don't have reservations yet
        $availableBookings = $this->bookingHistoryModel->select('booking_history.*,
                                                                hotels.name as hotel_name,
                                                                rooms.room_number,
                                                                room_types.type_name')
                                                       ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                                                       ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                                                       ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                                                       ->where('booking_history.hotel_id', $hotelId)
                                                       ->where('booking_history.status', 'confirmed')
                                                       ->whereNotIn('booking_history.history_id', function($builder) {
                                                           $builder->select('history_id')
                                                                  ->from('reservations')
                                                                  ->where('history_id IS NOT NULL');
                                                       })
                                                       ->orderBy('booking_history.check_in_date', 'ASC')
                                                       ->findAll();

        // Get all staff for assignment
        $allStaff = $this->staffModel->where('hotel_id', $hotelId)->findAll();

        $data = [
            'title' => 'Create New Reservation',
            'availableBookings' => $availableBookings,
            'allStaff' => $allStaff
        ];

        return view('staff/reservations/create', $data);
    }

    /**
     * Store new reservation
     */
    public function store()
    {
        $hotelId = $this->getStaffHotelId();
        $staffId = session()->get('staff_id');

        // Validation rules
        $rules = [
            'booking_id' => 'required|is_natural_no_zero',
            'assigned_staff_id' => 'permit_empty|is_natural_no_zero',
            'check_in_date' => 'required|valid_date',
            'check_out_date' => 'required|valid_date',
            'total_price' => 'required|decimal|greater_than[0]',
            'status' => 'required|in_list[pending,confirmed,cancelled,completed]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();

        try {
            // Get the booking details
            $booking = $this->bookingHistoryModel->find($data['booking_id']);
            if (!$booking) {
                return redirect()->back()->withInput()->with('error', 'Booking not found');
            }

            // Check if booking belongs to staff's hotel
            if ($booking['hotel_id'] != $hotelId) {
                return redirect()->back()->withInput()->with('error', 'Access denied');
            }

            // Check if reservation already exists for this booking
            $existingReservation = $this->reservationModel->where('history_id', $booking['history_id'])->first();
            if ($existingReservation) {
                return redirect()->back()->withInput()->with('error', 'Reservation already exists for this booking');
            }

            // Validate dates
            $checkInDate = new \DateTime($data['check_in_date']);
            $checkOutDate = new \DateTime($data['check_out_date']);

            if ($checkOutDate <= $checkInDate) {
                return redirect()->back()->withInput()->with('error', 'Check-out date must be after check-in date');
            }

            // Create reservation
            $reservationData = [
                'history_id' => $booking['history_id'],
                'staff_id' => !empty($data['assigned_staff_id']) ? $data['assigned_staff_id'] : $staffId,
                'check_in_date' => $data['check_in_date'],
                'check_out_date' => $data['check_out_date'],
                'total_price' => $data['total_price'],
                'status' => $data['status']
            ];

            $reservationId = $this->reservationModel->insert($reservationData);

            if (!$reservationId) {
                throw new \Exception('Failed to create reservation');
            }

            // Update booking status if needed
            if ($data['status'] == 'confirmed') {
                $this->bookingHistoryModel->update($booking['history_id'], ['status' => 'confirmed']);
            }

            return redirect()->to('/staff/reservations')->with('success', 'Reservation created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create reservation: ' . $e->getMessage());
        }
    }

    /**
     * View reservation details
     */
    public function view($reservationId)
    {
        $hotelId = $this->getStaffHotelId();

        $reservation = $this->reservationModel->getReservationWithDetails($reservationId);

        if (!$reservation) {
            return redirect()->to('/staff/reservations')->with('error', 'Reservation not found');
        }

        // Check if this reservation belongs to staff's hotel
        if ($reservation['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/reservations')->with('error', 'Access denied');
        }

        $data = [
            'title' => 'Reservation Details',
            'reservation' => $reservation
        ];

        return view('staff/reservations/view', $data);
    }

    /**
     * Confirm reservation
     */
    public function confirm($reservationId)
    {
        $hotelId = $this->getStaffHotelId();

        $reservation = $this->reservationModel->find($reservationId);
        if (!$reservation) {
            return redirect()->to('/staff/reservations')->with('error', 'Reservation not found');
        }

        // Get hotel_id from reservation
        $reservationDetails = $this->reservationModel->getReservationWithDetails($reservationId);
        if ($reservationDetails['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/reservations')->with('error', 'Access denied');
        }

        if ($reservation['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Only pending reservations can be confirmed');
        }

        try {
            $this->reservationModel->update($reservationId, ['status' => 'confirmed']);
            return redirect()->back()->with('success', 'Reservation confirmed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to confirm reservation: ' . $e->getMessage());
        }
    }

    /**
     * Cancel reservation
     */
    public function cancel($reservationId)
    {
        $hotelId = $this->getStaffHotelId();

        $reservation = $this->reservationModel->find($reservationId);
        if (!$reservation) {
            return redirect()->to('/staff/reservations')->with('error', 'Reservation not found');
        }

        // Get hotel_id from reservation
        $reservationDetails = $this->reservationModel->getReservationWithDetails($reservationId);
        if ($reservationDetails['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/reservations')->with('error', 'Access denied');
        }

        if ($reservation['status'] == 'completed') {
            return redirect()->back()->with('error', 'Cannot cancel completed reservations');
        }

        try {
            $this->reservationModel->cancelReservation($reservationId);
            return redirect()->back()->with('success', 'Reservation cancelled successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel reservation: ' . $e->getMessage());
        }
    }

    /**
     * Complete reservation
     */
    public function complete($reservationId)
    {
        $hotelId = $this->getStaffHotelId();

        $reservation = $this->reservationModel->find($reservationId);
        if (!$reservation) {
            return redirect()->to('/staff/reservations')->with('error', 'Reservation not found');
        }

        // Get hotel_id from reservation
        $reservationDetails = $this->reservationModel->getReservationWithDetails($reservationId);
        if ($reservationDetails['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/reservations')->with('error', 'Access denied');
        }

        if ($reservation['status'] !== 'confirmed') {
            return redirect()->back()->with('error', 'Only confirmed reservations can be completed');
        }

        try {
            $this->reservationModel->completeReservation($reservationId);
            return redirect()->back()->with('success', 'Reservation completed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to complete reservation: ' . $e->getMessage());
        }
    }

    /**
     * Get booking details via AJAX
     */
    public function getBookingDetails()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $bookingId = $this->request->getPost('booking_id');
        $hotelId = $this->getStaffHotelId();

        try {
            $booking = $this->bookingHistoryModel->select('booking_history.*,
                                                          hotels.name as hotel_name,
                                                          rooms.room_number,
                                                          room_types.type_name,
                                                          room_types.base_price')
                                                  ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                                                  ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                                                  ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left')
                                                  ->where('booking_history.history_id', $bookingId)
                                                  ->where('booking_history.hotel_id', $hotelId)
                                                  ->first();

            if (!$booking) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Booking not found'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error loading booking details: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete reservation
     */
    public function delete($reservationId)
    {
        $hotelId = $this->getStaffHotelId();

        $reservation = $this->reservationModel->find($reservationId);
        if (!$reservation) {
            return redirect()->to('/staff/reservations')->with('error', 'Reservation not found');
        }

        // Get hotel_id from reservation
        $reservationDetails = $this->reservationModel->getReservationWithDetails($reservationId);
        if ($reservationDetails['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/reservations')->with('error', 'Access denied');
        }

        // Allow deletion of all reservation statuses
        try {
            $this->reservationModel->delete($reservationId);
            return redirect()->to('/staff/reservations')->with('success', 'Reservation deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete reservation: ' . $e->getMessage());
        }
    }
}