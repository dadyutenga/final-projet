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
$routes->get('book', 'Home::book');


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
    $routes->post('staff-tasks/update-status/(:num)', 'StaffTaskController::updateStatus/$1');

});

$routes->group('staff', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('login', 'StaffAuthController::login');
    $routes->post('login', 'StaffAuthController::processLogin');
    $routes->get('logout', 'StaffAuthController::logout');
    $routes->get('dashboard', 'StaffAuthController::dashboard');
    $routes->get('profile', 'StaffAuthController::profile');
    $routes->post('profile', 'StaffAuthController::updateProfile');
    $routes->post('change-password', 'StaffAuthController::changePassword');
   
    $routes->get('tasks', 'TaskViewerController::index');
    $routes->get('tasks/show/(:num)', 'TaskViewerController::show/$1');
    $routes->post('tasks/update-status/(:num)', 'TaskViewerController::updateStatus/$1');
    
    // Booking management routes
    $routes->get('bookings', 'BookingController::index');
    $routes->get('bookings/create', 'BookingController::create');
    $routes->post('bookings/store', 'BookingController::store');
    $routes->get('bookings/view/(:num)', 'BookingController::view/$1');
    $routes->post('bookings/update-status/(:num)', 'BookingController::updateStatus/$1');
    $routes->post('bookings/delete/(:num)', 'BookingController::delete/$1');
    $routes->post('bookings/search-ticket', 'BookingController::searchByTicket');
    $routes->get('bookings/today-activity', 'BookingController::todayActivity');
    $routes->post('bookings/get-available-rooms', 'BookingController::getAvailableRooms');
});

// Customer Booking Routes
$routes->get('/customer-booking/get-hotels', 'CustomerBookingController::getHotels');
$routes->post('/customer-booking/get-available-rooms', 'CustomerBookingController::getAvailableRooms');
$routes->get('/customer-booking/get-room-details/(:num)', 'CustomerBookingController::getRoomDetails/$1');
$routes->get('/customer-booking/get-hotel-info/(:num)', 'CustomerBookingController::getHotelInfo/$1');
$routes->post('/customer-booking/calculate-price', 'CustomerBookingController::calculatePrice');
$routes->post('/customer-booking/process-booking', 'CustomerBookingController::processBooking');
$routes->post('/customer-booking/check-booking', 'CustomerBookingController::checkBooking');
$routes->post('/customer-booking/get-booking-details', 'CustomerBookingController::getBookingDetails');
$routes->post('/customer-booking/cancel-booking', 'CustomerBookingController::cancelBooking');
$routes->post('/customer-booking/update-booking', 'CustomerBookingController::updateBooking');
$routes->post('/customer-booking/get-booking-history', 'CustomerBookingController::getBookingHistory');
$routes->get('/customer-booking/get-booking-stats', 'CustomerBookingController::getBookingStats');

