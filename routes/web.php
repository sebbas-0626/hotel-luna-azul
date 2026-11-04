<?php
use App\Core\Router;

/** @var Router $router */

// Auth
$router->post('/api/login', 'AuthController@login');
$router->post('/api/logout', 'AuthController@logout');
$router->get('/api/me', 'AuthController@me');

// Huespedes
$router->get('/api/huespedes', 'HuespedController@index');
$router->post('/api/huespedes', 'HuespedController@store');
$router->get('/api/huespedes/{id}', 'HuespedController@show');
$router->put('/api/huespedes/{id}', 'HuespedController@update');
$router->delete('/api/huespedes/{id}', 'HuespedController@destroy');
$router->get('/api/huespedes/{id}/reservas', 'HuespedController@reservas');

// Habitaciones
$router->get('/api/habitaciones', 'HabitacionController@index');
$router->post('/api/habitaciones', 'HabitacionController@store');
$router->put('/api/habitaciones/{id}', 'HabitacionController@update');
$router->delete('/api/habitaciones/{id}', 'HabitacionController@destroy');

// Reservas
$router->get('/api/reservas', 'ReservaController@index');
$router->post('/api/reservas', 'ReservaController@store');
$router->get('/api/reservas/{id}', 'ReservaController@show');
$router->put('/api/reservas/{id}', 'ReservaController@update');
$router->delete('/api/reservas/{id}', 'ReservaController@destroy');
$router->get('/api/disponibilidad', 'ReservaController@disponibilidad');

// Frontend views 
$router->get('/', function ($req) { \App\Core\Response::view('index'); });
$router->get('/login', function ($req) { \App\Core\Response::view('login'); });
$router->get('/huespedes', function ($req) { \App\Core\Response::view('huespedes'); });
$router->get('/reservas', function ($req) { \App\Core\Response::view('reservas'); });
$router->get('/habitaciones', function ($req) { \App\Core\Response::view('habitaciones'); });
