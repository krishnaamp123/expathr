<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Hrjob;
use App\Models\Question;

class FormAdminController extends Controller
{
    public function getForm()
    {
        $forms = Form::with('hrjob', 'question')->get();
        return view('admin.form.index', compact('forms'));
    }

    public function addForm()
    {
        $hrjobs = Hrjob::all();
        $questions = Question::all();

        return view('admin.form.store', compact('hrjobs', 'questions'));
    }


    public function storeForm(Request $request)
    {
        $validated = $request->validate([
            'id_job' => 'required',
            'id_question' => 'required|array', // Ensure id_question is an array
            'id_question.*' => 'required|exists:questions,id', // Validate each question ID
        ]);

        foreach ($request->id_question as $questionId) {
            Form::create([
                'id_job' => $request->id_job,
                'id_question' => $questionId,
            ]);
        }

        return redirect()->route('getForm')->with('message', 'Questions added successfully to the job.');
    }

    public function editForm($id)
    {
        $form = Form::findOrFail($id);
        $hrjobs = Hrjob::all();
        $questions = Question::all();

        return view('admin.form.update', compact('form', 'hrjobs', 'questions'));
    }

    public function updateForm(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        $validated = $request->validate([
            'id_job' => 'required',
            'id_question' => 'required',
        ]);

        $form->id_job = $request->id_job;
        $form->id_question = $request->id_question;

        $form->save();

        return redirect()->route('getForm')->with('message', 'Form Updated Successfully');
    }

    public function destroyForm($id)
    {
        $form = Form::findOrFail($id);

        $form->delete();

        return redirect()->route('getForm')->with('message', 'Form deleted successfully');
    }
}
