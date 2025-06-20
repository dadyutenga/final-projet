<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ManagerController;

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
});


