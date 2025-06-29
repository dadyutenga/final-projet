<?php

namespace App\Controllers;

use App\Models\HotelModel;
use App\Models\RoomModel;
use App\Models\RoomTypeModel;
use App\Models\BookingHistoryModel;

class CustomerBookingController extends BaseController
{
    protected $hotelModel;
    protected $roomModel;
    protected $roomTypeModel;
    protected $bookingHistoryModel;

    public function __construct()
    {
        $this->hotelModel = new HotelModel();
        $this->roomModel = new RoomModel();
        $this->roomTypeModel = new RoomTypeModel();
        $this->bookingHistoryModel = new BookingHistoryModel();
    }

    /**
     * Get hotels for booking form
     */
    public function getHotels()
    {
        try {
            $hotels = $this->hotelModel->findAll();
            return $this->response->setJSON($hotels);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching hotels: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading hotels'
            ]);
        }
    }

    /**
     * Get available rooms for a hotel and date range
     */
    public function getAvailableRooms()
    {
        $hotelId = $this->request->getPost('hotel_id');
        $checkIn = $this->request->getPost('check_in');
        $checkOut = $this->request->getPost('check_out');
        $guests = $this->request->getPost('guests');

        if (!$hotelId || !$checkIn || !$checkOut) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hotel ID, check-in and check-out dates are required'
            ]);
        }

        try {
            // Get available rooms with room types
            $availableRooms = $this->roomModel->select('rooms.*, room_types.type_name, room_types.capacity, room_types.base_price')
                                             ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                                             ->where('rooms.hotel_id', $hotelId)
                                             ->where('rooms.status', 'available')
                                             ->findAll();

            // Filter rooms based on availability for the selected dates
            $filteredRooms = [];
            foreach ($availableRooms as $room) {
                // Check room availability using booking history
                $isAvailable = $this->bookingHistoryModel->checkRoomAvailability($room['room_id'], $checkIn, $checkOut);
                
                // Check if room capacity meets guest requirements
                $guestCount = is_numeric($guests) ? (int)$guests : 5;
                if ($isAvailable && $room['capacity'] >= $guestCount) {
                    $filteredRooms[] = $room;
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'rooms' => $filteredRooms
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching available rooms: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading available rooms'
            ]);
        }
    }

    /**
     * Get room details
     */
    public function getRoomDetails($roomId)
    {
        try {
            $room = $this->roomModel->select('rooms.*, room_types.type_name, room_types.capacity, room_types.base_price, room_types.description')
                                   ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                                   ->where('rooms.room_id', $roomId)
                                   ->first();
            
            if (!$room) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Room not found'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'room' => $room
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching room details: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading room details'
            ]);
        }
    }

    /**
     * Calculate booking price
     */
    public function calculatePrice()
    {
        $roomId = $this->request->getPost('room_id');
        $checkIn = $this->request->getPost('check_in');
        $checkOut = $this->request->getPost('check_out');

        if (!$roomId || !$checkIn || !$checkOut) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Room ID and dates are required'
            ]);
        }

        try {
            $room = $this->roomModel->select('rooms.*, room_types.base_price')
                                   ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                                   ->where('rooms.room_id', $roomId)
                                   ->first();
            
            if (!$room) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Room not found'
                ]);
            }

            // Calculate number of nights
            $nights = $this->bookingHistoryModel->calculateTotalNights($checkIn, $checkOut);
            $totalPrice = $room['base_price'] * $nights;

            return $this->response->setJSON([
                'success' => true,
                'nights' => $nights,
                'price_per_night' => $room['base_price'],
                'total_price' => $totalPrice
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error calculating price: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error calculating booking price'
            ]);
        }
    }

    /**
     * Process booking - Create booking history entry only
     */
    public function processBooking()
    {
        $rules = [
            'hotel_id' => 'required|is_natural_no_zero',
            'room_id' => 'required|is_natural_no_zero',
            'guest_name' => 'required|min_length[2]|max_length[100]',
            'guest_phone' => 'required|min_length[10]|max_length[20]',
            'guest_email' => 'permit_empty|valid_email|max_length[100]',
            'check_in_date' => 'required|valid_date',
            'check_out_date' => 'required|valid_date',
            'guests' => 'required|is_natural_no_zero|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = $this->request->getPost();

        try {
            // Validate dates
            $checkInDate = new \DateTime($data['check_in_date']);
            $checkOutDate = new \DateTime($data['check_out_date']);
            $today = new \DateTime(date('Y-m-d'));

            if ($checkInDate < $today) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Check-in date cannot be in the past'
                ]);
            }

            if ($checkOutDate <= $checkInDate) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Check-out date must be after check-in date'
                ]);
            }

            // Check room availability again
            $isAvailable = $this->bookingHistoryModel->checkRoomAvailability(
                $data['room_id'], 
                $data['check_in_date'], 
                $data['check_out_date']
            );

            if (!$isAvailable) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sorry, this room is no longer available for the selected dates'
                ]);
            }

            // Get room details for pricing
            $room = $this->roomModel->select('rooms.*, room_types.base_price, room_types.capacity')
                                   ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                                   ->where('rooms.room_id', $data['room_id'])
                                   ->first();

            if (!$room) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Room not found'
                ]);
            }

            // Check if room capacity is sufficient
            if ($room['capacity'] < $data['guests']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Room capacity is insufficient for the number of guests'
                ]);
            }

            // Calculate total price
            $nights = $this->bookingHistoryModel->calculateTotalNights($data['check_in_date'], $data['check_out_date']);
            $totalPrice = $room['base_price'] * $nights;

            // Generate booking ticket number
            $bookingTicket = $this->bookingHistoryModel->generateTicketNumber($data['hotel_id']);

            // Create booking history entry
            $bookingData = [
                'booking_ticket_no' => $bookingTicket,
                'room_id' => $data['room_id'],
                'hotel_id' => $data['hotel_id'],
                'person_full_name' => trim($data['guest_name']),
                'person_phone' => trim($data['guest_phone']),
                'check_in_date' => $data['check_in_date'],
                'check_out_date' => $data['check_out_date'],
                'total_price' => $totalPrice,
                'guests_count' => $data['guests'],
                'guest_email' => !empty($data['guest_email']) ? trim($data['guest_email']) : null,
                'status' => 'pending' // CHANGED FROM 'confirmed' TO 'pending'
            ];

            $bookingId = $this->bookingHistoryModel->insert($bookingData);

            if ($bookingId) {
                // Get hotel name for confirmation
                $hotel = $this->hotelModel->find($data['hotel_id']);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Booking request submitted successfully! Your booking is pending confirmation.',
                    'booking_ticket' => $bookingTicket,
                    'booking_id' => $bookingId,
                    'hotel_name' => $hotel['name'] ?? 'Unknown Hotel',
                    'room_number' => $room['room_number'],
                    'guest_name' => $data['guest_name'],
                    'check_in' => $data['check_in_date'],
                    'check_out' => $data['check_out_date'],
                    'total_price' => $totalPrice,
                    'nights' => $nights,
                    'guests' => $data['guests'],
                    'status' => 'pending' // ADD STATUS TO RESPONSE
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create booking. Please try again.'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Booking Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while processing your booking. Please try again.'
            ]);
        }
    }

    /**
     * Update booking details (before check-in)
     */
    public function updateBooking()
    {
        $ticketNo = $this->request->getPost('ticket_no');
        $phone = $this->request->getPost('phone');
        
        if (!$ticketNo || !$phone) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking ticket number and phone number are required'
            ]);
        }

        try {
            // Find booking by ticket and verify phone
            $booking = $this->bookingHistoryModel->getBookingByTicket($ticketNo);
            
            if (!$booking || $booking['person_phone'] !== $phone) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking not found or phone number does not match'
                ]);
            }

            // Check if booking can be modified - UPDATED TO INCLUDE PENDING
            if (!in_array($booking['status'], ['pending', 'confirmed'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Only pending or confirmed bookings can be modified'
                ]);
            }

            $today = date('Y-m-d');
            if ($booking['check_in_date'] <= $today) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot modify booking on or after check-in date'
                ]);
            }

            // Get update data
            $updateData = [];
            $allowedFields = [
                'person_full_name' => 'guest_name',
                'guest_email' => 'guest_email',
                'guests_count' => 'guests'
            ];
            
            foreach ($allowedFields as $dbField => $postField) {
                $value = $this->request->getPost($postField);
                if ($value !== null && $value !== '') {
                    $updateData[$dbField] = trim($value);
                }
            }

            if (empty($updateData)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No valid fields to update'
                ]);
            }

            // Validate guest count against room capacity if provided
            if (isset($updateData['guests_count'])) {
                $room = $this->roomModel->select('rooms.*, room_types.capacity')
                                       ->join('room_types', 'room_types.room_type_id = rooms.room_type_id')
                                       ->where('rooms.room_id', $booking['room_id'])
                                       ->first();

                if ($room && $room['capacity'] < $updateData['guests_count']) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Room capacity is insufficient for the number of guests'
                    ]);
                }
            }

            // Update booking
            $updated = $this->bookingHistoryModel->update($booking['history_id'], $updateData);
            
            if ($updated) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Booking updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update booking. Please try again.'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error updating booking: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating booking'
            ]);
        }
    }

    /**
     * Check booking status by ticket number
     */
    public function checkBooking()
    {
        $ticketNo = $this->request->getPost('ticket_no');
        
        if (!$ticketNo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking ticket number is required'
            ]);
        }

        try {
            $booking = $this->bookingHistoryModel->getBookingWithDetails($ticketNo);
            
            if (!$booking) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking not found'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error checking booking: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error checking booking status'
            ]);
        }
    }

    /**
     * Cancel booking
     */
    public function cancelBooking()
    {
        $ticketNo = $this->request->getPost('ticket_no');
        $phone = $this->request->getPost('phone');
        
        if (!$ticketNo || !$phone) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking ticket number and phone number are required'
            ]);
        }

        try {
            // Find booking by ticket and verify phone
            $booking = $this->bookingHistoryModel->getBookingByTicket($ticketNo);
            
            if (!$booking || $booking['person_phone'] !== $phone) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking not found or phone number does not match'
                ]);
            }

            // Check if booking can be cancelled - UPDATED TO INCLUDE PENDING
            if ($booking['status'] === 'cancelled') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking is already cancelled'
                ]);
            }

            if ($booking['status'] === 'completed') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot cancel completed booking'
                ]);
            }

            if ($booking['status'] === 'checked_in') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot cancel checked-in booking'
                ]);
            }

            // Check cancellation policy (can't cancel on check-in day for confirmed bookings)
            $today = date('Y-m-d');
            if ($booking['status'] === 'confirmed' && $booking['check_in_date'] <= $today) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot cancel confirmed booking on or after check-in date'
                ]);
            }

            // Cancel the booking
            $cancelled = $this->bookingHistoryModel->update($booking['history_id'], [
                'status' => 'cancelled'
            ]);
            
            if ($cancelled) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Booking cancelled successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to cancel booking. Please try again.'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error cancelling booking: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error cancelling booking'
            ]);
        }
    }

    /**
     * Get booking history by phone
     */
    public function getBookingHistory()
    {
        $phone = $this->request->getPost('phone');
        
        if (!$phone) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Phone number is required'
            ]);
        }

        try {
            $history = $this->bookingHistoryModel->getHistoryByPhone($phone, 20);
            
            return $this->response->setJSON([
                'success' => true,
                'history' => $history
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching booking history: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading booking history'
            ]);
        }
    }

    /**
     * Get booking details by ticket number (for public access)
     */
    public function getBookingDetails()
    {
        $ticketNo = $this->request->getPost('ticket_no');
        
        if (!$ticketNo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking ticket number is required'
            ]);
        }

        try {
            $booking = $this->bookingHistoryModel->getBookingWithDetails($ticketNo);
            
            if (!$booking) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking not found'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching booking details: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading booking details'
            ]);
        }
    }

    /**
     * Get hotel info for booking confirmation
     */
    public function getHotelInfo($hotelId)
    {
        try {
            $hotel = $this->hotelModel->find($hotelId);
            
            if (!$hotel) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Hotel not found'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'hotel' => $hotel
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching hotel info: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading hotel information'
            ]);
        }
    }

    /**
     * Validate booking data (utility method)
     */
    private function validateBookingData($data)
    {
        $errors = [];

        // Validate dates
        try {
            $checkIn = new \DateTime($data['check_in_date']);
            $checkOut = new \DateTime($data['check_out_date']);
            $today = new \DateTime(date('Y-m-d'));

            if ($checkIn < $today) {
                $errors[] = 'Check-in date cannot be in the past';
            }

            if ($checkOut <= $checkIn) {
                $errors[] = 'Check-out date must be after check-in date';
            }
        } catch (\Exception $e) {
            $errors[] = 'Invalid date format';
        }

        // Validate guest count
        if (!is_numeric($data['guests']) || $data['guests'] < 1) {
            $errors[] = 'Guest count must be at least 1';
        }

        // Validate guest name
        if (empty(trim($data['guest_name'])) || strlen(trim($data['guest_name'])) < 2) {
            $errors[] = 'Guest name must be at least 2 characters';
        }

        // Validate phone number
        if (empty(trim($data['guest_phone'])) || strlen(trim($data['guest_phone'])) < 10) {
            $errors[] = 'Phone number must be at least 10 characters';
        }

        return $errors;
    }

    /**
     * Get booking statistics for admin (optional)
     */
    public function getBookingStats()
    {
        try {
            $stats = $this->bookingHistoryModel->getBookingStatistics();
            
            return $this->response->setJSON([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching booking stats: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading booking statistics'
            ]);
        }
    }
}