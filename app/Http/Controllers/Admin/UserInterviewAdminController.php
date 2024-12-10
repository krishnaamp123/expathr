<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserInterview;
use App\Models\UserHrjob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserInterviewAdminController extends Controller
{
    public function getUserInterview()
    {
        if (Auth::user()->role === 'hiring_manager') {
            $userinterviews = UserInterview::with('userHrjob', 'user')
                ->whereHas('user', function ($query) {
                    $query->where('role', '!=', 'super_admin');
                })
                ->get();
        } elseif (Auth::user()->role === 'recruiter') {
            $userinterviews = UserInterview::with('userHrjob', 'user')
                ->whereHas('user', function ($query) {
                    $query->whereNotIn('role', ['super_admin', 'hiring_manager']);
                })
                ->get();
        } else {
            $userinterviews = UserInterview::with('userHrjob', 'user')->get();
        }

        return view('admin.userinterview.index', compact('userinterviews'));
    }

    public function addUserInterview()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserInterview')->with('error', 'You are not authorized to create interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.userinterview.store', compact('userhrjobs', 'users'));
    }


    public function storeUserInterview(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserInterview')->with('error', 'You are not authorized to create interview');
        }

        // Prevent hiring_manager from adding interviews for super_admin
        $user = User::findOrFail($request->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getUserInterview')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $validated = $request->validate([
            'id_user_job' => 'required',
            'id_user' => 'required',
            'interview_date' => 'nullable',
            'time' => 'nullable',
            'location' => 'nullable',
            'link' => 'nullable',
        ]);

        UserInterview::create([
            'id_user_job' => $request->id_user_job,
            'id_user' => $request->id_user,
            'interview_date' => $request->interview_date,
            'time' => $request->time,
            'location' => $request->location,
            'link' => $request->link,
        ]);

        return redirect()->route('getUserInterview')->with('message', 'Interview Scheduled Successfully');
    }

    public function editUserInterview($id)
    {
        $userinterview = UserInterview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserInterview')->with('error', 'You are not authorized to edit interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.userinterview.update', compact('userinterview', 'userhrjobs', 'users'));
    }

    public function updateUserInterview(Request $request, $id)
    {
        $userinterview = UserInterview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserInterview')->with('error', 'You are not authorized to edit interview');
        }

        // Prevent hiring_manager from updating interviews for super_admin
        $user = User::findOrFail($request->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getUserInterview')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $validated = $request->validate([
            'id_user_job' => 'required',
            'id_user' => 'required',
            'interview_date' => 'nullable',
            'time' => 'nullable',
            'location' => 'nullable',
            'link' => 'nullable',

        ]);

        $userinterview->id_user_job = $request->id_user_job;
        $userinterview->id_user = $request->id_user;
        $userinterview->interview_date = $request->interview_date;
        $userinterview->time = $request->time;
        $userinterview->location = $request->location;
        $userinterview->link = $request->link;

        $userinterview->save();

        return redirect()->route('getUserInterview')->with('message', 'Interview Updated Successfully');
    }

    public function editUserRating($id)
    {
        $userinterview = UserInterview::findOrFail($id);
        return view('admin.userinterview.rating', compact('userinterview'));
    }

    public function updateUserRating(Request $request, $id)
    {
        $userinterview = UserInterview::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $userinterview->rating = $validated['rating'];
        $userinterview->comment = $validated['comment'];

        $userinterview->save();

        return redirect()->route('getUserInterview')->with('message', 'Rating Updated Successfully');
    }


    public function destroyUserInterview($id)
    {
        $userinterview = UserInterview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserInterview')->with('error', 'You are not authorized to delete interview.');
        }

        // Prevent hiring_manager from deleting interviews for super_admin
        $user = User::findOrFail($userinterview->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getUserInterview')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $userinterview->delete();

        return redirect()->route('getUserInterview')->with('message', 'Interview deleted successfully');
    }
}
