<?php

use App\Http\Controllers\Admin\MonitorController;
use Illuminate\Support\Facades\Route;
use App\Models\Service;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/monitor', [MonitorController::class, 'index']);


Route::get('/kiosk', function () {
    $services = Service::all(); 
    return view('kiosk', ['services' => $services]);
});


Route::get('/admin/login', function () {
    return view('login');
})->name('login');


Route::get('/admin/dashboard', function () {
    return view('dashboard');
});

Route::view('/admin/services', 'admin.services'); 

Route::view('/admin/counters', 'admin.counters'); 

Route::view('/admin/staff', 'admin.staff');  

Route::get('/admin/queues', [App\Http\Controllers\Admin\QueueListController::class, 'index'])->name('admin.queues');