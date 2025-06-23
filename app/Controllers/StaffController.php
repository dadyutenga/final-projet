<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\StaffModel;
use App\Models\ManagerModel;
use App\Models\HotelModel;

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
            return redirect()->to('/manager/login');
        }
    }

    public function index()
    {
        $staff = $this->staffModel->getStaffByManager(session()->get('manager_id'));
        return view('managers/staff/index', ['staff' => $staff]);
    }

    public function create()
    {
        return view('managers/staff/create');
    }

    public function store()
    {
        // Get the current manager's ID from session
        $managerId = session()->get('manager_id');
        
        // Get the hotel managed by this manager (hotels table has manager_id)
        $hotel = $this->hotelModel->where('manager_id', $managerId)->first();
        
        if (!$hotel) {
            return redirect()->back()->with('error', 'No hotel associated with your manager account. Please contact admin.');
        }
        
        $hotelId = $hotel['hotel_id'];
        
        // Validation rules (same pattern as your ManagerController)
        $rules = [
            'full_name' => 'required|max_length[100]',
            'role'      => 'required|max_length[50]',
            'phone'     => 'permit_empty|max_length[20]',
            'email'     => 'permit_empty|valid_email|max_length[100]',
            'hire_date' => 'permit_empty|valid_date',
            'username'  => 'required|min_length[3]|max_length[50]|is_unique[staff.username]',
            'password'  => 'required|min_length[8]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }
        
        // Prepare data for insertion (manually hash password like in ManagerController)
        $data = [
            'hotel_id'      => $hotelId,
            'manager_id'    => $managerId,
            'full_name'     => $this->request->getPost('full_name'),
            'role'          => $this->request->getPost('role'),
            'phone'         => $this->request->getPost('phone'),
            'email'         => $this->request->getPost('email'),
            'hire_date'     => $this->request->getPost('hire_date'),
            'username'      => $this->request->getPost('username'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT) // Manual hash like ManagerController
        ];
        
        try {
            // Attempt to save the data (using save() like ManagerController)
            if ($this->staffModel->save($data)) {
                return redirect()->to(base_url('manager/staff'))
                               ->with('success', 'Staff member created successfully');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to create staff member: ' . implode(', ', $this->staffModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', '[Staff Creation] ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'An error occurred while creating the staff member');
        }
    }

    public function show($id = null)
    {
        $staff = $this->staffModel->find($id);
        
        if (!$staff || $staff['manager_id'] != session()->get('manager_id')) {
            return redirect()->to('/manager/staff')->with('error', 'Staff member not found or not authorized.');
        }
        
        return view('managers/staff/show', ['staff' => $staff]);
    }

    public function edit($id = null)
    {
        $staff = $this->staffModel->find($id);
        
        if (!$staff || $staff['manager_id'] != session()->get('manager_id')) {
            return redirect()->to('/manager/staff')->with('error', 'Staff member not found or not authorized.');
        }
        
        return view('managers/staff/edit', ['staff' => $staff]);
    }

    public function update($id = null)
    {
        $managerId = session()->get('manager_id');
        $staff = $this->staffModel->find($id);
        
        if (!$staff || $staff['manager_id'] != $managerId) {
            return redirect()->to('/manager/staff')->with('error', 'Staff member not found or not authorized.');
        }
        
        // Validation rules for update
        $rules = [
            'full_name' => 'required|max_length[100]',
            'role'      => 'required|max_length[50]',
            'phone'     => 'permit_empty|max_length[20]',
            'email'     => 'permit_empty|valid_email|max_length[100]',
            'hire_date' => 'permit_empty|valid_date',
            'username'  => "required|min_length[3]|max_length[50]|is_unique[staff.username,staff_id,{$id}]"
        ];
        
        // Only validate password if it's being updated
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[8]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'role'      => $this->request->getPost('role'),
            'phone'     => $this->request->getPost('phone'),
            'email'     => $this->request->getPost('email'),
            'hire_date' => $this->request->getPost('hire_date'),
            'username'  => $this->request->getPost('username')
        ];
        
        // If password is being updated, hash it manually
        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        try {
            if ($this->staffModel->update($id, $data)) {
                return redirect()->to(base_url('manager/staff'))
                               ->with('success', 'Staff member updated successfully');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to update staff member: ' . implode(', ', $this->staffModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', '[Staff Update] ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'An error occurred while updating the staff member');
        }
    }

    public function destroy($id = null)
    {
        $staff = $this->staffModel->find($id);
        
        if (!$staff || $staff['manager_id'] != session()->get('manager_id')) {
            return redirect()->to('/manager/staff')->with('error', 'Staff member not found or not authorized.');
        }
        
        try {
            if ($this->staffModel->delete($id)) {
                return redirect()->to('/manager/staff')->with('success', 'Staff member deleted successfully.');
            } else {
                return redirect()->to('/manager/staff')->with('error', 'Failed to delete staff member.');
            }
        } catch (\Exception $e) {
            log_message('error', '[Staff Deletion] ' . $e->getMessage());
            return redirect()->to('/manager/staff')->with('error', 'An error occurred while deleting the staff member.');
        }
    }
}
