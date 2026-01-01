<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index()
    {
        $services = Service::with('counters')->get();

        return view('monitor', compact('services'));
    }
}