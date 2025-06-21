<?php

namespace App\Controllers;

use App\Models\ManagerModel;
use CodeIgniter\Controller;

class ManagerAuthController extends BaseController
{
    protected $managerModel;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->managerModel = new ManagerModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        helper(["form", "url"]);
    }

    /**
     * Display login form for managers
     */
    public function login()
    {
        // If manager is already logged in, redirect to manager dashboard
        if ($this->session->get("manager_logged_in")) {
            return redirect()->to("/manager/dashboard");
        }

        $data = [
            "title" => "Manager Login",
            "validation" => $this->validation,
        ];

        return view("auth/login1", $data);  // Current path; confirm it's in app/Views/auth/
    }

    /**
     * Process login attempt for managers
     */
    public function attemptLogin()
    {
        // Validation rules
        $rules = [
            "username" => "required|min_length[3]",
            "password" => "required|min_length[3]",
        ];

        $messages = [
            "username" => [
                "required" => "Username is required",
                "min_length" => "Username must be at least 3 characters long",
            ],
            "password" => [
                "required" => "Password is required",
                "min_length" => "Password must be at least 3 characters long",
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()
                ->back()
                ->withInput()
                ->with("validation", $this->validator);
        }

        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");

        // Verify credentials using ManagerModel
        $manager = $this->managerModel->verifyCredentials($username, $password);

        if ($manager) {
            // Set session data
            $sessionData = [
                "manager_id" => $manager["manager_id"],
                "manager_username" => $manager["username"],
                "manager_email" => $manager["email"],
                "manager_full_name" => $manager["full_name"],
                "manager_logged_in" => true,
            ];

            $this->session->set($sessionData);

            // Set success message
            $this->session->setFlashdata(
                "success",
                "Welcome back, " . $manager["full_name"] . "!"
            );

            // Redirect to manager dashboard
            return redirect()->to('/manager/dashboard');
        } else {
            // Authentication failed
            $this->session->setFlashdata(
                "error",
                "Invalid username or password"
            );
            return redirect()->back()->withInput();
        }
    }

    /**
     * Logout manager
     */
    public function logout()
    {
        $this->session->destroy();
        $this->session->setFlashdata("success", "You have been logged out successfully.");
        return redirect()->to('/manager/login');
    }
}
