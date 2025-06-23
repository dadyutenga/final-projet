<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RoomTypeModel;
use App\Models\HotelModel;

class RoomTypeController extends Controller
{
    protected $roomTypeModel;
    protected $hotelModel;

    public function __construct()
    {
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
        
        $roomTypes = $this->roomTypeModel->getRoomTypesWithCounts($hotel['hotel_id']);
        
        return view('managers/roomtypes/index', [
            'roomTypes' => $roomTypes,
            'hotel' => $hotel
        ]);
    }

    public function create()
    {
        return view('managers/roomtypes/create');
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
            'type_name'   => 'required|max_length[100]',
            'description' => 'permit_empty',
            'base_price'  => 'required|decimal|greater_than[0]',
            'capacity'    => 'required|is_natural_no_zero',
            'amenities'   => 'permit_empty'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }
        
        // Prepare data for insertion
        $data = [
            'hotel_id'    => $hotelId,
            'type_name'   => $this->request->getPost('type_name'),
            'description' => $this->request->getPost('description'),
            'base_price'  => $this->request->getPost('base_price'),
            'capacity'    => $this->request->getPost('capacity')
        ];
        
        try {
            if ($this->roomTypeModel->save($data)) {
                return redirect()->to(base_url('manager/roomtypes'))
                               ->with('success', 'Room type created successfully');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to create room type: ' . implode(', ', $this->roomTypeModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', '[Room Type Creation] ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'An error occurred while creating the room type');
        }
    }

    public function show($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/dashboard')->with('error', 'No hotel associated with your manager account.');
        }
        
        $roomType = $this->roomTypeModel->find($id);
        
        if (!$roomType || $roomType['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->to('/manager/roomtypes')->with('error', 'Room type not found or not authorized.');
        }
        
        $stats = $this->roomTypeModel->getRoomTypeStatistics($id);
        
        return view('managers/roomtypes/show', [
            'roomType' => $roomType,
            'stats' => $stats
        ]);
    }

    public function edit($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/dashboard')->with('error', 'No hotel associated with your manager account.');
        }
        
        $roomType = $this->roomTypeModel->find($id);
        
        if (!$roomType || $roomType['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->to('/manager/roomtypes')->with('error', 'Room type not found or not authorized.');
        }
        
        return view('managers/roomtypes/edit', ['roomType' => $roomType]);
    }

    public function update($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->back()->with('error', 'No hotel associated with your manager account.');
        }
        
        $roomType = $this->roomTypeModel->find($id);
        
        if (!$roomType || $roomType['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->to('/manager/roomtypes')->with('error', 'Room type not found or not authorized.');
        }
        
        // Validation rules
        $rules = [
            'type_name'   => 'required|max_length[100]',
            'description' => 'permit_empty',
            'base_price'  => 'required|decimal|greater_than[0]',
            'capacity'    => 'required|is_natural_no_zero'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'type_name'   => $this->request->getPost('type_name'),
            'description' => $this->request->getPost('description'),
            'base_price'  => $this->request->getPost('base_price'),
            'capacity'    => $this->request->getPost('capacity')
        ];
        
        try {
            if ($this->roomTypeModel->update($id, $data)) {
                return redirect()->to(base_url('manager/roomtypes'))
                               ->with('success', 'Room type updated successfully');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to update room type: ' . implode(', ', $this->roomTypeModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', '[Room Type Update] ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'An error occurred while updating the room type');
        }
    }

    public function destroy($id = null)
    {
        $managerId = session()->get('manager_id');
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->to('/manager/roomtypes')->with('error', 'No hotel associated with your manager account.');
        }
        
        $roomType = $this->roomTypeModel->find($id);
        
        if (!$roomType || $roomType['hotel_id'] != $hotel['hotel_id']) {
            return redirect()->to('/manager/roomtypes')->with('error', 'Room type not found or not authorized.');
        }
        
        try {
            if ($this->roomTypeModel->delete($id)) {
                return redirect()->to('/manager/roomtypes')->with('success', 'Room type deleted successfully.');
            } else {
                return redirect()->to('/manager/roomtypes')->with('error', 'Failed to delete room type.');
            }
        } catch (\Exception $e) {
            log_message('error', '[Room Type Deletion] ' . $e->getMessage());
            return redirect()->to('/manager/roomtypes')->with('error', 'An error occurred while deleting the room type.');
        }
    }
}