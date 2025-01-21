<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserHrjob;
use App\Models\Hrjob;
use App\Models\User;
use App\Models\Interview;
use App\Models\UserHrjobStatusHistory;
use App\Models\Reference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UserHrjobAdminController extends Controller
{

    public function getUserHrjob(Request $request)
    {
        // Ambil parameter status, start_date, end_date, dan id_job dari URL
        $status = $request->query('status');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $hrjobId = $request->query('id_job');

        // Tentukan logika penyaringan berdasarkan role pengguna
        if (Auth::user()->role === 'hiring_manager') {
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews.interviewers', 'userinterviews.user_interviewers', 'userAnswer',
            'userAnswer.question.form',
            'userAnswer.question.answers',
            'userAnswer.answer')
                ->where(function ($query) use ($status) {
                    $query->whereHas('interviews.interviewers', function ($subQuery) {
                        $subQuery->where('role', '!=', 'super_admin');
                    })
                    ->orWhereHas('userinterviews.user_interviewers', function ($subQuery) {
                        $subQuery->where('role', '!=', 'super_admin');
                    })
                    ->orWhereDoesntHave('interviews') // Tampilkan data tanpa relasi interviews juga
                    ->orWhereDoesntHave('userinterviews'); // Data tanpa userinterviews juga

                    if ($status) {
                        $query->where('status', $status);
                    }
                });
        } elseif (Auth::user()->role === 'recruiter') {
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews.interviewers', 'userinterviews.user_interviewers', 'userAnswer',
            'userAnswer.question.form',
            'userAnswer.question.answers',
            'userAnswer.answer')
                ->where(function ($query) use ($status) {
                    // Pekerjaan yang recruiter handle berdasarkan `id_user`
                    $query->whereHas('hrjob', function ($subQuery) {
                        $subQuery->where('id_user', Auth::id());
                    })
                    ->orWhereHas('interviews.interviewers', function ($subQuery) {
                        $subQuery->where('id_user', Auth::id());
                    })
                    ->orWhereHas('userinterviews.user_interviewers', function ($subQuery) {
                        $subQuery->where('id_user', Auth::id());
                    })
                    ->orWhereDoesntHave('interviews') // Tampilkan data tanpa relasi interviews
                    ->orWhereDoesntHave('userinterviews'); // Tampilkan data tanpa userinterviews

                    if ($status) {
                        $query->where('status', $status);
                    }
                });
        } elseif (Auth::user()->role === 'interviewer') {
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews', 'userinterviews.user_interviewers', 'userAnswer',
            'userAnswer.question.form',
            'userAnswer.question.answers',
            'userAnswer.answer')
                ->where(function ($query) use ($status) {
                    $query->whereHas('userinterviews.user_interviewers', function ($subQuery) {
                        $subQuery->whereNotIn('role', ['super_admin', 'hiring_manager', 'recruiter']);
                    });

                    if ($status) {
                        $query->where('status', $status);
                    }
                })
                ->whereHas('userinterviews.user_interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                });
        } else {
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews.interviewers', 'userinterviews.user_interviewers', 'userAnswer',
            'userAnswer.question.form',
            'userAnswer.question.answers',
            'userAnswer.answer')
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                });
        }

        if ($hrjobId) {
            $userhrjobs = $userhrjobs->where('id_job', $hrjobId);
        }

        if ($startDate) {
            $userhrjobs->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $userhrjobs->whereDate('created_at', '<=', $endDate);
        }

        $userhrjobs = $userhrjobs->get();

        // Tampilkan ke view dengan semua status untuk digunakan di topbar
        $statuses = [
            'applicant', 'shortlist', 'phone_screen', 'hr_interview',
            'user_interview', 'skill_test', 'reference_check',
            'offering', 'rejected', 'hired'
        ];

        $userhrjobss = UserHrJob::with('user', 'hrjob')->get();
        $hrjobss = Hrjob::all();
        $userss = User::where('role', '=', 'applicant')->get();
        $users = User::where('role', '!=', 'applicant')->get();
        $userIds = $userhrjobss->pluck('id_user')->unique();
        $references = Reference::whereIn('id_user', $userIds)->get();

        return view('admin.userhrjob.index', compact('userhrjobs', 'userhrjobss', 'hrjobss', 'statuses', 'status', 'users', 'userss', 'references'));
    }

    public function getInterviewModal(Request $request)
    {
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.userhrjob.interviewmodal', [
            'users' => $users,
        ]);
    }

    public function getUserInterviewModal(Request $request)
    {
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.userhrjob.userinterviewmodal', [
            'users' => $users,
        ]);
    }

    public function storeUserHrjob(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('toast_type', 'failed');
                session()->flash('toast_message', 'You are not authorized to create this user job.');
                return back()->withInput();
            }

            $validated = $request->validate([
                'id_job' => 'required|exists:hrjobs,id',
                'id_user' => 'required|exists:users,id',
                'status' => 'required|in:applicant,shortlist,phone_screen,hr_interview,user_interview,skill_test,reference_check,offering,rejected,hired',
                'salary_expectation' => 'required',
                'availability' => 'required|in:immediately,<1_month_notice,1_month_notice,>1_month_notice',
            ]);

            UserHrjob::create([
                'id_job' => $request->id_job,
                'id_user' => $request->id_user,
                'status' => $request->status,
                'salary_expectation' => $request->salary_expectation,
                'availability' => $request->availability,
            ]);

            session()->flash('toast_type', 'success');
            session()->flash('toast_message', 'User Job created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            session()->flash('toast_type', 'failed');
            session()->flash('toast_message', implode(' ', $errors));
        } catch (\Exception $e) {
            session()->flash('toast_type', 'failed');
            session()->flash('toast_message', 'An unexpected error occurred. Please try again.');
        }

        return back()->withInput();
    }

    public function updateUserHrjob(Request $request, $id)
    {
        $userhrjob = UserHrjob::findOrFail($id);
        $previousStatus = $userhrjob->status;

        try {

            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to update this user job.'], 403);
            }

            $validated = $request->validate([
                'id_job' => 'required|exists:hrjobs,id',
                'id_user' => 'required|exists:users,id',
                'status' => 'required|in:applicant,shortlist,phone_screen,hr_interview,user_interview,skill_test,reference_check,offering,rejected,hired',
                'salary_expectation' => 'required',
                'availability' => 'required|in:immediately,<1_month_notice,1_month_notice,>1_month_notice',
            ]);

            $userhrjob->id_job = $validated['id_job'];
            $userhrjob->id_user = $validated['id_user'];
            $userhrjob->status = $validated['status'];
            $userhrjob->salary_expectation = $validated['salary_expectation'];
            $userhrjob->availability = $validated['availability'];

            $userhrjob->save();

            if ($userhrjob->status !== $request->status) {
                \App\Models\UserHrjobStatusHistory::create([
                    'id_user_job' => $userhrjob->id,
                    'status' => $userhrjob->status,
                ]);
            }

            $status = $request->status;

            if ($request->status === 'hr_interview') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Status updated successfully!',
                    'modalType' => 'hr_interview',
                    'modalData' => [
                        'userJobId' => $id,
                        'userJobName' => $userhrjob->user->fullname,
                    ],
                    'updatedRow' => [
                        'id' => $userhrjob->id,
                        'status' => $userhrjob->status,
                        'updated_at' => $userhrjob->updated_at->format('Y-m-d H:i:s'),
                        'previous_status' => $previousStatus,
                    ],
                ]);
            }


            if ($request->status === 'user_interview') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Status updated successfully!',
                    'modalType' => 'user_interview',
                    'modalData' => [
                        'userJobId' => $id,
                        'userJobName' => $userhrjob->user->fullname,
                    ],
                    'updatedRow' => [
                        'id' => $userhrjob->id,
                        'status' => $userhrjob->status,
                        'updated_at' => $userhrjob->updated_at->format('Y-m-d H:i:s'),
                        'previous_status' => $previousStatus,
                    ],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User Job updated successfully!',
                'updatedRow' => [
                    'id' => $userhrjob->id,
                    'job_name' => $userhrjob->hrjob->job_name,
                    'fullname' => $userhrjob->user->fullname,
                    'status' => $userhrjob->status,
                    'salary_expectation' => $userhrjob->salary_expectation,
                    'availability' => $userhrjob->availability,
                    'updated_at' => $userhrjob->updated_at->format('Y-m-d H:i:s'),
                    'previous_status' => $previousStatus,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            Log::error('Validation Error:', $e->errors());
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            return response()->json(['message' => implode(' ', $errors)], 422);
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            return response()->json(['message' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {

            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to update this user job.'], 403);
            }

            $validated = $request->validate([
                'status' => 'required|in:applicant,shortlist,phone_screen,hr_interview,user_interview,skill_test,reference_check,offering,rejected,hired',
            ]);

            $userhrjob = UserHrjob::findOrFail($id);
            $previousStatus = $userhrjob->status;

            $userhrjob->status = $validated['status'];
            $userhrjob->save();

            if ($userhrjob->status !== $request->status) {
                \App\Models\UserHrjobStatusHistory::create([
                    'id_user_job' => $userhrjob->id,
                    'status' => $userhrjob->status,
                ]);
            }

            $status = $request->status;

            if ($request->status === 'hr_interview') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Status updated successfully!',
                    'modalType' => 'hr_interview',
                    'modalData' => [
                        'userJobId' => $id,
                        'userJobName' => $userhrjob->user->fullname,
                    ],
                    'updatedRow' => [
                        'id' => $userhrjob->id,
                        'status' => $userhrjob->status,
                        'updated_at' => $userhrjob->updated_at->format('Y-m-d H:i:s'),
                        'previous_status' => $previousStatus,
                    ],
                    // 'redirectUrl' => route('getUserHrjob', ['status' => 'hr_interview']),
                ]);
            }


            if ($request->status === 'user_interview') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Status updated successfully!',
                    'modalType' => 'user_interview',
                    'modalData' => [
                        'userJobId' => $id,
                        'userJobName' => $userhrjob->user->fullname,
                    ],
                    'updatedRow' => [
                        'id' => $userhrjob->id,
                        'status' => $userhrjob->status,
                        'updated_at' => $userhrjob->updated_at->format('Y-m-d H:i:s'),
                        'previous_status' => $previousStatus,
                    ],
                    // 'redirectUrl' => route('getUserHrjob', ['status' => 'user_interview']),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'updatedRow' => [
                    'id' => $userhrjob->id,
                    'status' => $userhrjob->status,
                    'updated_at' => $userhrjob->updated_at->format('Y-m-d H:i:s'),
                    'previous_status' => $previousStatus,
                ],
            ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Gabungkan semua pesan validasi
                $errors = [];
                foreach ($e->errors() as $fieldErrors) {
                    $errors = array_merge($errors, $fieldErrors);
                }
                return response()->json(['message' => implode(' ', $errors)], 422);
            } catch (\Exception $e) {
                return response()->json(['message' => 'An error occurred while updating the status.'], 500);
            }
    }

    public function destroyUserHrjob($id)
    {
        $userhrjob = UserHrjob::findOrFail($id);
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to delete this user job.'], 403);
            }

            $userhrjob->delete();
            return response()->json(['message' => 'User Job deleted successfully.'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            return response()->json(['message' => implode(' ', $errors)], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the history.'], 500);
        }
    }

    public function bulkRejectStatus(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['success' => false, 'message' => 'You are not authorized to bulk reject this user job.']);
            }

            // Validasi input
            $validated = $request->validate([
                'selected_jobs' => 'required|array',
                'selected_jobs.*' => 'exists:user_hrjobs,id',
            ]);

            // Nonaktifkan event updated
            UserHrjob::withoutEvents(function () use ($request) {
                $userHrjobs = UserHrjob::whereIn('id', $request->selected_jobs)->get();

                $statusHistories = [];
                $currentTimestamp = now();

                $userHrjobs->each(function ($userHrjob) use (&$statusHistories, $currentTimestamp) {
                    $userHrjob->update(['status' => 'rejected']); // Perbarui status

                    $statusHistories[] = [
                        'id_user_job' => $userHrjob->id,
                        'status' => 'rejected',
                        'created_at' => $currentTimestamp,
                        'updated_at' => $currentTimestamp,
                    ];
                });

                // Simpan riwayat status ke tabel UserHrjobStatusHistory
                \App\Models\UserHrjobStatusHistory::insert($statusHistories);
            });

            return response()->json(['success' => true, 'message' => 'Selected jobs successfully rejected.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            return response()->json(['success' => false, 'message' => implode(' ', $errors)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again.']);
        }
    }

    public function exportUserHrjob()
    {
        $status = request('status');
        // Ambil data dari model UserHrjob berdasarkan status
        $userhrjobs = UserHrjob::with(['hrjob', 'user'])
            ->where('status', $status)
            ->get();

        // Buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tambahkan header untuk file Excel
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Applicant Name');
        $sheet->setCellValue('C1', 'Job Name');
        $sheet->setCellValue('D1', 'Location');
        $sheet->setCellValue('E1', 'Outlet');
        $sheet->setCellValue('F1', 'Job Salary');
        $sheet->setCellValue('G1', 'Salary Expectation');
        $sheet->setCellValue('H1', 'Applied At');
        $sheet->setCellValue('I1', 'Availability');
        $sheet->setCellValue('J1', 'Status');

        // Membuat header bold
        $headerRange = 'A1:J1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data dari database ke dalam file Excel
        $rowNumber = 2; // Baris pertama adalah header
        foreach ($userhrjobs as $userhrjob) {

            $sheet->setCellValue('A' . $rowNumber, $userhrjob->id);
            $sheet->setCellValue('B' . $rowNumber, $userhrjob->user->fullname ?? 'No Applicant');
            $sheet->setCellValue('C' . $rowNumber, $userhrjob->hrjob->job_name ?? 'No Job');
            $sheet->setCellValue('D' . $rowNumber, $userhrjob->hrjob->city->city_name  ?? 'No Location');
            $sheet->setCellValue('E' . $rowNumber, $userhrjob->hrjob->outlet->outlet_name ?? 'No Outlet');
            $sheet->setCellValue('F' . $rowNumber, $userhrjob->hrjob->price ?? 'No Job Salary');
            $sheet->setCellValue('G' . $rowNumber, $userhrjob->salary_expectation ?? 'No Salary Expectation');
            $sheet->setCellValue('H' . $rowNumber, $userhrjob->created_at ?? 'No Applied');
            $sheet->setCellValue('I' . $rowNumber, $userhrjob->availability ?? 'No Availability');
            $sheet->setCellValue('J' . $rowNumber, $userhrjob->status ?? 'No Status');

            $rowNumber++;
        }

        // Simpan file dan buat response untuk download
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Konfigurasi headers
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="user_job.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportdateUserHrjob(Request $request)
    {
        // Validasi input tanggal
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $status = request('status','all');
        // Filter data berdasarkan rentang tanggal dan status
        $userhrjobs = UserHrjob::with(['hrjob', 'user'])
            ->whereBetween('created_at', [
                Carbon::parse($validated['start_date'])->startOfDay(),
                Carbon::parse($validated['end_date'])->endOfDay()
            ])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->get();

            // \Log::info('Tanggal yang digunakan untuk filter', [
            //     'start_date' => Carbon::parse($validated['start_date'])->startOfDay(),
            //     'end_date' => Carbon::parse($validated['end_date'])->endOfDay(),
            //     'status' => $status,
            // ]);

            // \Log::info('Data hasil query', $userhrjobs->toArray());


        // Membuat spreadsheet dan isi data
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tambahkan header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Applicant Name');
        $sheet->setCellValue('C1', 'Job Name');
        $sheet->setCellValue('D1', 'Location');
        $sheet->setCellValue('E1', 'Outlet');
        $sheet->setCellValue('F1', 'Job Salary');
        $sheet->setCellValue('G1', 'Salary Expectation');
        $sheet->setCellValue('H1', 'Applied At');
        $sheet->setCellValue('I1', 'Availability');
        $sheet->setCellValue('J1', 'Status');

        // Membuat header bold
        $headerRange = 'A1:J1'; // Range dari header
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data
        $rowNumber = 2;
        foreach ($userhrjobs as $userhrjob) {
            $sheet->setCellValue('A' . $rowNumber, $userhrjob->id);
            $sheet->setCellValue('B' . $rowNumber, $userhrjob->user->fullname ?? 'No Applicant');
            $sheet->setCellValue('C' . $rowNumber, $userhrjob->hrjob->job_name ?? 'No Job');
            $sheet->setCellValue('D' . $rowNumber, $userhrjob->hrjob->city->city_name  ?? 'No Location');
            $sheet->setCellValue('E' . $rowNumber, $userhrjob->hrjob->outlet->outlet_name ?? 'No Outlet');
            $sheet->setCellValue('F' . $rowNumber, $userhrjob->hrjob->price ?? 'No Job Salary');
            $sheet->setCellValue('G' . $rowNumber, $userhrjob->salary_expectation ?? 'No Salary Expectation');
            $sheet->setCellValue('H' . $rowNumber, $userhrjob->created_at ?? 'No Applied');
            $sheet->setCellValue('I' . $rowNumber, $userhrjob->availability ?? 'No Availability');
            $sheet->setCellValue('J' . $rowNumber, $userhrjob->status ?? 'No Status');
            $rowNumber++;
        }

        // Simpan file dan buat response untuk download
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Konfigurasi headers
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="user_job_date.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
