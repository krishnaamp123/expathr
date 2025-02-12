<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Experience;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

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
            'end_date' => 'nullable',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? null;
        if ($endDate) {
            $endDate = Carbon::createFromFormat('m/Y', $endDate)->endOfMonth()->format('Y-m-d');
        }

        Experience::create([
            'id_user' => Auth::id(),
            'position' => $validated['position'],
            'job_type' => $validated['job_type'],
            'company_name' => $validated['company_name'],
            'location' => $validated['location'],
            'location_type' => $validated['location_type'],
            'responsibility' => $validated['responsibility'],
            'job_report' => $validated['job_report'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('getProfile')->with('message', 'Experience Added Successfully');
    }


    public function editExperience($id)
    {
        $experience = Experience::findOrFail($id);
        $experience->start_date = Carbon::parse($experience->start_date)->format('m/Y');
        $experience->end_date = $experience->end_date
        ? Carbon::parse($experience->end_date)->format('m/Y')
        : null;

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
            'end_date' => 'nullable',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? null;
        if ($endDate) {
            $endDate = Carbon::createFromFormat('m/Y', $endDate)->endOfMonth()->format('Y-m-d');
        }

        $experience->update([
            'position' => $validated['position'],
            'job_type' => $validated['job_type'],
            'company_name' => $validated['company_name'],
            'location' => $validated['location'],
            'location_type' => $validated['location_type'],
            'responsibility' => $validated['responsibility'],
            'job_report' => $validated['job_report'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);


        return redirect()->route('getProfile')->with('message', 'Experience Updated Successfully');
    }

    public function destroyExperience($id)
    {
        $experience = Experience::findOrFail($id);
        $experience->delete();

        return redirect()->back()->with('message', 'Experience deleted successfully.');
    }
}
