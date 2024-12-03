<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hrjob;

class DashboardUserController extends Controller
{
    public function getDashboardUser()
    {
        $davacancies = Hrjob::with('category')
                            ->where('is_active', 'yes')
                            ->orderBy('created_at', 'desc')
                            ->take(3) // Batasi hanya 3 data
                            ->get();

        return view('user.dashboard', compact('davacancies'));
    }

    public function getTeam()
    {
        return view('user.team');
    }
}
