<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Experience;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ExperienceController extends Controller
{
    public function addExperience()
    {
        return view('user.profile.experience.store');
    }

    public function storeExperience(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'job_type' => 'required|in:full_time,part_time,self_employed,freelancer,contract,internship,seasonal',
            'company_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'location_type' => 'required|in:on_site,hybrid,remote',
            'responsibility' => 'nullable|string|max:1000',
            'job_report' => 'nullable|string|max:1000',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        // Jika tidak ada, tambahkan data baru
        Experience::create([
            'id_user' => Auth::id(),
            'position' => $request->position,
            'job_type' => $request->job_type,
            'company_name' => $request->company_name,
            'location' => $request->location,
            'location_type' => $request->location_type,
            'responsibility' => $request->responsibility,
            'job_report' => $request->job_report,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('getProfile')->with('message', 'Experience Added Successfully');
    }


    public function editExperience($id)
    {
        $experience = Experience::findOrFail($id);

        return view('user.profile.experience.update', compact('experience'));
    }

    public function updateExperience(Request $request, $id)
    {
        $experience = Experience::findOrFail($id);

        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'job_type' => 'required|in:full_time,part_time,self_employed,freelancer,contract,internship,seasonal',
            'company_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'location_type' => 'required|in:on_site,hybrid,remote',
            'responsibility' => 'nullable|string|max:1000',
            'job_report' => 'nullable|string|max:1000',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $experience->position = $validated['position'];
        $experience->job_type = $validated['job_type'];
        $experience->company_name = $validated['company_name'];
        $experience->location = $validated['location'];
        $experience->location_type = $validated['location_type'];
        $experience->responsibility = $validated['responsibility'];
        $experience->job_report = $validated['job_report'];
        $experience->start_date = $validated['start_date'];
        $experience->end_date = $validated['end_date'];
        $experience->save();

        return redirect()->route('getProfile')->with('message', 'Experience Updated Successfully');
    }

    public function destroyExperience($id)
    {
        $experience = Experience::findOrFail($id);
        $experience->delete();

        return redirect()->back()->with('message', 'Experience deleted successfully.');
    }
}
