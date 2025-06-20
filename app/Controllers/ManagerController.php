<?php

namespace App\Controllers;

use App\Models\ManagerModel;
use CodeIgniter\RESTful\ResourceController;

class ManagerController extends ResourceController
{
    protected $managerModel;

    public function __construct()
    {
        $this->managerModel = new ManagerModel();
    }

    public function index()
    {
        $searchTerm = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        // Set up pagination
        $this->managerModel->builder()->select('*');
        
        // Apply search if term exists
        if (!empty($searchTerm)) {
            $this->managerModel->builder()
                ->groupStart()
                ->like('username', $searchTerm)
                ->orLike('email', $searchTerm)
                ->orLike('full_name', $searchTerm)
                ->groupEnd();
        }

        // Get paginated results
        $managers = $this->managerModel->paginate($perPage);
        
        // Get pager
        $pager = $this->managerModel->pager;

        return view('admin/managers/index', [
            'managers' => $managers,
            'pager' => $pager,
            'searchTerm' => $searchTerm
        ]);
    }

    public function new()
    {
        return view('admin/managers/create');
    }

    public function create()
    {
        // Validation rules
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[managers.username]',
            'email' => 'required|valid_email|is_unique[managers.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'full_name' => 'required|max_length[100]',
            'phone' => 'permit_empty|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Prepare data for insertion
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone')
        ];

        try {
            // Attempt to insert the data
            if ($this->managerModel->save($data)) {
                return redirect()->to(base_url('admin/managers'))
                               ->with('success', 'Manager created successfully');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to create manager: ' . implode(', ', $this->managerModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', '[Manager Creation] ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'An error occurred while creating the manager');
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'No ID provided']);
        }

        try {
            if ($this->managerModel->delete($id)) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete manager']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
