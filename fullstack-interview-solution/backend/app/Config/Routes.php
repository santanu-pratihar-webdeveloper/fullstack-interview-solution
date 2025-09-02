<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->get('/', 'Home::index');

// Auth
$routes->post('/api/register', 'AuthController::register');
$routes->post('/api/login', 'AuthController::login');
$routes->get('/api/me', 'AuthController::me', ['filter' => 'jwtAuth']);

// Single POST to create user + teacher
$routes->post('/api/users-with-teacher', 'TeacherController::createUserWithTeacher');

// Protected listings
$routes->get('/api/auth-users', 'AuthController::listUsers', ['filter' => 'jwtAuth']);
$routes->get('/api/teachers', 'TeacherController::index', ['filter' => 'jwtAuth']);
