<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class VolunteerController extends Controller
{
    public function addVolunteer()
    {
        return view('user.profile.volunteer.store');
    }

    public function storeVolunteer(Request $request)
    {
        $validated = $request->validate([
            'organization' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'issue' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required',
            'end_date' => 'nullable',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? null;
        if ($endDate) {
            $endDate = Carbon::createFromFormat('m/Y', $endDate)->endOfMonth()->format('Y-m-d');
        }

        Volunteer::create([
            'id_user' => Auth::id(),
            'organization' => $validated['organization'],
            'role' => $validated['role'],
            'issue' => $validated['issue'],
            'description' => $validated['description'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('getProfile')->with('message', 'Volunteer Added Successfully');
    }


    public function editVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->start_date = Carbon::parse($volunteer->start_date)->format('m/Y');
        $volunteer->end_date = $volunteer->end_date
        ? Carbon::parse($volunteer->end_date)->format('m/Y')
        : null;

        return view('user.profile.volunteer.update', compact('volunteer'));
    }

    public function updateVolunteer(Request $request, $id)
    {
        $volunteer = Volunteer::findOrFail($id);

        $validated = $request->validate([
            'organization' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'issue' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required',
            'end_date' => 'nullable',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? null;
        if ($endDate) {
            $endDate = Carbon::createFromFormat('m/Y', $endDate)->endOfMonth()->format('Y-m-d');
        }

        $volunteer->update([
            'organization' => $validated['organization'],
            'role' => $validated['role'],
            'issue' => $validated['issue'],
            'description' => $validated['description'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);


        return redirect()->route('getProfile')->with('message', 'Volunteer Updated Successfully');
    }

    public function destroyVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->delete();

        return redirect()->back()->with('message', 'Volunteer deleted successfully.');
    }
}
