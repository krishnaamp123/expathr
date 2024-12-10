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
        // Ambil parameter status dari URL
        $status = $request->query('status');

        // Tentukan logika penyaringan berdasarkan role pengguna
        if (Auth::user()->role === 'hiring_manager') {
            // Filter untuk hiring_manager, tidak dapat melihat data dari super_admin
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews')
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
                })
                ->get();
        } elseif (Auth::user()->role === 'recruiter') {
            // Filter untuk recruiter, tidak dapat melihat data dari super_admin dan hiring_manager
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews')
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
                })
                ->get();
        } else {
            // Jika pengguna bukan recruiter atau hiring_manager, tampilkan semua data
            $userhrjobs = UserHrjob::with('hrjob', 'user', 'interviews')
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->get();
        }

        // Tampilkan ke view dengan semua status untuk digunakan di topbar
        $statuses = [
            'applicant', 'shortlist', 'phone_screen', 'hr_interview',
            'user_interview', 'skill_test', 'reference_check',
            'offering', 'rejected', 'hired'
        ];

        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.userhrjob.index', compact('userhrjobs', 'statuses', 'status', 'users'));
    }

    public function addUserHrjob()
    {
        $hrjob = Hrjob::all();
        $user = User::all();
        return view('admin.userhrjob.store', compact('hrjob', 'user'));
    }

    public function storeUserHrjob(Request $request)
    {
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

        return redirect()->route('getUserHrjob')->with('message', 'Data Added Successfully');
    }

    public function editUserHrjob($id)
    {
        $userhrjob = UserHrjob::findOrFail($id);
        $hrjob = Hrjob::all();
        $user = User::all();

        return view('admin.userhrjob.update', compact('userhrjob', 'hrjob', 'user'));
    }

    public function updateUserHrjob(Request $request, $id)
    {
        $userhrjob = UserHrjob::findOrFail($id);

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

        return redirect()->route('getUserHrjob')->with('message', 'User Job Updated Successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:applicant,shortlist,phone_screen,hr_interview,user_interview,skill_test,reference_check,offering,rejected,hired',
        ]);

        $userhrjob = UserHrjob::findOrFail($id);
        $userhrjob->status = $request->status;
        $userhrjob->save();

        if ($request->status === 'hr_interview') {
            return redirect()->route('getUserHrjob', ['status' => 'hr_interview'])
                ->with('showModal', true)
                ->with('userJobId', $id);
        }

        return redirect()->route('getUserHrjob')->with('message', 'Status updated successfully');
    }

    public function destroyUserHrjob($id)
    {
        $userhrjob = UserHrjob::findOrFail($id);

        $userhrjob->delete();

        return redirect()->route('getUserHrjob')->with('message', 'User Job deleted successfully');
    }

    // INI UNTUK DI HALAMAN USERHRJOB

    public function addUserHrjobInterview()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getInterview')->with('error', 'You are not authorized to create interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.interview.store', compact('userhrjobs', 'users'));
    }


    public function storeUserHrjobInterview(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to create interview');
        }

        // Prevent hiring_manager from adding interviews for super_admin
        $user = User::findOrFail($request->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getUserHrjob')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $validated = $request->validate([
            'id_user_job' => 'required',
            'id_user' => 'required',
            'interview_date' => 'nullable',
            'time' => 'nullable',
            'location' => 'nullable',
            'link' => 'nullable',
        ]);

        Interview::create([
            'id_user_job' => $request->id_user_job,
            'id_user' => $request->id_user,
            'interview_date' => $request->interview_date,
            'time' => $request->time,
            'location' => $request->location,
            'link' => $request->link,
        ]);

        return redirect()->route('getUserHrjob')->with('message', 'Interview Scheduled Successfully');
    }

    public function editUserHrjobInterview($id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to edit interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.interview.update', compact('interview', 'userhrjobs', 'users'));
    }

    public function updateUserHrjobInterview(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to edit interview');
        }

        // Prevent hiring_manager from updating interviews for super_admin
        $user = User::findOrFail($request->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getUserHrjob')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $validated = $request->validate([
            'id_user_job' => 'required',
            'id_user' => 'required',
            'interview_date' => 'nullable',
            'time' => 'nullable',
            'location' => 'nullable',
            'link' => 'nullable',

        ]);

        $interview->id_user_job = $request->id_user_job;
        $interview->id_user = $request->id_user;
        $interview->interview_date = $request->interview_date;
        $interview->time = $request->time;
        $interview->location = $request->location;
        $interview->link = $request->link;

        $interview->save();

        return redirect()->route('getUserHrjob')->with('message', 'Interview Updated Successfully');
    }

    public function editUserHrjobRating($id)
    {
        $interview = Interview::findOrFail($id);
        return view('admin.interview.rating', compact('interview'));
    }

    public function updateUserHrjobRating(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $interview->rating = $validated['rating'];
        $interview->comment = $validated['comment'];

        $interview->save();

        return redirect()->route('getUserHrjob')->with('message', 'Rating Updated Successfully');
    }


    public function destroyUserHrjobInterview($id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to delete interview.');
        }

        // Prevent hiring_manager from deleting interviews for super_admin
        $user = User::findOrFail($interview->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getUserHrjob')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $interview->delete();

        return redirect()->route('getUserHrjob')->with('message', 'Interview deleted successfully');
    }

}
