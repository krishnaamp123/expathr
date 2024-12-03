<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Form;
use App\Models\UserHrjob;
use App\Models\Question;

class AnswerAdminController extends Controller
{
    public function getAnswer()
    {
        $answers = Answer::with('userHrjob', 'form')->get();
        $groupedAnswers = $answers->groupBy(['userhrjob.id', 'form.id']);
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

        $answer->delete();

        return redirect()->route('getAnswer')->with('message', 'Answer deleted successfully');
    }
}
