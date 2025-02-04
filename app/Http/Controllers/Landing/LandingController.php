<?php

namespace App\Http\Controllers\Landing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hrjob;

class LandingController extends Controller
{
    public function getLanding()
    {
        $landingjobs = Hrjob::with('category', 'city')
                            ->where('is_ended', 'no')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('landing.landing', compact('landingjobs'));
    }
}
