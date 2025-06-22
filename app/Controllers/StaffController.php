<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\StaffModel;
use App\Models\ManagerModel;  // To verify and get manager details
use App\Models\HotelModel;   // To ensure hotel linkage

class StaffController extends Controller
{
    protected $staffModel;
    protected $managerModel;
    protected $hotelModel;

    public function __construct()
    {
        $this->staffModel = new StaffModel();
        $this->managerModel = new ManagerModel();
        $this->hotelModel = new HotelModel();
        
        // Ensure the user is a logged-in manager
        if (!session()->get('manager_id')) {
            return redirect()->to('/manager/login');  // Redirect to manager login if not authenticated
        }
    }

    public function index()
    {
        $staff = $this->staffModel->getStaffByManager(session()->get('manager_id'));
        return view('managers/staff/index', ['staff' => $staff]);
    }

    public function create()
    {
        return view('managers/staff/create');  // Load the create form
    }

    public function store()
    {
        // Get the current manager's ID from session
        $managerId = session()->get('manager_id');
        
        // Fetch the manager's hotel(s) – assuming a manager is linked to one hotel
        $manager = $this->managerModel->find($managerId);
        if (!$manager || empty($manager['hotel_id'])) {  // Assuming hotel_id is in manager data; adjust if needed
            return redirect()->back()->with('error', 'No hotel associated with your account.');
        }
        
        $hotelId = $manager['hotel_id'];  // Link staff to this hotel
        
        $data = [
            'hotel_id'     => $hotelId,  // Link to the manager's hotel
            'manager_id'   => $managerId,  // Link to the current manager
            'full_name'    => $this->request->getPost('full_name'),
            'role'         => $this->request->getPost('role'),
            'phone'        => $this->request->getPost('phone'),
            'email'        => $this->request->getPost('email'),
            'hire_date'    => $this->request->getPost('hire_date'),
            'username'     => $this->request->getPost('username'),
            'password'     => $this->request->getPost('password'),  // This will be hashed in the model
        ];
        
        if ($this->staffModel->insert($data)) {
            return redirect()->to('/manager/staff')->with('success', 'Staff member created successfully.');
        } else {
            return redirect()->back()->with('errors', $this->staffModel->errors());
        }
    }

    public function show($id = null)
    {
        $staff = $this->staffModel->find($id);
        
        if (!$staff || $staff['manager_id'] != session()->get('manager_id')) {
            return redirect()->to('/manager/staff')->with('error', 'Staff member not found or not authorized.');
        }
        
        return view('managers/staff/show', ['staff' => $staff]);  // Assuming you have a show.php view
    }

    public function edit($id = null)
    {
        $staff = $this->staffModel->find($id);
        
        if (!$staff || $staff['manager_id'] != session()->get('manager_id')) {
            return redirect()->to('/manager/staff')->with('error', 'Staff member not found or not authorized.');
        }
        
        return view('managers/staff/edit', ['staff' => $staff]);  // Assuming you have an edit.php view
    }

    public function update($id = null)
    {
        $managerId = session()->get('manager_id');
        $staff = $this->staffModel->find($id);
        
        if (!$staff || $staff['manager_id'] != $managerId) {
            return redirect()->to('/manager/staff')->with('error', 'Staff member not found or not authorized.');
        }
        
        $data = [
            'full_name'    => $this->request->getPost('full_name'),
            'role'         => $this->request->getPost('role'),
            'phone'        => $this->request->getPost('phone'),
            'email'        => $this->request->getPost('email'),
            'hire_date'    => $this->request->getPost('hire_date'),
            // Do not allow changing username or password here; handle separately if needed
        ];
        
        // If password is being updated
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');  // Model will hash this
        }
        
        if ($this->staffModel->update($id, $data)) {
            return redirect()->to('/manager/staff')->with('success', 'Staff member updated successfully.');
        } else {
            return redirect()->back()->with('errors', $this->staffModel->errors());
        }
    }

    public function destroy($id = null)
    {
        $staff = $this->staffModel->find($id);
        
        if (!$staff || $staff['manager_id'] != session()->get('manager_id')) {
            return redirect()->to('/manager/staff')->with('error', 'Staff member not found or not authorized.');
        }
        
        $this->staffModel->delete($id);
        return redirect()->to('/manager/staff')->with('success', 'Staff member deleted successfully.');
    }
}
