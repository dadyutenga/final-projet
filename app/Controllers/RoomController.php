<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RoomModel;
use App\Models\RoomTypeModel;
use App\Models\HotelModel;

class RoomController extends Controller
{
    protected $roomModel;
    protected $roomTypeModel;
    protected $hotelModel;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
        $this->roomTypeModel = new RoomTypeModel();
        $this->hotelModel = new HotelModel();
        
        // Ensure the user is a logged-in manager
        if (!session()->get('manager_id')) {
            return redirect()->to('/manager/login');
        }
    }

    public function index()
    {
        $managerId = session()->get('manager_id');
        
        // Get the hotel managed by this manager
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/dashboard')->with('error', 'No hotel associated with your manager account.');
        }
        
        // Get search and filter parameters
        $searchTerm = $this->request->getGet('search');
        $statusFilter = $this->request->getGet('status');
        $typeFilter = $this->request->getGet('type');
        $floorFilter = $this->request->getGet('floor');
        
        // Get rooms with filters
        $rooms = $this->roomModel->searchRooms($searchTerm, $hotel['hotel_id'], $statusFilter, $typeFilter);
        
        // Get room types for filter dropdown
        $roomTypes = $this->roomTypeModel->getRoomTypesByHotel($hotel['hotel_id']);
        
        // Get unique floors for filter - Fixed this line
        $floors = $this->roomModel->getUniqueFloors($hotel['hotel_id']);
        
        // Get room statistics
        $roomStats = $this->roomModel->getRoomStatusStats($hotel['hotel_id']);
        
        return view('managers/rooms/index', [
            'rooms' => $rooms,
            'roomTypes' => $roomTypes,
            'floors' => $floors,
            'roomStats' => $roomStats,
            'hotel' => $hotel,
            'searchTerm' => $searchTerm,
            'statusFilter' => $statusFilter,
            'typeFilter' => $typeFilter,
            'floorFilter' => $floorFilter
        ]);
    }

    public function create()
    {
        $managerId = session()->get('manager_id');
        
        // Get the hotel managed by this manager
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/dashboard')->with('error', 'No hotel associated with your manager account.');
        }
        
        // Get room types for this hotel
        $roomTypes = $this->roomTypeModel->getRoomTypesByHotel($hotel['hotel_id']);
        
        if (empty($roomTypes)) {
            return redirect()->to('/manager/roomtypes/create')
                           ->with('error', 'Please create room types first before adding rooms.');
        }
        
        return view('managers/rooms/create', [
            'roomTypes' => $roomTypes,
            'hotel' => $hotel
        ]);
    }

    public function store()
    {
        $managerId = session()->get('manager_id');
        
        // Get the hotel managed by this manager
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->back()->with('error', 'No hotel associated with your manager account.');
        }
        
        $hotelId = $hotel['hotel_id'];
        
        // Validation rules
        $rules = [
            'room_type_id' => 'required|is_natural_no_zero',
            'room_number'  => 'required|max_length[10]',
            'floor'        => 'permit_empty|is_natural',
            'status'       => 'required|in_list[available,occupied,maintenance]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }
        
        // Check if room number already exists in this hotel
        $roomNumber = $this->request->getPost('room_number');
        if ($this->roomModel->roomNumberExists($hotelId, $roomNumber)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Room number already exists in this hotel.');
        }
        
        // Verify room type belongs to this hotel
        $roomType = $this->roomTypeModel->find($this->request->getPost('room_type_id'));
        if (!$roomType || $roomType['hotel_id'] != $hotelId) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Invalid room type selected.');
        }
        
        // Prepare data for insertion
        $data = [
            'hotel_id'     => $hotelId,
            'room_type_id' => $this->request->getPost('room_type_id'),
            'room_number'  => $roomNumber,
            'floor'        => $this->request->getPost('floor') ?: null,
            'status'       => $this->request->getPost('status') ?: 'available'
        ];
        
        try {
            if ($this->roomModel->save($data)) {
                return redirect()->to(base_url('manager/rooms'))
                               ->with('success', 'Room created successfully');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to create room: ' . implode(', ', $this->roomModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', '[Room Creation] ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'An error occurred while creating the room');
        }
    }

    public function show($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/dashboard')->with('error', 'No hotel associated with your manager account.');
        }
        
        $room = $this->roomModel->getRoomWithDetails($id);
        
        if (!$room || $room['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->to('/manager/rooms')->with('error', 'Room not found or not authorized.');
        }
        
        // Get current reservation if any
        $currentReservation = $this->roomModel->getRoomCurrentReservation($id);
        
        // Get reservation history
        $reservationHistory = $this->roomModel->getRoomReservationHistory($id, 10);
        
        return view('managers/rooms/show', [
            'room' => $room,
            'currentReservation' => $currentReservation,
            'reservationHistory' => $reservationHistory
        ]);
    }

    public function edit($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/dashboard')->with('error', 'No hotel associated with your manager account.');
        }
        
        $room = $this->roomModel->find($id);
        
        if (!$room || $room['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->to('/manager/rooms')->with('error', 'Room not found or not authorized.');
        }
        
        // Get room types for this hotel
        $roomTypes = $this->roomTypeModel->getRoomTypesByHotel($hotel['hotel_id']);
        
        return view('managers/rooms/edit', [
            'room' => $room,
            'roomTypes' => $roomTypes,
            'hotel' => $hotel
        ]);
    }

    public function update($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->back()->with('error', 'No hotel associated with your manager account.');
        }
        
        $room = $this->roomModel->find($id);
        
        if (!$room || $room['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->to('/manager/rooms')->with('error', 'Room not found or not authorized.');
        }
        
        // Validation rules
        $rules = [
            'room_type_id' => 'required|is_natural_no_zero',
            'room_number'  => 'required|max_length[10]',
            'floor'        => 'permit_empty|is_natural',
            'status'       => 'required|in_list[available,occupied,maintenance]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }
        
        // Check if room number already exists (excluding current room)
        $roomNumber = $this->request->getPost('room_number');
        if ($this->roomModel->roomNumberExists($hotel['hotel_id'], $roomNumber, $id)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Room number already exists in this hotel.');
        }
        
        // Verify room type belongs to this hotel
        $roomType = $this->roomTypeModel->find($this->request->getPost('room_type_id'));
        if (!$roomType || $roomType['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Invalid room type selected.');
        }
        
        $data = [
            'room_type_id' => $this->request->getPost('room_type_id'),
            'room_number'  => $roomNumber,
            'floor'        => $this->request->getPost('floor') ?: null,
            'status'       => $this->request->getPost('status')
        ];
        
        try {
            if ($this->roomModel->update($id, $data)) {
                return redirect()->to(base_url('manager/rooms'))
                               ->with('success', 'Room updated successfully');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to update room: ' . implode(', ', $this->roomModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', '[Room Update] ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'An error occurred while updating the room');
        }
    }

    public function destroy($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/rooms')->with('error', 'No hotel associated with your manager account.');
        }
        
        $room = $this->roomModel->find($id);
        
        if (!$room || $room['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->to('/manager/rooms')->with('error', 'Room not found or not authorized.');
        }
        
        try {
            if ($this->roomModel->delete($id)) {
                return redirect()->to('/manager/rooms')->with('success', 'Room deleted successfully.');
            } else {
                return redirect()->to('/manager/rooms')->with('error', 'Failed to delete room.');
            }
        } catch (\Exception $e) {
            log_message('error', '[Room Deletion] ' . $e->getMessage());
            return redirect()->to('/manager/rooms')->with('error', 'An error occurred while deleting the room.');
        }
    }

    public function bulkStatusUpdate()
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/rooms')->with('error', 'No hotel associated with your manager account.');
        }
        
        $roomIds = $this->request->getPost('room_ids');
        $status = $this->request->getPost('status');
        
        if (empty($roomIds) || !$status) {
            return redirect()->back()->with('error', 'Please select rooms and status.');
        }
        
        // Verify all rooms belong to this hotel
        $rooms = $this->roomModel->whereIn('room_id', $roomIds)
                                ->where('hotel_id', $hotel['hotel_id'])
                                ->findAll();
        
        if (count($rooms) != count($roomIds)) {
            return redirect()->back()->with('error', 'Some rooms are not authorized for update.');
        }
        
        try {
            if ($this->roomModel->bulkUpdateStatus($roomIds, $status)) {
                return redirect()->to('/manager/rooms')
                               ->with('success', count($roomIds) . ' rooms updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update room status.');
            }
        } catch (\Exception $e) {
            log_message('error', '[Bulk Room Update] ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating rooms.');
        }
    }
}