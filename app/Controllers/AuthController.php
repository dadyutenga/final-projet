<?php

namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    protected $adminModel;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        helper(["form", "url"]);
    }

    /**
     * Display login form
     */
    public function login()
    {
        // If admin is already logged in, redirect to dashboard
        if ($this->session->get("admin_logged_in")) {
            return redirect()->to("/admin/dashboard");
        }

        $data = [
            "title" => "Admin Login",
            "validation" => $this->validation,
        ];

        return view("auth/login", $data);
    }

    /**
     * Process login attempt
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
        $remember = $this->request->getPost("remember_me");

        // Verify credentials using AdminModel
        $admin = $this->adminModel->verifyCredentials($username, $password);

        if ($admin) {
            // Set session data
            $sessionData = [
                "admin_id" => $admin["admin_id"],
                "admin_username" => $admin["username"],
                "admin_email" => $admin["email"],
                "admin_full_name" => $admin["full_name"],
                "admin_logged_in" => true,
            ];

            $this->session->set($sessionData);

            // Set remember me cookie if checked
            if ($remember) {
                $this->setRememberMeCookie($admin["admin_id"]);
            }

            // Set success message
            $this->session->setFlashdata(
                "success",
                "Welcome back, " . $admin["full_name"] . "!"
            );

            // Redirect to dashboard
            return redirect()->to('/admin/dashboard');
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
     * Display registration form
     */
    public function register()
    {
        // Check if admin is already logged in
        if ($this->session->get("admin_logged_in")) {
            return redirect()->to("/admin/dashboard");
        }

        $data = [
            "title" => "Admin Registration",
            "validation" => $this->validation,
        ];

        return view("auth/register", $data);
    }

    /**
     * Process registration
     */
    public function attemptRegister()
    {
        // Validation rules
        $rules = [
            "username" =>
                "required|min_length[3]|max_length[50]|is_unique[admins.username]",
            "email" =>
                "required|valid_email|max_length[100]|is_unique[admins.email]",
            "full_name" => "required|max_length[100]",
            "password" => "required|min_length[8]",
            "confirm_password" => "required|matches[password]",
        ];

        $messages = [
            "username" => [
                "required" => "Username is required",
                "min_length" => "Username must be at least 3 characters long",
                "max_length" => "Username cannot exceed 50 characters",
                "is_unique" => "Username already exists",
            ],
            "email" => [
                "required" => "Email is required",
                "valid_email" => "Please enter a valid email address",
                "max_length" => "Email cannot exceed 100 characters",
                "is_unique" => "Email already exists",
            ],
            "full_name" => [
                "required" => "Full name is required",
                "max_length" => "Full name cannot exceed 100 characters",
            ],
            "password" => [
                "required" => "Password is required",
                "min_length" => "Password must be at least 8 characters long",
            ],
            "confirm_password" => [
                "required" => "Please confirm your password",
                "matches" => "Passwords do not match",
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()
                ->back()
                ->withInput()
                ->with("validation", $this->validator);
        }

        // Prepare data for insertion
        $data = [
            "username" => $this->request->getPost("username"),
            "email" => $this->request->getPost("email"),
            "full_name" => $this->request->getPost("full_name"),
            "password" => $this->request->getPost("password"), // Will be hashed by model
        ];

        // Insert admin
        if ($this->adminModel->insert($data)) {
            $this->session->setFlashdata(
                "success",
                "Registration successful! Please login."
            );
            return redirect()->to("/admin/login");
        } else {
            $this->session->setFlashdata(
                "error",
                "Registration failed. Please try again."
            );
            return redirect()->back()->withInput();
        }
    }

    /**
     * Logout admin
     */
    public function logout()
    {
        // Remove remember me cookie
        $this->removeRememberMeCookie();

        // Destroy session
        $this->session->remove([
            "admin_id",
            "admin_username",
            "admin_email",
            "admin_full_name",
            "admin_logged_in",
        ]);

        $this->session->setFlashdata(
            "success",
            "You have been logged out successfully"
        );
        return redirect()->to("/admin/login");
    }

    /**
     * Display forgot password form
     */
    public function forgotPassword()
    {
        if ($this->session->get("admin_logged_in")) {
            return redirect()->to("/admin/dashboard");
        }

        $data = [
            "title" => "Forgot Password",
            "validation" => $this->validation,
        ];

        return view("auth/forgot_password", $data);
    }

    /**
     * Process forgot password request
     */
    public function processForgotPassword()
    {
        $rules = [
            "email" => "required|valid_email",
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with("validation", $this->validator);
        }

        $email = $this->request->getPost("email");
        $admin = $this->adminModel->where("email", $email)->first();

        if ($admin) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Store token in database (you might want to create a password_reset_tokens table)
            $this->session->set("reset_token_" . $admin["admin_id"], [
                "token" => $token,
                "expiry" => $expiry,
            ]);

            // In a real application, you would send an email here
            // For now, we'll just show the reset link
            $resetLink = site_url("admin/reset-password/{$token}");

            $this->session->setFlashdata(
                "success",
                "Password reset instructions have been sent to your email. For demo purposes, use this link: <a href='{$resetLink}'>Reset Password</a>"
            );
        } else {
            // Don't reveal if email exists or not for security
            $this->session->setFlashdata(
                "success",
                "If an account with that email exists, password reset instructions have been sent."
            );
        }

        return redirect()->to("/admin/forgot-password");
    }

    /**
     * Display reset password form
     */
    public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to("/admin/forgot-password");
        }

        // Verify token (simplified version)
        $validToken = false;
        $adminId = null;

        // Check session for token (in production, use database)
        foreach ($this->session->get() as $key => $value) {
            if (
                strpos($key, "reset_token_") === 0 &&
                isset($value["token"]) &&
                $value["token"] === $token
            ) {
                if (strtotime($value["expiry"]) > time()) {
                    $validToken = true;
                    $adminId = str_replace("reset_token_", "", $key);
                    break;
                }
            }
        }

        if (!$validToken) {
            $this->session->setFlashdata(
                "error",
                "Invalid or expired reset token"
            );
            return redirect()->to("/admin/forgot-password");
        }

        $data = [
            "title" => "Reset Password",
            "token" => $token,
            "validation" => $this->validation,
        ];

        return view("auth/reset_password", $data);
    }

    /**
     * Process password reset
     */
    public function processResetPassword()
    {
        $rules = [
            "token" => "required",
            "password" => "required|min_length[8]",
            "confirm_password" => "required|matches[password]",
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with("validation", $this->validator);
        }

        $token = $this->request->getPost("token");
        $password = $this->request->getPost("password");

        // Verify token and get admin ID
        $validToken = false;
        $adminId = null;

        foreach ($this->session->get() as $key => $value) {
            if (
                strpos($key, "reset_token_") === 0 &&
                isset($value["token"]) &&
                $value["token"] === $token
            ) {
                if (strtotime($value["expiry"]) > time()) {
                    $validToken = true;
                    $adminId = str_replace("reset_token_", "", $key);
                    break;
                }
            }
        }

        if (!$validToken) {
            $this->session->setFlashdata(
                "error",
                "Invalid or expired reset token"
            );
            return redirect()->to("/admin/forgot-password");
        }

        // Update password
        if ($this->adminModel->update($adminId, ["password" => $password])) {
            // Remove token from session
            $this->session->remove("reset_token_" . $adminId);

            $this->session->setFlashdata(
                "success",
                "Password has been reset successfully. Please login with your new password."
            );
            return redirect()->to("/admin/login");
        } else {
            $this->session->setFlashdata(
                "error",
                "Failed to reset password. Please try again."
            );
            return redirect()->back();
        }
    }

    /**
     * Check if admin is logged in (for AJAX requests)
     */
    public function checkAuth()
    {
        $isLoggedIn = $this->session->get("admin_logged_in") ? true : false;

        return $this->response->setJSON([
            "logged_in" => $isLoggedIn,
            "admin_id" => $this->session->get("admin_id"),
            "admin_name" => $this->session->get("admin_full_name"),
        ]);
    }

    /**
     * Set remember me cookie
     */
    private function setRememberMeCookie($adminId)
    {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + 30 * 24 * 60 * 60; // 30 days

        // Set cookie
        setcookie("remember_admin", $token, $expiry, "/", "", false, true);

        // Store token in session (in production, use database)
        $this->session->set("remember_token_" . $adminId, $token);
    }

    /**
     * Remove remember me cookie
     */
    private function removeRememberMeCookie()
    {
        setcookie("remember_admin", "", time() - 3600, "/", "", false, true);
    }

    /**
     * Check remember me cookie on page load
     */
    public function checkRememberMe()
    {
        if ($this->session->get("admin_logged_in")) {
            return;
        }

        $rememberToken = $_COOKIE["remember_admin"] ?? null;

        if ($rememberToken) {
            // Check token in session (in production, check database)
            foreach ($this->session->get() as $key => $value) {
                if (
                    strpos($key, "remember_token_") === 0 &&
                    $value === $rememberToken
                ) {
                    $adminId = str_replace("remember_token_", "", $key);
                    $admin = $this->adminModel->find($adminId);

                    if ($admin) {
                        // Auto login
                        $sessionData = [
                            "admin_id" => $admin["admin_id"],
                            "admin_username" => $admin["username"],
                            "admin_email" => $admin["email"],
                            "admin_full_name" => $admin["full_name"],
                            "admin_logged_in" => true,
                        ];

                        $this->session->set($sessionData);
                        return redirect()->to("/Admin/Dashboard");
                    }
                }
            }
        }
    }

    /**
     * Change password (for logged in admin)
     */
    public function changePassword()
    {
        if (!$this->session->get("admin_logged_in")) {
            return redirect()->to("/Auth/Login");
        }

        $data = [
            "title" => "Change Password",
            "validation" => $this->validation,
        ];

        return view("auth/change_password", $data);
    }

    /**
     * Process password change
     */
    public function processChangePassword()
    {
        if (!$this->session->get("admin_logged_in")) {
            return redirect()->to("/admin/login");
        }

        $rules = [
            "current_password" => "required",
            "new_password" => "required|min_length[8]",
            "confirm_new_password" => "required|matches[new_password]",
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with("validation", $this->validator);
        }

        $adminId = $this->session->get("admin_id");
        $currentPassword = $this->request->getPost("current_password");
        $newPassword = $this->request->getPost("new_password");

        // Verify current password
        $admin = $this->adminModel->find($adminId);
        if (!password_verify($currentPassword, $admin["password_hash"])) {
            $this->session->setFlashdata(
                "error",
                "Current password is incorrect"
            );
            return redirect()->back();
        }

        // Update password
        if ($this->adminModel->update($adminId, ["password" => $newPassword])) {
            $this->session->setFlashdata(
                "success",
                "Password changed successfully"
            );
            return redirect()->to("/admin/profile");
        } else {
            $this->session->setFlashdata("error", "Failed to change password");
            return redirect()->back();
        }
    }
}
