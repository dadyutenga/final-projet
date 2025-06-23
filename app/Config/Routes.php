<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ManagerController;
use App\Controllers\HotelController;
use App\Controllers\ManagerAuthController;
use App\Controllers\StaffController;
use App\Controllers\RoomTypeController;
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
    
    // Staff management routes
    $routes->get('staff', 'StaffController::index');
    $routes->get('staff/create', 'StaffController::create');
    $routes->post('staff/store', 'StaffController::store');
    $routes->get('staff/show/(:num)', 'StaffController::show/$1');
    $routes->get('staff/edit/(:num)', 'StaffController::edit/$1');
    $routes->post('staff/update/(:num)', 'StaffController::update/$1');
    $routes->delete('staff/destroy/(:num)', 'StaffController::destroy/$1');
    
    // Room Type management routes
    $routes->get('roomtypes', 'RoomTypeController::index');
    $routes->get('roomtypes/create', 'RoomTypeController::create');
    $routes->post('roomtypes/store', 'RoomTypeController::store');
    $routes->get('roomtypes/show/(:num)', 'RoomTypeController::show/$1');
    $routes->get('roomtypes/edit/(:num)', 'RoomTypeController::edit/$1');
    $routes->post('roomtypes/update/(:num)', 'RoomTypeController::update/$1');
    $routes->delete('roomtypes/destroy/(:num)', 'RoomTypeController::destroy/$1');

    
    // Room management routes
    $routes->get('rooms', 'RoomController::index');
    $routes->get('rooms/create', 'RoomController::create');
    $routes->post('rooms/store', 'RoomController::store');
    $routes->get('rooms/show/(:num)', 'RoomController::show/$1');
    $routes->get('rooms/edit/(:num)', 'RoomController::edit/$1');
    $routes->post('rooms/update/(:num)', 'RoomController::update/$1');
    $routes->delete('rooms/destroy/(:num)', 'RoomController::destroy/$1');
    $routes->post('rooms/bulk-status-update', 'RoomController::bulkStatusUpdate');

    $routes->get('staff-tasks', 'StaffTaskController::index');
    $routes->get('staff-tasks/create', 'StaffTaskController::create');
    $routes->post('staff-tasks/store', 'StaffTaskController::store');
    $routes->get('staff-tasks/show/(:num)', 'StaffTaskController::show/$1');
    $routes->get('staff-tasks/edit/(:num)', 'StaffTaskController::edit/$1');
    $routes->post('staff-tasks/update/(:num)', 'StaffTaskController::update/$1');
    $routes->delete('staff-tasks/destroy/(:num)', 'StaffTaskController::destroy/$1');
    $routes->post('staff-tasks/update-status/(:num)', 'StaffTaskController::updateStatus/$1');
    $routes->post('staff-tasks/reassign/(:num)', 'StaffTaskController::reassign/$1');
});


