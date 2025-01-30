<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hrjob;
use App\Models\UserHrjob;
use App\Models\Source;
use App\Models\UserHrjobStatusHistory;
use App\Models\Offering;

class DashboardAdminController extends Controller
{
    public function getDashboardAdmin(Request $request)
    {
        // Filter tanggal jika ada input
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $isEnded = $request->get('is_ended');

        // Fungsi reusable untuk filter tanggal
        $applyDateFilter = function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            return $query;
        };

        // $applyupDateFilter = function ($query) use ($startDate, $endDate) {
        //     if ($startDate && $endDate) {
        //         $query->whereBetween('updated_at', [$startDate, $endDate]); // Ganti created_at dengan updated_at
        //     }
        //     return $query;
        // };

        $applyIsEndedFilter = function ($query) use ($isEnded) {
            if ($isEnded !== null) {
                $query->where('is_ended', $isEnded);
            }
            return $query;
        };

        $applyuserIsEndedFilter = function ($query) use ($isEnded) {
            if ($isEnded !== null) {
                $query->whereHas('hrjob', function ($q) use ($isEnded) {
                    $q->where('is_ended', $isEnded);
                });
            }
            return $query;
        };

        $applyuserhrjobIsEndedFilter = function ($query) use ($isEnded) {
            if ($isEnded !== null) {
                $query->whereHas('userHrjob', function ($q) use ($isEnded) {
                    $q->whereHas('hrjob', function ($qq) use ($isEnded) {
                        $qq->where('is_ended', $isEnded);
                    });
                });
            }
            return $query;
        };


        // Menghitung jumlah applicant
        $applicantCount = $applyDateFilter(
            User::where('role', 'applicant')
        )->count();

        // Menghitung jumlah job
        $jobCount = $applyIsEndedFilter(
            $applyDateFilter(
                Hrjob::query()
            )
        )->count();

        // Menghitung Conversion Rate
        $hiredCount = $applyuserIsEndedFilter(
            $applyDateFilter(
                UserHrjob::where('status', 'hired')
            )
        )->count();
        $totalJobApplicants = $applyuserIsEndedFilter(
            $applyDateFilter(
                UserHrjob::query()
            )
        )->count();
        $conversionRate = $totalJobApplicants > 0 ? ($hiredCount / $totalJobApplicants) * 100 : 0;

        // Menghitung rata-rata hiring_cost
        $averageHiringCost = $applyIsEndedFilter(
            $applyDateFilter(
                Hrjob::query()
            )
        )->avg('hiring_cost');

        // Menghitung rata-rata day to hire dengan penyesuaian hari penuh
        $averageDayToHire = $applyIsEndedFilter(
            $applyDateFilter(
                Hrjob::whereNotNull('job_closed')
            )
        )->get()
        ->map(fn($job) => $job->created_at->startOfDay()->diffInDays($job->job_closed->startOfDay()) + 1)
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
        $onTimeCount = $applyIsEndedFilter(
            $applyDateFilter(
                Hrjob::query()
            )
        )->whereNotNull('expired')
        ->whereRaw('DATE(job_closed) = DATE(expired)')
        ->count();
        $totalJobsWithExpiredDate = $applyIsEndedFilter(
            $applyDateFilter(
                Hrjob::query()
            )
        )->whereNotNull('expired')
        ->count();
        $percentageFilledOnTime = $totalJobsWithExpiredDate > 0
            ? ($onTimeCount / $totalJobsWithExpiredDate) * 100
            : 0;

        // Menghitung Hired vs Rejected per bulan
        // $hiredRejectedData = $applyupDateFilter(
        //     UserHrjob::query()
        // )
        //     ->selectRaw('MONTH(updated_at) as month, YEAR(updated_at) as year, status, COUNT(*) as count')
        //     ->groupBy('month', 'year', 'status')
        //     ->get()
        //     ->groupBy('status')
        //     ->map(function ($group) {
        //         return $group->groupBy('year')->map(function ($yearGroup) {
        //             return $yearGroup->mapWithKeys(function ($item) {
        //                 return [sprintf('%04d-%02d', $item->year, $item->month) => $item->count];
        //             });
        //         });
        //     });

        // $statuses = [
        //     'applicant', 'shortlist', 'phone_screen', 'hr_interview',
        //     'user_interview', 'skill_test', 'reference_check',
        //     'offering', 'rejected', 'hired'
        // ];

        // Menghitung funnel chart
        $statuses = [
            'applicant', 'shortlist', 'phone_screen', 'hr_interview',
            'user_interview', 'hired'
        ];

        $funnelData = collect($statuses)->mapWithKeys(function ($status) use ($applyDateFilter, $applyuserIsEndedFilter, $applyuserhrjobIsEndedFilter) {
            if ($status === 'applicant') {
                // Jika status adalah applicant, hitung dari total id_user_job
                $count = $applyuserIsEndedFilter(
                    $applyDateFilter(
                        UserHrjob::query()
                            ->distinct('id')
                    )
                )->count();
            } else {
                // Untuk status lainnya, lakukan filter berdasarkan status
                $count = $applyuserhrjobIsEndedFilter(
                    $applyDateFilter(
                        UserHrjobStatusHistory::query()
                            ->select('id_user_job')
                            ->where('status', $status)
                            ->distinct('id_user_job')
                    )
                )->count();
            }

            return [$status => $count];
        });

        // Menghitung total interview
        $totalHrInterviews = $applyuserhrjobIsEndedFilter(
            $applyDateFilter(
                UserHrjobStatusHistory::query()
                    ->where('status', 'hr_interview')
                    ->distinct('id_user_job')
            )
        )->count();

        $totalUserInterviews = $applyuserhrjobIsEndedFilter(
            $applyDateFilter(
                UserHrjobStatusHistory::query()
                    ->where('status', 'user_interview')
                    ->distinct('id_user_job')
            )
        )->count();

        $totalInterviews = $totalHrInterviews + $totalUserInterviews;

        // Menghitung Offering Success Rate
        $totalOfferings = $applyuserhrjobIsEndedFilter(
            $applyDateFilter(
                Offering::query()
            )
        )->count();

        $successfulOfferings = $applyuserhrjobIsEndedFilter(
            $applyDateFilter(
                Offering::query()->whereNotNull('id_job')
            )
        )->count();

        $offeringSuccessRate = $totalOfferings > 0
            ? ($successfulOfferings / $totalOfferings) * 100
            : 0;

        //Menghitung Hiring Success Rate
        $hiredCountHSR = $applyuserIsEndedFilter(
            $applyDateFilter(
                UserHrjob::where('status', 'hired')
            )
        )->count();
        $jobCountHSR = $applyIsEndedFilter(
            $applyDateFilter(
                Hrjob::query()
            )
        )->count();
        $hiringSuccessRate = $jobCountHSR > 0 ? ($hiredCountHSR / $jobCountHSR) * 100 : 0;

        return view('admin.dashboardadmin', [
            'applicantCount' => $applicantCount,
            'jobCount' => $jobCount,
            'conversionRate' => $conversionRate,
            'hiringSuccessRate' => $hiringSuccessRate,
            'averageHiringCost' => $averageHiringCost,
            'averageDayToHire' => $averageDayToHire,
            'percentageData' => $percentageData,
            'percentageFilledOnTime' => $percentageFilledOnTime,
            // 'hiredRejectedData' => $hiredRejectedData,
            'funnelData' => $funnelData,
            'totalInterviews' => $totalInterviews,
            'offeringSuccessRate' => $offeringSuccessRate,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
