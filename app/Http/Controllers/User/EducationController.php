<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Education;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class EducationController extends Controller
{
    public function addEducation()
    {
        return view('user.profile.education.store');
    }

    public function storeEducation(Request $request)
    {
        $validated = $request->validate([
            'university' => 'required|string|max:255',
            'degree' => 'required|in:elementary,juniorhigh,seniorhigh,bachelor,master,doctoral',
            'major' => 'nullable|string|max:255',
            'start_date' => 'required',
            'end_date' => 'nullable',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? null;
        if ($endDate) {
            $endDate = Carbon::createFromFormat('m/Y', $endDate)->endOfMonth()->format('Y-m-d');
        }

        Education::create([
            'id_user' => Auth::id(),
            'university' => $validated['university'],
            'degree' => $validated['degree'],
            'major' => $validated['major'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('getProfile')->with('message', 'Education Added Successfully');
    }


    public function editEducation($id)
    {
        $education = Education::findOrFail($id);
        $education->start_date = Carbon::parse($education->start_date)->format('m/Y');
        $education->end_date = $education->end_date
        ? Carbon::parse($education->end_date)->format('m/Y')
        : null;

        return view('user.profile.education.update', compact('education'));
    }

    public function updateEducation(Request $request, $id)
    {
        $education = Education::findOrFail($id);

        $validated = $request->validate([
            'university' => 'required|string|max:255',
            'degree' => 'required|in:elementary,juniorhigh,seniorhigh,bachelor,master,doctoral',
            'major' => 'nullable|string|max:255',
            'start_date' => 'required',
            'end_date' => 'nullable',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? null;
        if ($endDate) {
            $endDate = Carbon::createFromFormat('m/Y', $endDate)->endOfMonth()->format('Y-m-d');
        }

        $education->update([
            'university' => $validated['university'],
            'degree' => $validated['degree'],
            'major' => $validated['major'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('getProfile')->with('message', 'Education Updated Successfully');
    }

    public function destroyEducation($id)
    {
        $education = Education::findOrFail($id);
        $education->delete();

        return redirect()->back()->with('message', 'Education deleted successfully.');
    }
}
