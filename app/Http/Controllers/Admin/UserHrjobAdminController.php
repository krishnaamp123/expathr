<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserHrjob;
use App\Models\Hrjob;
use App\Models\User;
use App\Models\Interview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserHrjobAdminController extends Controller
{

    public function getUserHrjob(Request $request)
    {
        // Ambil parameter status, start_date, dan end_date dari URL
        $status = $request->query('status');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Tentukan logika penyaringan berdasarkan role pengguna
        if (Auth::user()->role === 'hiring_manager') {
            // Filter untuk hiring_manager, tidak dapat melihat data dari super_admin
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews', 'userinterviews', 'answers')
                ->where(function ($query) use ($status) {
                    $query->whereHas('interviews', function ($subQuery) {
                        $subQuery->whereHas('user', function ($nestedQuery) {
                            $nestedQuery->where('role', '!=', 'super_admin');
                        });
                    })
                    ->orWhereDoesntHave('interviews'); // Tampilkan data tanpa relasi interviews juga

                    // Tambahkan filter status jika ada
                    if ($status) {
                        $query->where('status', $status);
                    }
                });
        } elseif (Auth::user()->role === 'recruiter') {
            // Filter untuk recruiter, tidak dapat melihat data dari super_admin dan hiring_manager
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews', 'userinterviews', 'answers')
                ->where(function ($query) use ($status) {
                    $query->whereHas('interviews', function ($subQuery) {
                        $subQuery->whereHas('user', function ($nestedQuery) {
                            $nestedQuery->whereNotIn('role', ['super_admin', 'hiring_manager']);
                        });
                    })
                    ->orWhereDoesntHave('interviews'); // Tampilkan data tanpa relasi interviews juga

                    // Tambahkan filter status jika ada
                    if ($status) {
                        $query->where('status', $status);
                    }
                });
        } else {
            // Jika pengguna bukan recruiter atau hiring_manager, tampilkan semua data
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews', 'userinterviews', 'answers')
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                });
        }

        // Tambahkan filter berdasarkan tanggal jika diberikan
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
        $userss= User::where('role', '=', 'applicant')->get();
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.userhrjob.index', compact('userhrjobs', 'userhrjobss', 'hrjobss', 'statuses', 'status', 'users', 'userss'));
    }

    public function storeUserHrjob(Request $request)
    {
        try {
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
            $validated = $request->validate([
                'status' => 'required|in:applicant,shortlist,phone_screen,hr_interview,user_interview,skill_test,reference_check,offering,rejected,hired',
            ]);

            $userhrjob = UserHrjob::findOrFail($id);
            $userhrjob->status = $request->status;
            $userhrjob->save();

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
            $validated = $request->validate([
                'selected_jobs' => 'required|array',
                'selected_jobs.*' => 'exists:user_hrjobs,id',
            ]);

            // Update status menjadi 'rejected'
            UserHrjob::whereIn('id', $request->selected_jobs)->update(['status' => 'rejected']);

            // Simpan pesan sukses ke dalam session
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
