<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\UserHrjob;
use App\Models\User;

class InterviewAdminController extends Controller
{
    public function getInterview()
    {
        $interviews = Interview::with('userHrjob', 'user')->get();
        return view('admin.interview.index', compact('interviews'));
    }

    public function addInterview()
    {
        $userhrjobs = UserHrjob::all();
        $users = User::all();

        return view('admin.interview.store', compact('userhrjobs', 'users'));
    }


    public function storeInterview(Request $request)
    {
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
        $userhrjobs = UserHrjob::all();
        $users = User::all();

        return view('admin.interview.update', compact('interview', 'userhrjobs', 'users'));
    }

    public function updateInterview(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

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

        $interview->delete();

        return redirect()->route('getInterview')->with('message', 'Interview deleted successfully');
    }
}