<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FormAdminController extends Controller
{
    public function getForm()
    {
        return view('admin.form.index', [
            'forms' => Form::latest()->get()
        ]);
    }

    public function addForm()
    {
        return view('admin.form.store');
    }

    public function storeForm(Request $request)
    {
        try {
            \Log::info('storeForm function is running');

            // Validasi input
            $validated = $request->validate([
                'form_name' => 'required|max:255',
                'questions' => 'required|array|min:1',
                'questions.*.question_name' => 'required|max:255',
                'questions.*.answers' => 'required|array|min:2',
                'questions.*.answers.*.answer_name' => 'required|max:255',
                'questions.*.answers.*.is_answer' => 'required|in:yes,no',
            ]);

            \Log::info('Validation passed', $validated);

            // Simpan form (hanya satu kali)
            $form = Form::create([
                'form_name' => $validated['form_name'],
            ]);

            // Iterasi dan simpan setiap pertanyaan
            foreach ($validated['questions'] as $questionData) {
                $question = $form->question()->create([
                    'question_name' => $questionData['question_name'],
                ]);

                // Iterasi dan simpan jawaban untuk pertanyaan tersebut
                foreach ($questionData['answers'] as $answerData) {
                    $question->answer()->create([
                        'answer_name' => $answerData['answer_name'],
                        'is_answer' => $answerData['is_answer'],
                    ]);
                }
            }

            \Log::info('Form and related data stored successfully');
            return redirect()->route('getForm')->with('message', 'Form and Questions Added Successfully');
        } catch (\Exception $e) {
            \Log::error('Error in storeForm: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An unexpected error occurred.']);
        }
    }

    public function editForm($id)
    {
        // Ambil form berdasarkan ID
        $form = Form::with('questions.answers')->findOrFail($id);

        return view('admin.form.update', compact('form'));
    }

    public function updateForm(Request $request, $id)
    {
        try {
            \Log::info('updateForm function is running');

            // Validasi input
            $validated = $request->validate([
                'form_name' => 'required|max:255',
                'questions' => 'required|array|min:1',
                'questions.*.id' => 'nullable|exists:questions,id',
                'questions.*.question_name' => 'required|max:255',
                'questions.*.answers' => 'required|array|min:2',
                'questions.*.answers.*.id' => 'nullable|exists:answers,id',
                'questions.*.answers.*.answer_name' => 'required|max:255',
                'questions.*.answers.*.is_answer' => 'required|in:yes,no',
            ]);

            \Log::info('Validation passed', $validated);

            // Cari form berdasarkan ID
            $form = Form::findOrFail($id);

            // Update nama form
            $form->update([
                'form_name' => $validated['form_name'],
            ]);

            // Iterasi pertanyaan
            foreach ($validated['questions'] as $questionData) {
                if (isset($questionData['id'])) {
                    // Update pertanyaan jika ID ada
                    $question = $form->questions()->findOrFail($questionData['id']);
                    $question->update([
                        'question_name' => $questionData['question_name'],
                    ]);
                } else {
                    // Buat pertanyaan baru jika ID tidak ada
                    $question = $form->questions()->create([
                        'question_name' => $questionData['question_name'],
                    ]);
                }

                // Iterasi jawaban
                foreach ($questionData['answers'] as $answerData) {
                    if (isset($answerData['id'])) {
                        // Update jawaban jika ID ada
                        $answer = $question->answers()->findOrFail($answerData['id']);
                        $answer->update([
                            'answer_name' => $answerData['answer_name'],
                            'is_answer' => $answerData['is_answer'],
                        ]);
                    } else {
                        // Buat jawaban baru jika ID tidak ada
                        $question->answers()->create([
                            'answer_name' => $answerData['answer_name'],
                            'is_answer' => $answerData['is_answer'],
                        ]);
                    }
                }
            }

            \Log::info('Form and related data updated successfully');
            return redirect()->route('getForm')->with('message', 'Form and Questions Updated Successfully');
        } catch (\Exception $e) {
            \Log::error('Error in updateForm: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An unexpected error occurred.']);
        }
    }

    public function destroyForm($id)
    {
        try {
            $form = Form::findOrFail($id);

            $form->questions()->each(function ($question) {
                $question->answers()->delete();
                $question->delete();
            });

            // Hapus form
            $form->delete();

            return redirect()->route('getForm')->with('message', 'Form and its related data have been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('getForm')->with('error', 'Failed to delete the form: ' . $e->getMessage());
        }
    }


}
