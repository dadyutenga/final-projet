<?php

namespace App\Controllers;

use App\Models\BookingHistoryModel;
use App\Models\RoomModel;
use App\Models\HotelModel;
use App\Models\RoomTypeModel;
use CodeIgniter\Controller;

class StaffBookingController extends BaseController
{
    protected $bookingHistoryModel;
    protected $roomModel;
    protected $hotelModel;
    protected $roomTypeModel;

    public function __construct()
    {
        $this->bookingHistoryModel = new BookingHistoryModel();
        $this->roomModel = new RoomModel();
        $this->hotelModel = new HotelModel();
        $this->roomTypeModel = new RoomTypeModel();
        
        // Ensure the user is a logged-in staff member
        if (!session()->get('staff_id')) {
            redirect()->to('/staff/login')->send();
            exit();
        }
    }

    /**
     * Display all bookings for staff (index)
     */
    public function index()
    {
        $staffId = session()->get('staff_id');
        
        // Get hotel_id from staff record instead of session
        $db = \Config\Database::connect();
        $staffQuery = $db->table('staff')->where('staff_id', $staffId)->get();
        $staffData = $staffQuery->getRowArray();
        
        $hotelId = $staffData['hotel_id'] ?? null;
        
        // DEBUG: Let's see what we got
        log_message('debug', 'Staff ID: ' . $staffId);
        log_message('debug', 'Hotel ID from staff table: ' . $hotelId);

        // Get filter parameters
        $status = $this->request->getGet('status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $search = $this->request->getGet('search');

        // Get ALL bookings without hotel filter (temporary fix)
        $builder = $this->bookingHistoryModel->select('booking_history.*,
                                                   hotels.name as hotel_name,
                                                   rooms.room_number,
                                                   room_types.type_name')
                                          ->join('hotels', 'hotels.hotel_id = booking_history.hotel_id', 'left')
                                          ->join('rooms', 'rooms.room_id = booking_history.room_id', 'left')
                                          ->join('room_types', 'room_types.room_type_id = rooms.room_type_id', 'left');

    // Filter by hotel if we have hotel_id
    if ($hotelId) {
        $builder->where('booking_history.hotel_id', $hotelId);
    }

    // Apply other filters only if they have values
    if (!empty($status)) {
        $builder->where('booking_history.status', $status);
    }

    if (!empty($dateFrom)) {
        $builder->where('booking_history.check_in_date >=', $dateFrom);
    }

    if (!empty($dateTo)) {
        $builder->where('booking_history.check_out_date <=', $dateTo);
    }

    if (!empty($search)) {
        $builder->groupStart()
                ->like('booking_history.person_full_name', $search)
                ->orLike('booking_history.person_phone', $search)
                ->orLike('booking_history.booking_ticket_no', $search)
                ->orLike('rooms.room_number', $search)
                ->groupEnd();
    }

    $bookings = $builder->orderBy('booking_history.created_at', 'DESC')->findAll();

    // DEBUG: Let's see what we got
    log_message('debug', 'Total bookings found: ' . count($bookings));

    // Get statistics
    $stats = $this->bookingHistoryModel->getBookingStatistics($hotelId, $dateFrom, $dateTo);

    $data = [
        'title' => 'Manage Bookings',
        'bookings' => $bookings,
        'stats' => $stats,
        'current_status' => $status,
        'date_from' => $dateFrom,
        'date_to' => $dateTo,
        'search' => $search,
        'hotel_id' => $hotelId
    ];

    return view('staff/bookings/index', $data);
    }

    /**
     * Show create booking form - redirect to customer booking
     */
    public function create()
    {
        // Redirect staff to use the working customer booking system
        return redirect()->to('/book')->with('info', 'Please use the booking form below to create a new booking.');
    }

    /**
     * Store new booking (created by staff)
     */
    public function store()
    {
        $staffId = session()->get('staff_id');
        
        // Get hotel_id from staff record instead of session
        $db = \Config\Database::connect();
        $staffQuery = $db->table('staff')->where('staff_id', $staffId)->get();
        $staffData = $staffQuery->getRowArray();
        
        $hotelId = $staffData['hotel_id'] ?? null;

        // Validation rules
        $rules = [
            'guest_name' => 'required|min_length[2]|max_length[100]',
            'guest_phone' => 'required|min_length[10]|max_length[20]',
            'guest_email' => 'permit_empty|valid_email',
            'room_id' => 'required|is_natural_no_zero',
            'check_in_date' => 'required|valid_date',
            'check_out_date' => 'required|valid_date',
            'guests_count' => 'required|is_natural_no_zero',
            'total_price' => 'required|decimal|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();

        try {
            // Validate dates
            $checkInDate = new \DateTime($data['check_in_date']);
            $checkOutDate = new \DateTime($data['check_out_date']);
            $today = new \DateTime(date('Y-m-d'));

            if ($checkInDate < $today) {
                return redirect()->back()->withInput()->with('error', 'Check-in date cannot be in the past');
            }

            if ($checkOutDate <= $checkInDate) {
                return redirect()->back()->withInput()->with('error', 'Check-out date must be after check-in date');
            }

            // Check room availability using existing method
            $isAvailable = $this->bookingHistoryModel->checkRoomAvailability(
                $data['room_id'], 
                $data['check_in_date'], 
                $data['check_out_date']
            );

            if (!$isAvailable) {
                return redirect()->back()->withInput()->with('error', 'Selected room is not available for the chosen dates');
            }

            // Get room details using existing method
            $room = $this->roomModel->getRoomWithDetails($data['room_id']);

            if (!$room) {
                return redirect()->back()->withInput()->with('error', 'Room not found');
            }

            // Check if room capacity is sufficient
            if ($room['capacity'] < $data['guests_count']) {
                return redirect()->back()->withInput()->with('error', 'Room capacity is insufficient for the number of guests');
            }

            // Generate booking ticket number using existing method
            $bookingTicket = $this->bookingHistoryModel->generateTicketNumber($hotelId);

            // Create booking history entry (staff bookings are confirmed by default)
            $bookingData = [
                'booking_ticket_no' => $bookingTicket,
                'room_id' => $data['room_id'],
                'hotel_id' => $hotelId,
                'person_full_name' => trim($data['guest_name']),
                'person_phone' => trim($data['guest_phone']),
                'check_in_date' => $data['check_in_date'],
                'check_out_date' => $data['check_out_date'],
                'total_price' => $data['total_price'],
                'guests_count' => $data['guests_count'],
                'guest_email' => !empty($data['guest_email']) ? trim($data['guest_email']) : null,
                'status' => 'confirmed' // Staff bookings are confirmed by default
            ];

            $bookingId = $this->bookingHistoryModel->insert($bookingData);

            if (!$bookingId) {
                throw new \Exception('Failed to create booking');
            }

            return redirect()->to('/staff/bookings')->with('success', 'Booking created successfully! Ticket: ' . $bookingTicket);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    /**
     * View booking details
     */
    public function view($bookingId)
    {
        $staffId = session()->get('staff_id');
        
        // Get hotel_id from staff record instead of session
        $db = \Config\Database::connect();
        $staffQuery = $db->table('staff')->where('staff_id', $staffId)->get();
        $staffData = $staffQuery->getRowArray();
        
        $hotelId = $staffData['hotel_id'] ?? null;

        // Get booking with basic details first
        $booking = $this->bookingHistoryModel->find($bookingId);

        if (!$booking) {
            return redirect()->to('/staff/bookings')->with('error', 'Booking not found');
        }

        // Check if this booking belongs to staff's hotel
        if ($booking['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/bookings')->with('error', 'Access denied');
        }

        // Get full booking details using the ticket number
        $bookingDetails = $this->bookingHistoryModel->getBookingWithDetails($booking['booking_ticket_no']);

        $data = [
            'title' => 'Booking Details',
            'booking' => $bookingDetails ?: $booking
        ];

        return view('staff/bookings/view', $data);
    }

    /**
     * Confirm booking (change from pending to confirmed)
     */
    public function confirm($bookingId)
    {
        $staffId = session()->get('staff_id');
        
        // Get hotel_id from staff record instead of session
        $db = \Config\Database::connect();
        $staffQuery = $db->table('staff')->where('staff_id', $staffId)->get();
        $staffData = $staffQuery->getRowArray();
        
        $hotelId = $staffData['hotel_id'] ?? null;

        $booking = $this->bookingHistoryModel->find($bookingId);
        if (!$booking) {
            return redirect()->to('/staff/bookings')->with('error', 'Booking not found');
        }

        // Check if this booking belongs to staff's hotel
        if ($booking['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/bookings')->with('error', 'Access denied');
        }

        if ($booking['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Only pending bookings can be confirmed');
        }

        try {
            $this->bookingHistoryModel->update($bookingId, ['status' => 'confirmed']);
            return redirect()->back()->with('success', 'Booking confirmed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to confirm booking: ' . $e->getMessage());
        }
    }

    /**
     * Check-in booking (change from confirmed to checked_in)
     */
    public function checkIn($bookingId)
    {
        $staffId = session()->get('staff_id');
        
        // Get hotel_id from staff record instead of session
        $db = \Config\Database::connect();
        $staffQuery = $db->table('staff')->where('staff_id', $staffId)->get();
        $staffData = $staffQuery->getRowArray();
        
        $hotelId = $staffData['hotel_id'] ?? null;

        $booking = $this->bookingHistoryModel->find($bookingId);
        if (!$booking) {
            return redirect()->to('/staff/bookings')->with('error', 'Booking not found');
        }

        // Check if this booking belongs to staff's hotel
        if ($booking['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/bookings')->with('error', 'Access denied');
        }

        if ($booking['status'] !== 'confirmed') {
            return redirect()->back()->with('error', 'Only confirmed bookings can be checked in');
        }

        try {
            $this->bookingHistoryModel->update($bookingId, [
                'status' => 'checked_in',
                'checked_in_date' => date('Y-m-d H:i:s')
            ]);
            return redirect()->back()->with('success', 'Guest checked in successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to check in guest: ' . $e->getMessage());
        }
    }

    /**
     * Complete booking (change from checked_in to completed)
     */
    public function complete($bookingId)
    {
        $staffId = session()->get('staff_id');
        
        // Get hotel_id from staff record instead of session
        $db = \Config\Database::connect();
        $staffQuery = $db->table('staff')->where('staff_id', $staffId)->get();
        $staffData = $staffQuery->getRowArray();
        
        $hotelId = $staffData['hotel_id'] ?? null;

        $booking = $this->bookingHistoryModel->find($bookingId);
        if (!$booking) {
            return redirect()->to('/staff/bookings')->with('error', 'Booking not found');
        }

        // Check if this booking belongs to staff's hotel
        if ($booking['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/bookings')->with('error', 'Access denied');
        }

        if ($booking['status'] !== 'checked_in') {
            return redirect()->back()->with('error', 'Only checked-in bookings can be completed');
        }

        try {
            $this->bookingHistoryModel->update($bookingId, [
                'status' => 'completed',
                'checked_out_date' => date('Y-m-d H:i:s')
            ]);
            return redirect()->back()->with('success', 'Booking completed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to complete booking: ' . $e->getMessage());
        }
    }

    /**
     * Delete booking
     */
    public function delete($bookingId)
    {
        $staffId = session()->get('staff_id');
        
        // Get hotel_id from staff record instead of session
        $db = \Config\Database::connect();
        $staffQuery = $db->table('staff')->where('staff_id', $staffId)->get();
        $staffData = $staffQuery->getRowArray();
        
        $hotelId = $staffData['hotel_id'] ?? null;

        $booking = $this->bookingHistoryModel->find($bookingId);
        if (!$booking) {
            return redirect()->to('/staff/bookings')->with('error', 'Booking not found');
        }

        // Check if this booking belongs to staff's hotel
        if ($booking['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/bookings')->with('error', 'Access denied');
        }

        // Check if booking can be deleted
        if ($booking['status'] == 'checked_in') {
            return redirect()->back()->with('error', 'Cannot delete checked-in bookings');
        }

        if ($booking['status'] == 'completed') {
            return redirect()->back()->with('error', 'Cannot delete completed bookings');
        }

        try {
            $this->bookingHistoryModel->delete($bookingId);
            return redirect()->to('/staff/bookings')->with('success', 'Booking deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }

    /**
     * Cancel booking
     */
    public function cancel($bookingId)
    {
        $staffId = session()->get('staff_id');
        
        // Get hotel_id from staff record instead of session
        $db = \Config\Database::connect();
        $staffQuery = $db->table('staff')->where('staff_id', $staffId)->get();
        $staffData = $staffQuery->getRowArray();
        
        $hotelId = $staffData['hotel_id'] ?? null;

        $booking = $this->bookingHistoryModel->find($bookingId);
        if (!$booking) {
            return redirect()->to('/staff/bookings')->with('error', 'Booking not found');
        }

        // Check if this booking belongs to staff's hotel
        if ($booking['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/bookings')->with('error', 'Access denied');
        }

        if ($booking['status'] == 'cancelled') {
            return redirect()->back()->with('error', 'Booking is already cancelled');
        }

        if ($booking['status'] == 'completed') {
            return redirect()->back()->with('error', 'Cannot cancel completed bookings');
        }

        try {
            $this->bookingHistoryModel->update($bookingId, ['status' => 'cancelled']);
            return redirect()->back()->with('success', 'Booking cancelled successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    /**
     * Get available rooms via AJAX
     */
    public function getAvailableRooms()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $staffId = session()->get('staff_id');
        
        // Get hotel_id from staff record instead of session
        $db = \Config\Database::connect();
        $staffQuery = $db->table('staff')->where('staff_id', $staffId)->get();
        $staffData = $staffQuery->getRowArray();
        
        $hotelId = $staffData['hotel_id'] ?? null;

        $checkIn = $this->request->getPost('check_in');
        $checkOut = $this->request->getPost('check_out');
        $roomTypeId = $this->request->getPost('room_type_id');

        try {
            // Get available rooms using existing method
            $rooms = $this->roomModel->getAvailableRooms($hotelId, $checkIn, $checkOut, $roomTypeId);

            // Filter rooms based on availability for the selected dates if dates provided
            if ($checkIn && $checkOut) {
                $filteredRooms = [];
                foreach ($rooms as $room) {
                    $isAvailable = $this->bookingHistoryModel->checkRoomAvailability($room['room_id'], $checkIn, $checkOut);
                    if ($isAvailable) {
                        $filteredRooms[] = $room;
                    }
                }
                $rooms = $filteredRooms;
            }

            return $this->response->setJSON([
                'success' => true,
                'rooms' => $rooms
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error loading available rooms: ' . $e->getMessage()
            ]);
        }
    }
}