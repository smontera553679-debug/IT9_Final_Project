<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserGuideController extends Controller
{
    public function admin()
    {
        return view('admin.user-guide');
    }

    public function customer()
    {
        return view('customer.user-guide');
    }
}