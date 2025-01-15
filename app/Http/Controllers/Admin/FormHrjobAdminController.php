<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormHrjob;
use App\Models\Form;
use App\Models\Hrjob;

class FormHrjobAdminController extends Controller
{
    public function getFormHrjob()
    {
        $formhrjobs = FormHrjob::with('hrjob', 'form')->get();
        return view('admin.formhrjob.index', compact('formhrjobs'));
    }

    public function addFormHrjob()
    {
        $hrjobs = Hrjob::all();
        $forms = Form::all();

        return view('admin.formhrjob.store', compact('hrjobs', 'forms'));
    }


    public function storeFormHrjob(Request $request)
    {
        $validated = $request->validate([
            'id_job' => 'required',
            'id_form' => 'required|array',
            'id_form.*' => 'required|exists:forms,id',
        ]);

        foreach ($request->id_form as $formId) {
            FormHrjob::create([
                'id_job' => $request->id_job,
                'id_form' => $formId,
            ]);
        }

        return redirect()->route('getFormHrjob')->with('message', 'Forms added successfully to the job.');
    }

    public function editFormHrjob($id)
    {
        $formhrjob = FormHrjob::findOrFail($id);
        $hrjobs = Hrjob::all();
        $forms = Form::all();

        return view('admin.formhrjob.update', compact('formhrjob', 'hrjobs', 'forms'));
    }

    public function updateFormHrjob(Request $request, $id)
    {
        $formhrjob = FormHrjob::findOrFail($id);

        $validated = $request->validate([
            'id_job' => 'required',
            'id_form' => 'required',
        ]);

        $formhrjob->id_job = $request->id_job;
        $formhrjob->id_form = $request->id_form;

        $formhrjob->save();

        return redirect()->route('getFormHrjob')->with('message', 'Form Job Updated Successfully');
    }

    public function destroyFormHrjob($id)
    {
        $formhrjob = FormHrjob::findOrFail($id);

        $formhrjob->delete();

        return redirect()->route('getFormHrjob')->with('message', 'Form Job deleted successfully');
    }
}
