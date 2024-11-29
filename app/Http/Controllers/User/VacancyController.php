<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrjob;
use App\Models\HrjobCategory;
use Illuminate\Support\Facades\Storage;

class VacancyController extends Controller
{
    public function getVacancy()
    {
        $vacancies = Hrjob::with('category')
                          ->where('is_active', 'yes')
                          ->get();
        return view('user.vacancy.index', compact('vacancies'));
    }
}
