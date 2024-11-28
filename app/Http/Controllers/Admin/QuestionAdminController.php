<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;

class QuestionAdminController extends Controller
{
    public function getQuestion()
    {
        return view('admin.question.index', [
            'questions' => Question::latest()->get()
        ]);
    }

    public function addQuestion()
    {
        return view('admin.question.store');
    }

    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|max:255',
        ]);

        Question::create([
            'question' => $request->question,
        ]);

        return redirect()->route('getQuestion')->with('message', 'Data Added Successfully');
    }

    public function editQuestion($id)
    {
        $question = Question::findOrFail($id);

        return view('admin.question.update', compact('question'));
    }

    public function updateQuestion(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|max:255',
        ]);

        $question->question = $request->question;

        $question->save();

        return redirect()->route('getQuestion')->with('message', 'Question Updated Successfully');
    }

    public function destroyQuestion($id)
    {
        $question = Question::findOrFail($id);

        $question->delete();

        return redirect()->route('getQuestion')->with('message', 'Question deleted successfully');
    }

}
