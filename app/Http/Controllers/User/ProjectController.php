<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function addProject()
    {
        return view('user.profile.project.store');
    }

    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        Project::create([
            'id_user' => Auth::id(),
            'project_name' => $validated['project_name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->route('getProfile')->with('message', 'Project Added Successfully');
    }


    public function editProject($id)
    {
        $project = Project::findOrFail($id);

        return view('user.profile.project.update', compact('project'));
    }

    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $project->project_name = $validated['project_name'];
        $project->description = $validated['description'];
        $project->start_date = $validated['start_date'];
        $project->end_date = $validated['end_date'];
        $project->save();

        return redirect()->route('getProfile')->with('message', 'Project Updated Successfully');
    }

    public function destroyProject($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->back()->with('message', 'Project deleted successfully.');
    }
}
