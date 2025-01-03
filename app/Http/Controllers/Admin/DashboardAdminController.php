<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hrjob;
use App\Models\UserHrjob;
use App\Models\Source;
use App\Models\UserHrjobStatusHistory;

class DashboardAdminController extends Controller
{
    public function getDashboardAdmin(Request $request)
    {
        // Filter tanggal jika ada input
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Fungsi reusable untuk filter tanggal
        $applyDateFilter = function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            return $query;
        };

        $applyupDateFilter = function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('updated_at', [$startDate, $endDate]); // Ganti created_at dengan updated_at
            }
            return $query;
        };

        // Menghitung jumlah applicant
        $applicantCount = $applyDateFilter(
            User::where('role', 'applicant')
        )->count();

        // Menghitung jumlah job
        $jobCount = $applyDateFilter(
            Hrjob::query()
        )->count();

        // Menghitung Hiring Success Rate
        $hiredCount = $applyDateFilter(
            UserHrjob::where('status', 'hired')
        )->count();
        $totalJobApplicants = $applyDateFilter(
            UserHrjob::query()
        )->count();
        $hiringSuccessRate = $totalJobApplicants > 0 ? ($hiredCount / $totalJobApplicants) * 100 : 0;

        // Menghitung rata-rata hiring_cost
        $averageHiringCost = $applyDateFilter(
            Hrjob::query()
        )->avg('hiring_cost');

        // Menghitung rata-rata day to hire dengan penyesuaian hari penuh
        $averageDayToHire = $applyDateFilter(
            Hrjob::whereNotNull('job_closed')
        )->get()
        ->map(function ($job) {
            return $job->created_at->startOfDay()->diffInDays($job->job_closed->startOfDay()) + 1;
        })
        ->avg();

        // Menghitung Percentage of Referral Hires
        $sources = $applyDateFilter(Source::query())->get();
        $totalSources = $sources->count();
        $platforms = $sources->whereNotNull('platform')->groupBy('platform')->map->count();
        $referals = $sources->whereNotNull('referal')->count();
        $others = $sources->whereNotNull('other')->count();
        $percentageData = [
            'platforms' => $platforms,
            'referals' => $referals,
            'others' => $others,
        ];

        // Menghitung Percentage of Positions Filled within Target Timeframe
        $onTimeCount = $applyDateFilter(Hrjob::query())
            ->whereNotNull('expired')
            ->whereRaw('DATE(job_closed) = DATE(expired)')
            ->count();
        $totalJobsWithExpiredDate = $applyDateFilter(Hrjob::query())
            ->whereNotNull('expired')
            ->count();
        $percentageFilledOnTime = $totalJobsWithExpiredDate > 0
            ? ($onTimeCount / $totalJobsWithExpiredDate) * 100
            : 0;

        // Menghitung Hired vs Rejected per bulan
        $hiredRejectedData = $applyupDateFilter(
            UserHrjob::query()
        )
            ->selectRaw('MONTH(updated_at) as month, YEAR(updated_at) as year, status, COUNT(*) as count')
            ->groupBy('month', 'year', 'status')
            ->get()
            ->groupBy('status')
            ->map(function ($group) {
                return $group->groupBy('year')->map(function ($yearGroup) {
                    return $yearGroup->mapWithKeys(function ($item) {
                        return [sprintf('%04d-%02d', $item->year, $item->month) => $item->count];
                    });
                });
            });

        // $hiredRejectedData = $applyDateFilter(
        //     UserHrjobStatusHistory::query()
        // )
        //     ->selectRaw('id_user_job, status, MAX(updated_at) as latest_update')
        //     ->groupBy('id_user_job', 'status')
        //     ->get()
        //     ->groupBy('status')
        //     ->map(function ($statusGroup) {
        //         return $statusGroup
        //             ->groupBy(function ($item) {
        //                 // Kelompokkan berdasarkan tahun dari updated_at
        //                 return \Carbon\Carbon::parse($item->latest_update)->year;
        //             })
        //             ->map(function ($yearGroup) {
        //                 return $yearGroup
        //                     ->groupBy(function ($item) {
        //                         // Kelompokkan berdasarkan bulan dari updated_at
        //                         return \Carbon\Carbon::parse($item->latest_update)->format('Y-m');
        //                     })
        //                     ->map->count();
        //             });
        //     });

        // Menghitung funnel chart
        $statuses = [
            'applicant', 'shortlist', 'phone_screen', 'hr_interview',
            'user_interview', 'skill_test', 'reference_check',
            'offering', 'rejected', 'hired'
        ];

        $funnelData = collect($statuses)->mapWithKeys(function ($status) use ($applyDateFilter) {
            if ($status === 'applicant') {
                // Jika status adalah applicant, hitung dari total id_user_job
                $count = $applyDateFilter(
                    UserHrjob::query()
                        ->distinct('id')
                )->count();
            } else {
                // Untuk status lainnya, lakukan filter berdasarkan status
                $count = $applyDateFilter(
                    UserHrjobStatusHistory::query()
                        ->select('id_user_job')
                        ->where('status', $status)
                        ->distinct('id_user_job')
                )->count();
            }

            return [$status => $count];
        });

        return view('admin.dashboardadmin', [
            'applicantCount' => $applicantCount,
            'jobCount' => $jobCount,
            'hiringSuccessRate' => $hiringSuccessRate,
            'averageHiringCost' => $averageHiringCost,
            'averageDayToHire' => $averageDayToHire,
            'percentageData' => $percentageData,
            'percentageFilledOnTime' => $percentageFilledOnTime,
            'hiredRejectedData' => $hiredRejectedData,
            'funnelData' => $funnelData,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
