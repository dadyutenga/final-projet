<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\HotelModel;
use App\Models\PaymentModel;


class DashboardController extends BaseController
{
    protected $session;
    protected $adminModel;
    protected $hotelModel;
    protected $paymentModel;


    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->adminModel = new AdminModel();
        $this->hotelModel = new HotelModel();
        $this->paymentModel = new PaymentModel();
       
    }

    public function index()
    {
        // Check if admin is logged in
        if (!$this->session->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        // Get total counts
        $totalHotels = $this->hotelModel->countAll();
        
        // Get total revenue (completed payments)
        $totalRevenue = $this->paymentModel->select('SUM(amount) as total_revenue')
            ->where('payment_status', 'completed')
            ->first();

        // Get recent hotels with their details
        $recentHotels = $this->hotelModel->getRecentHotels(5);

        // Get recent activities (from payments only)
        $recentActivities = $this->getRecentActivities();

        $data = [
            'title' => 'Admin Dashboard',
            'admin_name' => $this->session->get('admin_full_name'),
            'total_hotels' => $totalHotels,
            'total_revenue' => $totalRevenue['total_revenue'] ?? 0,
            'recent_hotels' => $recentHotels,
            'recent_activities' => $recentActivities
        ];

        return view('admin/dashboard', $data);
    }

    private function getRecentActivities()
    {
        // Get recent payments only
        $recentPayments = $this->paymentModel->select('
                payment_id,
                amount,
                payment_status,
                payment_date as activity_date
            ')
            ->orderBy('payment_date', 'DESC')
            ->limit(5)
            ->find();

        // Format activities
        $activities = [];
        
        foreach ($recentPayments as $payment) {
            $activities[] = [
                'description' => 'New payment of $' . number_format($payment['amount'], 2),
                'time' => $this->getTimeAgo($payment['activity_date']),
                'status' => $payment['payment_status']
            ];
        }

        return $activities;
    }

    private function getTimeAgo($datetime)
    {
        $time = strtotime($datetime);
        $current = time();
        $difference = $current - $time;
        
        if ($difference < 60) {
            return "Just now";
        } elseif ($difference < 3600) {
            $minutes = floor($difference / 60);
            return $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
        } elseif ($difference < 86400) {
            $hours = floor($difference / 3600);
            return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
        } else {
            $days = floor($difference / 86400);
            return $days . " day" . ($days > 1 ? "s" : "") . " ago";
        }
    }
} 