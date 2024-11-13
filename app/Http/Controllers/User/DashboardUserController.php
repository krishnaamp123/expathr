<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardUserController extends Controller
{
    public function getUser()
    {
        return view('user.dashboard');
    }

    public function getTeam()
    {
        return view('user.team');
    }
}
