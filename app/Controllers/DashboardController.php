<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        // Check if admin is logged in
        if (!$this->session->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Admin Dashboard',
            'admin_name' => $this->session->get('admin_full_name')
        ];

        return view('admin/dashboard', $data);
    }
} 