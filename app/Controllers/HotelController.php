<?php

namespace App\Controllers;

use App\Models\HotelModel;
use App\Models\ManagerModel;
use CodeIgniter\RESTful\ResourceController;

class HotelController extends ResourceController
{
    protected $hotelModel;
    protected $managerModel;

    public function __construct()
    {
        $this->hotelModel = new HotelModel();
        $this->managerModel = new ManagerModel();
    }

    public function index()
    {
        $searchTerm = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        // Set up pagination
        $this->hotelModel->builder()->select('hotels.*, managers.full_name as manager_name')
            ->join('managers', 'managers.manager_id = hotels.manager_id', 'left');
        
        // Apply search if term exists
        if (!empty($searchTerm)) {
            $this->hotelModel->builder()
                ->groupStart()
                ->like('hotels.name', $searchTerm)
                ->orLike('hotels.city', $searchTerm)
                ->orLike('hotels.country', $searchTerm)
                ->groupEnd();
        }

        // Get paginated results
        $hotels = $this->hotelModel->paginate($perPage);
        
        // Get pager
        $pager = $this->hotelModel->pager;

        return view('admin/hotel/index', [
            'hotels' => $hotels,
            'pager' => $pager,
            'searchTerm' => $searchTerm
        ]);
    }

    public function new()
    {
        // Get all available managers
        $managers = $this->managerModel->findAll();

        return view('admin/hotel/create', [
            'managers' => $managers
        ]);
    }

    public function create()
    {
        $rules = [
            'name' => 'required|max_length[100]',
            'address' => 'required|max_length[255]',
            'city' => 'required|max_length[50]',
            'country' => 'required|max_length[50]',
            'phone' => 'permit_empty|max_length[20]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'manager_id' => 'required|is_natural_no_zero|is_not_unique[managers.manager_id]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Handle logo upload
        $logo = $this->request->getFile('hotel_logo');
        $logoPath = '';

        if ($logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move(FCPATH . 'uploads/hotels', $newName);
            $logoPath = 'uploads/hotels/' . $newName;
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'country' => $this->request->getPost('country'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'manager_id' => $this->request->getPost('manager_id'),
            'hotel_logo' => $logoPath
        ];

        try {
            if ($this->hotelModel->insert($data)) {
                return redirect()->to(base_url('admin/hotels'))
                               ->with('success', 'Hotel created successfully');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to create hotel: ' . implode(', ', $this->hotelModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', '[Hotel Creation] ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'An error occurred while creating the hotel');
        }
    }
}
