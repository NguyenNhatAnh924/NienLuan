<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../bootstrap.php';

define('CONGNGHE', 'Horus Shop');

session_start();

$router = new \Bramus\Router\Router();

// Auth routes
$router->get('/', '\App\Controllers\HomeController@index');
$router->get('/home', '\App\Controllers\HomeController@index');
$router->post('/logout', '\App\Controllers\Auth\LoginController@destroy');
$router->get('/register', '\App\Controllers\Auth\RegisterController@create');
$router->post('/register', '\App\Controllers\Auth\RegisterController@store');
$router->get('/login', '\App\Controllers\Auth\LoginController@create');
$router->post('/login', '\App\Controllers\Auth\LoginController@store');

$router->post('/payment', '\App\Controllers\HomeController@Payment');
$router->post('/appointment', '\App\Controllers\AppoimentController@store');

$router->get('/doctor/appointments', '\App\Controllers\HomeController@DoctorAppointments');
$router->post('/completed_appointment/(\d+)','\App\Controllers\AppoimentController@completed_appointment');
$router->post('/canceled_appointment/(\d+)','\App\Controllers\AppoimentController@cancaled_appointment');
$router->post('/restore_appointment/(\d+)','\App\Controllers\AppoimentController@restore_appointment');
$router->get('/reception/appointments', '\App\Controllers\HomeController@ReceptionAppointments');
$router->get('/patient/appointments/(\d+)', '\App\Controllers\HomeController@PatientAppointments');
$router->get('/DoctorHistory', '\App\Controllers\HomeController@DoctorHistory');

$router->post('/appointments/create', '\App\Controllers\AppoimentController@ReceptionStore');

$router->get('/doctor/schedule/(\d+)','\App\Controllers\HomeController@schedule');
$router->post('/doctor/schedule/create', '\App\Controllers\UserController@saveSchedule');
$router->post('/doctor/schedule/update/(\d+)', '\App\Controllers\UserController@updateSchedule');
$router->post('/doctor/schedule/delete/(\d+)', '\App\Controllers\UserController@cancelSchedule');
$router->post('/edit-doctor-schedule', '\App\Controllers\UserController@adminupdateSchedule');

$router->get('/profile/(\d+)','\App\Controllers\HomeController@Profile');
$router->post('/update-doctor-info/(\d+)/([a-zA-Z0-9_-]+)', '\App\Controllers\UserController@update');
$router->post('/add-doctor-info', '\App\Controllers\UserController@save');
$router->post('/appointments/update', '\App\Controllers\AppoimentController@updateAppoiment');

$router->get('/medical_history/history/(\d+)', 'MedicalHistoryController@historyByPatientId');

$router->post('forms/edit-service', '\App\Controllers\ServiceController@update');
$router->post('forms/add-service', '\App\Controllers\ServiceController@save');


$router->post('/medical_history/add', '\App\Controllers\MedicalHistoryController@add');
$router->get('/admin','\App\Controllers\HomeController@Admin');

$router->set404('\App\Controllers\Controller@sendNotFound');
$router->run();