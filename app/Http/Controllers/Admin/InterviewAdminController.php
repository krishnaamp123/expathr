<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\UserHrjob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InterviewAdminController extends Controller
{
    public function getInterview()
    {
        if (Auth::user()->role === 'hiring_manager') {
            // Filter wawancara untuk hiring_manager
            $interviews = Interview::with('userHrjob', 'user')
                ->whereHas('user', function ($query) {
                    $query->where('role', '!=', 'super_admin');
                })
                ->get();
        } elseif (Auth::user()->role === 'recruiter') {
            // Filter wawancara untuk recruiter
            $interviews = Interview::with('userHrjob', 'user')
                ->whereHas('user', function ($query) {
                    $query->whereNotIn('role', ['super_admin', 'hiring_manager']);
                })
                ->get();
        } else {
            $interviews = Interview::with('userHrjob', 'user')->get();
        }

        return view('admin.interview.index', compact('interviews'));
    }

    public function addInterview()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getInterview')->with('error', 'You are not authorized to create interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.interview.store', compact('userhrjobs', 'users'));
    }


    public function storeInterview(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getInterview')->with('error', 'You are not authorized to create interview');
        }

        // Prevent hiring_manager from adding interviews for super_admin
        $user = User::findOrFail($request->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getInterview')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $validated = $request->validate([
            'id_user_job' => 'required',
            'id_user' => 'required',
            'interview_date' => 'required',
            'time' => 'required',
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

        return redirect()->route('getInterview')->with('message', 'Data Added Successfully');
    }

    public function editInterview($id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getInterview')->with('error', 'You are not authorized to edit interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.interview.update', compact('interview', 'userhrjobs', 'users'));
    }

    public function updateInterview(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getInterview')->with('error', 'You are not authorized to edit interview');
        }

        // Prevent hiring_manager from updating interviews for super_admin
        $user = User::findOrFail($request->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getInterview')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $validated = $request->validate([
            'id_user_job' => 'required',
            'id_user' => 'required',
            'interview_date' => 'required',
            'time' => 'required',
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

        return redirect()->route('getInterview')->with('message', 'Interview Updated Successfully');
    }

    public function editRating($id)
    {
        $interview = Interview::findOrFail($id);
        return view('admin.interview.rating', compact('interview'));
    }

    public function updateRating(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $interview->rating = $validated['rating'];
        $interview->comment = $validated['comment'];

        $interview->save();

        return redirect()->route('getInterview')->with('message', 'Rating Updated Successfully');
    }


    public function destroyInterview($id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getInterview')->with('error', 'You are not authorized to delete interview.');
        }

        // Prevent hiring_manager from deleting interviews for super_admin
        $user = User::findOrFail($interview->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getInterview')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $interview->delete();

        return redirect()->route('getInterview')->with('message', 'Interview deleted successfully');
    }
}
