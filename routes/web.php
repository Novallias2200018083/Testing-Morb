<?php

use Illuminate\Support\Facades\Route;
use App\Models\Service;
use App\Http\Controllers\Admin\MonitorController;
use App\Http\Controllers\Admin\QueueListController;


Route::redirect('/', '/admin/login');


Route::get('/monitor', [MonitorController::class, 'index'])->name('monitor');


Route::get('/kiosk', function () {
    $services = Service::all();
    return view('kiosk', ['services' => $services]);
})->name('kiosk');



Route::prefix('admin')->name('admin.')->group(function () {
    
  
    Route::get('/login', function () {
        return view('login');
    })->name('login'); 

    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    
    Route::view('/services', 'admin.services')->name('services');
    Route::view('/counters', 'admin.counters')->name('counters');
    Route::view('/staff', 'admin.staff')->name('staff');

    Route::get('/queues', [QueueListController::class, 'index'])->name('queues');
});