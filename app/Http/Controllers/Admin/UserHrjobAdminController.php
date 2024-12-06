<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserHrjob;
use App\Models\Hrjob;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserHrjobAdminController extends Controller
{
    public function getUserHrjob(Request $request)
    {
        // Ambil parameter status dari URL
        $status = $request->query('status');

        // Filter data berdasarkan status jika parameter ada
        $userhrjobs = UserHrjob::with('hrjob', 'user')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();

        // Tampilkan ke view dengan semua status untuk digunakan di topbar
        $statuses = [
            'applicant', 'shortlist', 'phone_screen', 'hr_interview',
            'user_interview', 'skill_test', 'reference_check',
            'offering', 'rejected', 'hired'
        ];

        return view('admin.userhrjob.index', compact('userhrjobs', 'statuses', 'status'));
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

        return redirect()->route('getUserHrjob')->with('message', 'Status updated successfully');
    }

    public function destroyUserHrjob($id)
    {
        $userhrjob = UserHrjob::findOrFail($id);

        $userhrjob->delete();

        return redirect()->route('getUserHrjob')->with('message', 'User Job deleted successfully');
    }

}
