<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Form;
use App\Models\UserHrjob;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class AnswerAdminController extends Controller
{

    public function getAnswer()
    {
        $answers = Answer::with(['userHrjob', 'form'])
            ->when(Auth::user()->role === 'hiring_manager', function ($query) {
                // Exclude data where associated hrjob has a super_admin user
                $query->whereHas('userHrjob', function ($subQuery) {
                    $subQuery->whereHas('hrjob.user', function ($q) {
                        $q->where('role', '!=', 'super_admin');
                    });
                });
            })
            ->when(Auth::user()->role === 'recruiter', function ($query) {
                // Show only answers associated with hrjob that the recruiter owns
                $query->whereHas('userHrjob', function ($subQuery) {
                    $subQuery->whereHas('hrjob', function ($q) {
                        $q->where('id_user', Auth::id());
                    });
                });
            })
            ->get();

        $groupedAnswers = $answers->groupBy(['userHrjob.id', 'form.id']);
        return view('admin.answer.index', compact('answers', 'groupedAnswers'));
    }

    public function addAnswer()
    {
        $userhrjobs = UserHrjob::all();
        $forms = Form::all();

        return view('admin.answer.store', compact('userhrjobs', 'forms'));
    }


    public function storeAnswer(Request $request)
    {
        $validated = $request->validate([
            'id_user_job' => 'required|exists:user_hrjobs,id',
            'answers.*.id_form' => 'required|exists:forms,id',
            'answers.*.answer' => 'required|string',
        ]);

        foreach ($validated['answers'] as $answerData) {
            Answer::create([
                'id_user_job' => $validated['id_user_job'],
                'id_form' => $answerData['id_form'],
                'answer' => $answerData['answer'],
            ]);
        }

        return redirect()->route('getAnswer')->with('message', 'Data Added Successfully');
    }

    public function editAnswer($id)
    {
        $answer = Answer::findOrFail($id);
        $userhrjobs = UserHrjob::all();
        $forms = Form::all();

        return view('admin.answer.update', compact('answer', 'form', 'userhrjobs'));
    }

    public function updateAnswer(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $validated = $request->validate([
            'id_user_job' => 'required',
            'id_form' => 'required',
            'answer' => 'required',
        ]);

        $answer->id_user_job = $request->id_user_job;
        $answer->id_form = $request->id_form;
        $answer->answer = $request->answer;

        $answer->save();

        return redirect()->route('getAnswer')->with('message', 'Answer Updated Successfully');
    }

    public function destroyAnswer($id)
    {
        $answer = Answer::findOrFail($id);

        $loggedInUser = Auth::user();

        // Jika user yang login adalah recruiter
        if ($loggedInUser->role === 'recruiter') {
            $selectedUser = $answer->userHrjob->hrjob->user; // Ambil user yang terkait dengan hrjob
            if ($selectedUser && in_array($selectedUser->role, ['super_admin', 'hiring_manager'])) {
                return redirect()->route('getAnswer')->with('error', 'You cannot manage answers for Super Admin & Hiring Manager.');
            }
        }

        // Jika user yang login adalah hiring_manager
        if ($loggedInUser->role === 'hiring_manager') {
            $selectedUser = $answer->userHrjob->hrjob->user; // Ambil user yang terkait dengan hrjob
            if ($selectedUser && $selectedUser->role === 'super_admin') {
                return redirect()->route('getAnswer')->with('error', 'You cannot manage answers for Super Admin.');
            }
        }

        // Hapus answer
        $answer->delete();

        return redirect()->route('getAnswer')->with('message', 'Answer deleted successfully');
    }
}
