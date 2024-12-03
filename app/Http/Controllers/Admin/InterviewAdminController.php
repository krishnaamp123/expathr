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
        ]);

        Interview::create([
            'id_user_job' => $request->id_user_job,
            'id_user' => $request->id_user,
            'interview_date' => $request->interview_date,
            'time' => $request->time,
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
        ]);

        $interview->id_user_job = $request->id_user_job;
        $interview->id_user = $request->id_user;
        $interview->interview_date = $request->interview_date;
        $interview->time = $request->time;

        $interview->save();

        return redirect()->route('getInterview')->with('message', 'Interview Updated Successfully');
    }

    public function destroyInterview($id)
    {
        $interview = Interview::findOrFail($id);

        $interview->delete();

        return redirect()->route('getInterview')->with('message', 'Interview deleted successfully');
    }
}
