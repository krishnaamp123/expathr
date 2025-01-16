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
                            ->where('is_ended', 'no')
                            ->orderBy('created_at', 'desc')
                            // ->take(3) // Batasi hanya 3 data
                            ->get();

        // Cek kelengkapan profil pengguna
        $user = auth()->user();
        $isProfileComplete = $user->workLocation->count() > 0 && $user->emergency->count() > 0 && $user->language->count() > 0 &&
                        $user->workSkill->count() > 0 && $user->workField->count() > 0 && $user->source->count() > 0 &&
                        $user->education->count() > 0 && $user->reference->count() >= 2 && $user->experience->count() > 0;

        return view('user.dashboard', compact('davacancies', 'isProfileComplete'));
    }
}
