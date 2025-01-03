<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserHrjob;
use App\Models\Hrjob;
use App\Models\User;
use App\Models\Interview;
use App\Models\UserHrjobStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews.interviewers', 'userinterviews.user_interviewers', 'answers')
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
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews.interviewers', 'userinterviews.user_interviewers', 'answers')
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
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews', 'userinterviews.user_interviewers', 'answers')
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
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews.interviewers', 'userinterviews.user_interviewers', 'answers')
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

        return view('admin.userhrjob.index', compact('userhrjobs', 'userhrjobss', 'hrjobss', 'statuses', 'status', 'users', 'userss'));
    }


    public function storeUserHrjob(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('failed', 'You are not authorized to create this user job.');
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

            session()->flash('success', 'User Job created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            session()->flash('failed', implode(' ', $errors));
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            session()->flash('failed', 'An unexpected error occurred. Please try again.');
        }

        return back()->withInput();
    }

    public function updateUserHrjob(Request $request, $id)
    {
        $userhrjob = UserHrjob::findOrFail($id);

        try {

            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('failed', 'You are not authorized to update this user job.');
                return back()->withInput();
            }

            $validated = $request->validate([
                'id_job' => 'required|exists:hrjobs,id',
                'id_user' => 'required|exists:users,id',
                'status' => 'required|in:applicant,shortlist,phone_screen,hr_interview,user_interview,skill_test,reference_check,offering,rejected,hired',
                'salary_expectation' => 'required',
                'availability' => 'required|in:immediately,<1_month_notice,1_month_notice,>1_month_notice',
            ]);

            $userhrjob->id_job = $request->id_job;
            $userhrjob->id_user = $request->id_user;
            $userhrjob->status = $request->status;
            $userhrjob->salary_expectation = $request->salary_expectation;
            $userhrjob->availability = $request->availability;

            $userhrjob->save();

            if ($userhrjob->status !== $request->status) {
                \App\Models\UserHrjobStatusHistory::create([
                    'id_user_job' => $userhrjob->id,
                    'status' => $userhrjob->status,
                ]);
            }

            $status = $request->status;

            if ($request->status === 'hr_interview') {
                return redirect()->route('getUserHrjob', ['status' => 'hr_interview'])
                    ->with('showModal', true)
                    ->with('userJobId', $id)
                    ->with('userJobName', $userhrjob->user->fullname);
            }

            if ($status === 'user_interview') {
                return redirect()->route('getUserHrjob', ['status' => 'user_interview'])
                    ->with('showuserModal', true)
                    ->with('userJobId', $id)
                    ->with('userJobName', $userhrjob->user->fullname);
            }

            session()->flash('success', 'User Job updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            session()->flash('failed', implode(' ', $errors));
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            session()->flash('failed', 'An unexpected error occurred. Please try again.');
        }

        return back()->withInput();
    }

    public function updateStatus(Request $request, $id)
    {
        try {

            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('failed', 'You are not authorized to update this user job.');
                return back()->withInput();
            }

            $validated = $request->validate([
                'status' => 'required|in:applicant,shortlist,phone_screen,hr_interview,user_interview,skill_test,reference_check,offering,rejected,hired',
            ]);

            $userhrjob = UserHrjob::findOrFail($id);


            $userhrjob->status = $request->status;
            $userhrjob->save();

            if ($userhrjob->status !== $request->status) {
                \App\Models\UserHrjobStatusHistory::create([
                    'id_user_job' => $userhrjob->id,
                    'status' => $userhrjob->status,
                ]);
            }

            $status = $request->status;

            if ($request->status === 'hr_interview') {
                return redirect()->route('getUserHrjob', ['status' => 'hr_interview'])
                    ->with('showModal', true)
                    ->with('userJobId', $id)
                    ->with('userJobName', $userhrjob->user->fullname);
            }

            if ($status === 'user_interview') {
                return redirect()->route('getUserHrjob', ['status' => 'user_interview'])
                    ->with('showuserModal', true)
                    ->with('userJobId', $id)
                    ->with('userJobName', $userhrjob->user->fullname);
            }

            session()->flash('success', 'Status updated successfully');
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Gabungkan semua pesan validasi
                $errors = [];
                foreach ($e->errors() as $fieldErrors) {
                    $errors = array_merge($errors, $fieldErrors);
                }
                session()->flash('failed', implode(' ', $errors));
            } catch (\Exception $e) {
                // Pesan error untuk kesalahan umum
                session()->flash('failed', 'An unexpected error occurred. Please try again.');
            }

        return back()->withInput();
    }

    public function destroyUserHrjob($id)
    {
        $userhrjob = UserHrjob::findOrFail($id);
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('failed', 'You are not authorized to delete this user job.');
                return back()->withInput();
            }

            $userhrjob->delete();
            session()->flash('success', 'User Job deleted successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            session()->flash('failed', implode(' ', $errors));
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            session()->flash('failed', 'An unexpected error occurred. Please try again.');
        }

        return back()->withInput();
    }

    public function bulkRejectStatus(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('failed', 'You are not authorized to bulk reject this user job.');
                return back()->withInput();
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

            session()->flash('success', 'Selected jobs successfully rejected.');
            return response()->json(['success' => true, 'message' => 'Selected jobs successfully rejected.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            session()->flash('failed', implode(' ', $errors));
            return response()->json(['success' => false, 'message' => implode(' ', $errors)]);
        } catch (\Exception $e) {
            session()->flash('failed', 'An unexpected error occurred. Please try again.');
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again.']);
        }
    }
}
