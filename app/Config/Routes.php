<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Admin routes group
$routes->group('admin', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::attemptRegister');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('dashboard', 'DashboardController::index');
});

$routes->get("/admin/forgot-password", "AuthController::forgotPassword");
$routes->post("/admin/forgot-password", "AuthController::processForgotPassword");
$routes->get("/admin/reset-password/(:any)", 'AuthController::resetPassword/$1');
$routes->post("/admin/reset-password", "AuthController::processResetPassword");
$routes->get("/admin/change-password", "AuthController::changePassword");
$routes->post("/admin/change-password", "AuthController::processChangePassword");
$routes->get("/admin/check-auth", "AuthController::checkAuth");
