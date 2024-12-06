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
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $mediaName = null;

        if ($request->hasFile('file')) {
            $filename = $this->generateRandomString();
            $extension = $request->file('file')->getClientOriginalExtension();
            $mediaName = 'storage/images/' . $filename . '.' . $extension;

            $destinationPath = public_path('storage/images');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $request->file('file')->move($destinationPath, $filename . '.' . $extension);
        }

        Project::create([
            'id_user' => Auth::id(),
            'project_name' => $validated['project_name'],
            'description' => $validated['description'],
            'media' => $mediaName,
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
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        // Perbarui gambar jika ada
        if ($request->hasFile('file')) {
            // Generate nama file unik
            $filename = $this->generateRandomString();
            $extension = $request->file('file')->getClientOriginalExtension();
            $mediaName = $filename . '.' . $extension;

            // Tentukan lokasi penyimpanan di public/storage/images
            $destinationPath = public_path('storage/images');

            // Buat folder jika belum ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Pindahkan file ke lokasi tujuan
            $request->file('file')->move($destinationPath, $mediaName);

            // Hapus file lama jika ada
            if ($project->media) {
                $oldFilePath = public_path($project->media);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Simpan nama file ke database
            $project->media = 'storage/images/' . $mediaName;
        }

        $project->project_name = $validated['project_name'];
        $project->description = $validated['description'];
        $project->start_date = $validated['start_date'];
        $project->end_date = $validated['end_date'];
        $project->save();

        return redirect()->route('getProfile')->with('message', 'Project Updated Successfully');
    }

    // Helper method to generate a random string for the file name
    function generateRandomString($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function destroyProject($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->back()->with('message', 'Project deleted successfully.');
    }
}
