<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminCarsController extends Controller
{
    public function index()
    {
        return view('admin.cars');
    }
}
