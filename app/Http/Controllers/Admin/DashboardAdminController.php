<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hrjob;

class DashboardAdminController extends Controller
{
    public function getDashboardAdmin(Request $request)
    {
        // Filter tanggal jika ada input
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Query user dengan status 'applicant'
        $applicantCountQuery = User::where('role', 'applicant');
        $jobCountQuery = Hrjob::query();

        // Filter berdasarkan tanggal jika diberikan
        if ($startDate && $endDate) {
            $applicantCountQuery->whereBetween('created_at', [$startDate, $endDate]);
            $jobCountQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $applicantCount = $applicantCountQuery->count();
        $jobCount = $jobCountQuery->count();

        return view('admin.dashboardadmin', [
            'applicantCount' => $applicantCount,
            'jobCount' => $jobCount,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
