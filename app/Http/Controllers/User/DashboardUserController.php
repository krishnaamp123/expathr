<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hrjob;

class DashboardUserController extends Controller
{
    public function getDashboardUser()
    {
        $davacancies = Hrjob::with('category', 'city')
                            ->where('is_ended', 'yes')
                            ->orderBy('created_at', 'desc')
                            // ->take(3) // Batasi hanya 3 data
                            ->get();

        // Cek kelengkapan profil pengguna
        $user = auth()->user();
        $isProfileComplete = $user->workLocation->count() > 0 && $user->emergency->count() > 0 && $user->language->count() > 0 &&
                        $user->skill->count() > 0 && $user->workField->count() > 0 &&
                        $user->education->count() > 0 && $user->experience->count() > 0;

        return view('user.dashboard', compact('davacancies', 'isProfileComplete'));
    }

    public function getTeam()
    {
        return view('user.team');
    }
}
