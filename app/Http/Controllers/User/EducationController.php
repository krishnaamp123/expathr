<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Education;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
            'degree' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        // Jika tidak ada, tambahkan data baru
        Education::create([
            'id_user' => Auth::id(),
            'university' => $request->university,
            'degree' => $request->degree,
            'major' => $request->major,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('getProfile')->with('message', 'Education Added Successfully');
    }


    public function editEducation($id)
    {
        $education = Education::findOrFail($id);

        return view('user.profile.education.update', compact('education'));
    }

    public function updateEducation(Request $request, $id)
    {
        $education = Education::findOrFail($id);

        $validated = $request->validate([
            'university' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $education->university = $validated['university'];
        $education->degree = $validated['degree'];
        $education->major = $validated['major'];
        $education->start_date = $validated['start_date'];
        $education->end_date = $validated['end_date'];
        $education->save();

        return redirect()->route('getProfile')->with('message', 'Education Updated Successfully');
    }

    public function destroyEducation($id)
    {
        $education = Education::findOrFail($id);
        $education->delete();

        return redirect()->back()->with('success', 'Education deleted successfully.');
    }
}
