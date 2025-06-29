<?php

namespace App\Controllers;

use App\Models\BookingHistoryModel;
use App\Models\RoomModel;
use App\Models\HotelModel;
use App\Models\RoomTypeModel;
use CodeIgniter\Controller;

class BookingController extends BaseController
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
        
        // Allow pending status for staff bookings
        $this->bookingHistoryModel->addPendingStatus();
    }

    /**
     * Display all bookings for staff
     */
    public function index()
    {
        // Check if staff is logged in
        if (!session()->has('staff_logged_in')) {
            return redirect()->to('/staff/login');
        }

        $staffId = session()->get('staff_id');
        $hotelId = session()->get('staff_hotel_id');

        // Get filter parameters
        $status = $this->request->getGet('status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $search = $this->request->getGet('search');

        // Get bookings from booking_history directly
        $bookings = $this->bookingHistoryModel->getBookingsByHotel($hotelId, $status, $dateFrom, $dateTo, $search, 20, 0);

        // Get statistics
        $stats = $this->bookingHistoryModel->getBookingStatistics($hotelId, $dateFrom, $dateTo);

        // Add pending to stats if not exist
        if (!isset($stats['pending'])) {
            $stats['pending'] = ['count' => 0];
        }

        $data = [
            'title' => 'Manage Bookings',
            'reservations' => $bookings, // Keep the same variable name for the view
            'stats' => $stats,
            'current_status' => $status,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'search' => $search
        ];

        return view('staff/bookings/index', $data);
    }

    /**
     * Show create booking form
     */
    public function create()
    {
        // Check if staff is logged in
        if (!session()->has('staff_logged_in')) {
            return redirect()->to('/staff/login');
        }

        $hotelId = session()->get('staff_hotel_id');

        // Get available rooms
        $availableRooms = $this->roomModel->getAvailableRooms($hotelId);
        
        // Get room types for the hotel
        $roomTypes = $this->roomTypeModel->getRoomTypesByHotel($hotelId);

        // Get hotel info
        $hotel = $this->hotelModel->find($hotelId);

        $data = [
            'title' => 'Create New Booking',
            'availableRooms' => $availableRooms,
            'roomTypes' => $roomTypes,
            'hotel' => $hotel
        ];

        return view('staff/bookings/create', $data);
    }

    /**
     * Store new booking (similar to customer booking but with staff control)
     */
    public function store()
    {
        // Check if staff is logged in
        if (!session()->has('staff_logged_in')) {
            return redirect()->to('/staff/login');
        }

        $staffId = session()->get('staff_id');
        $hotelId = session()->get('staff_hotel_id');

        // Validation rules (same as customer booking)
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
            // Validate dates (same logic as customer booking)
            $checkInDate = new \DateTime($data['check_in_date']);
            $checkOutDate = new \DateTime($data['check_out_date']);
            $today = new \DateTime(date('Y-m-d'));

            if ($checkInDate < $today) {
                return redirect()->back()->withInput()->with('error', 'Check-in date cannot be in the past');
            }

            if ($checkOutDate <= $checkInDate) {
                return redirect()->back()->withInput()->with('error', 'Check-out date must be after check-in date');
            }

            // Check room availability using booking history
            $isAvailable = $this->bookingHistoryModel->checkRoomAvailability(
                $data['room_id'], 
                $data['check_in_date'], 
                $data['check_out_date']
            );

            if (!$isAvailable) {
                return redirect()->back()->withInput()->with('error', 'Selected room is not available for the chosen dates');
            }

            // Get room details for validation
            $room = $this->roomModel->select('rooms.*, room_types.capacity')
                                   ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                                   ->where('rooms.room_id', $data['room_id'])
                                   ->first();

            if (!$room) {
                return redirect()->back()->withInput()->with('error', 'Room not found');
            }

            // Check if room capacity is sufficient
            if ($room['capacity'] < $data['guests_count']) {
                return redirect()->back()->withInput()->with('error', 'Room capacity is insufficient for the number of guests');
            }

            // Generate booking ticket number
            $bookingTicket = $this->bookingHistoryModel->generateTicketNumber($hotelId);

            // Create booking history entry (same as customer booking)
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

            // Update room status to occupied if check-in is today
            if ($data['check_in_date'] == date('Y-m-d')) {
                $this->roomModel->updateRoomStatus($data['room_id'], 'occupied');
            }

            return redirect()->to('/staff/bookings')->with('success', 'Booking created successfully! Ticket: ' . $bookingTicket);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    /**
     * Get available rooms via AJAX (same logic as customer booking)
     */
    public function getAvailableRooms()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $hotelId = session()->get('staff_hotel_id');
        $checkIn = $this->request->getPost('check_in');
        $checkOut = $this->request->getPost('check_out');
        $roomTypeId = $this->request->getPost('room_type_id');

        try {
            // Get available rooms with room types
            $availableRooms = $this->roomModel->select('rooms.*, room_types.type_name, room_types.capacity, room_types.base_price')
                                             ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                                             ->where('rooms.hotel_id', $hotelId)
                                             ->where('rooms.status', 'available')
                                             ->orderBy('rooms.room_number', 'ASC');

            if ($roomTypeId) {
                $availableRooms->where('rooms.room_type_id', $roomTypeId);
            }

            $rooms = $availableRooms->findAll();

            // Filter rooms based on availability for the selected dates
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

    /**
     * View booking details
     */
    public function view($bookingId)
    {
        // Check if staff is logged in
        if (!session()->has('staff_logged_in')) {
            return redirect()->to('/staff/login');
        }

        $booking = $this->bookingHistoryModel->getBookingWithDetailsById($bookingId);

        if (!$booking) {
            return redirect()->to('/staff/bookings')->with('error', 'Booking not found');
        }

        // Check if this booking belongs to staff's hotel
        $hotelId = session()->get('staff_hotel_id');
        if ($booking['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/bookings')->with('error', 'Access denied');
        }

        $data = [
            'title' => 'Booking Details',
            'reservation' => $booking // Keep same variable name for view compatibility
        ];

        return view('staff/bookings/view', $data);
    }

    /**
     * Update booking status
     */
    public function updateStatus($bookingId)
    {
        // Check if staff is logged in
        if (!session()->has('staff_logged_in')) {
            return redirect()->to('/staff/login');
        }

        $status = $this->request->getPost('status');
        $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Invalid status');
        }

        $booking = $this->bookingHistoryModel->getBookingWithDetailsById($bookingId);
        if (!$booking) {
            return redirect()->to('/staff/bookings')->with('error', 'Booking not found');
        }

        // Check if this booking belongs to staff's hotel
        $hotelId = session()->get('staff_hotel_id');
        if ($booking['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/bookings')->with('error', 'Access denied');
        }

        try {
            // Update booking status
            $this->bookingHistoryModel->updateBookingStatus($bookingId, $status);
            
            // Update room status based on booking status
            if ($status == 'confirmed' && $booking['check_in_date'] == date('Y-m-d')) {
                $this->roomModel->updateRoomStatus($booking['room_id'], 'occupied');
            } elseif ($status == 'cancelled' || $status == 'completed') {
                $this->roomModel->updateRoomStatus($booking['room_id'], 'available');
            }

            return redirect()->back()->with('success', 'Booking status updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update booking status: ' . $e->getMessage());
        }
    }

    /**
     * Delete booking (cancel and remove)
     */
    public function delete($bookingId)
    {
        // Check if staff is logged in
        if (!session()->has('staff_logged_in')) {
            return redirect()->to('/staff/login');
        }

        $booking = $this->bookingHistoryModel->find($bookingId);
        if (!$booking) {
            return redirect()->to('/staff/bookings')->with('error', 'Booking not found');
        }

        // Check if this booking belongs to staff's hotel
        $hotelId = session()->get('staff_hotel_id');
        if ($booking['hotel_id'] != $hotelId) {
            return redirect()->to('/staff/bookings')->with('error', 'Access denied');
        }

        // Check if booking can be deleted (only pending or future bookings)
        if ($booking['status'] == 'completed') {
            return redirect()->back()->with('error', 'Cannot delete completed bookings');
        }

        if ($booking['check_in_date'] <= date('Y-m-d') && $booking['status'] == 'confirmed') {
            return redirect()->back()->with('error', 'Cannot delete current or past confirmed bookings');
        }

        try {
            // Cancel the booking first, then delete
            $this->bookingHistoryModel->update($bookingId, ['status' => 'cancelled']);
            
            // Make room available if it was reserved
            $this->roomModel->updateRoomStatus($booking['room_id'], 'available');
            
            // Actually delete the record
            $this->bookingHistoryModel->delete($bookingId);

            return redirect()->to('/staff/bookings')->with('success', 'Booking deleted successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }

    /**
     * Search bookings by ticket number
     */
    public function searchByTicket()
    {
        $ticketNo = $this->request->getPost('ticket_number');
        
        if (empty($ticketNo)) {
            return redirect()->back()->with('error', 'Please enter a ticket number');
        }

        $booking = $this->bookingHistoryModel->getBookingByTicket($ticketNo);

        if (!$booking) {
            return redirect()->back()->with('error', 'No booking found with ticket number: ' . $ticketNo);
        }

        // Check if this booking belongs to staff's hotel
        $hotelId = session()->get('staff_hotel_id');
        if ($booking['hotel_id'] != $hotelId) {
            return redirect()->back()->with('error', 'Booking not found in your hotel');
        }

        return redirect()->to('/staff/bookings/view/' . $booking['history_id']);
    }

    /**
     * Get today's check-ins and check-outs
     */
    public function todayActivity()
    {
        // Check if staff is logged in
        if (!session()->has('staff_logged_in')) {
            return redirect()->to('/staff/login');
        }

        $hotelId = session()->get('staff_hotel_id');

        $checkIns = $this->bookingHistoryModel->getTodayCheckIns($hotelId);
        $checkOuts = $this->bookingHistoryModel->getTodayCheckOuts($hotelId);
        $currentGuests = $this->bookingHistoryModel->getCurrentGuests($hotelId);

        $data = [
            'title' => 'Today\'s Activity',
            'checkIns' => $checkIns,
            'checkOuts' => $checkOuts,
            'currentGuests' => $currentGuests
        ];

        return view('staff/bookings/today_activity', $data);
    }
}