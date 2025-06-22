<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ManagerController;
use App\Controllers\HotelController;
use App\Controllers\ManagerAuthController;
use App\Controllers\StaffController;
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
    $routes->get('managers', 'ManagerController::index');
    $routes->get('managers/new', 'ManagerController::new');
    $routes->post('managers/create', 'ManagerController::create');
    $routes->delete('managers/(:num)', 'ManagerController::delete/$1');
    $routes->get('hotels', 'HotelController::index');
    $routes->get('hotels/new', 'HotelController::new');
    $routes->post('hotels/create', 'HotelController::create');
    $routes->delete('hotels/(:num)', 'HotelController::delete/$1');
});

$routes->group('manager', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('login', 'ManagerAuthController::login');
    $routes->post('login', 'ManagerAuthController::attemptLogin');
    $routes->get('logout', 'ManagerAuthController::logout');
    $routes->get('dashboard', 'ManagerDashboardController::index');
    
    // New routes for StaffController
    $routes->get('staff', 'StaffController::index');  // Lists staff
    $routes->get('staff/create', 'StaffController::create');  // Shows create form
    $routes->post('staff/store', 'StaffController::store');  // Handles staff creation
    $routes->get('staff/(:num)', 'StaffController::show/$1');  // Shows a single staff member
    $routes->get('staff/(:num)/edit', 'StaffController::edit/$1');  // Shows edit form
    $routes->post('staff/(:num)', 'StaffController::update/$1');  // Handles staff update (assumes POST with _method=PUT)
    $routes->delete('staff/(:num)', 'StaffController::destroy/$1');  // Handles staff deletion
});


