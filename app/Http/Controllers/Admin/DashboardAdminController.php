<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardAdminController extends Controller
{
    public function getAdmin()
    {
        return view('admin.layout.app');
    }
}
