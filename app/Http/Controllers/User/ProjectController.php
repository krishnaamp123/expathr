<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
            'start_date' => 'required',
            'end_date' => 'nullable',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? null;
        if ($endDate) {
            $endDate = Carbon::createFromFormat('m/Y', $endDate)->endOfMonth()->format('Y-m-d');
        }

        Project::create([
            'id_user' => Auth::id(),
            'project_name' => $validated['project_name'],
            'description' => $validated['description'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('getProfile')->with('message', 'Project Added Successfully');
    }


    public function editProject($id)
    {
        $project = Project::findOrFail($id);
        $project->start_date = Carbon::parse($project->start_date)->format('m/Y');
        $project->end_date = $project->end_date
        ? Carbon::parse($project->end_date)->format('m/Y')
        : null;

        return view('user.profile.project.update', compact('project'));
    }

    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required',
            'end_date' => 'nullable',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? null;
        if ($endDate) {
            $endDate = Carbon::createFromFormat('m/Y', $endDate)->endOfMonth()->format('Y-m-d');
        }

        $project->update([
            'project_name' => $validated['project_name'],
            'description' => $validated['description'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('getProfile')->with('message', 'Project Updated Successfully');
    }

    public function destroyProject($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->back()->with('message', 'Project deleted successfully.');
    }
}
