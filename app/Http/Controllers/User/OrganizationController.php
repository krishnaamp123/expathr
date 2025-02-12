<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class OrganizationController extends Controller
{
    public function addOrganization()
    {
        return view('user.profile.organization.store');
    }

    public function storeOrganization(Request $request)
    {
        $validated = $request->validate([
            'organization' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'associated' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required',
            'end_date' => 'nullable',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? null;
        if ($endDate) {
            $endDate = Carbon::createFromFormat('m/Y', $endDate)->endOfMonth()->format('Y-m-d');
        }

        Organization::create([
            'id_user' => Auth::id(),
            'organization' => $validated['organization'],
            'position' => $validated['position'],
            'associated' => $validated['associated'],
            'description' => $validated['description'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('getProfile')->with('message', 'Organization Added Successfully');
    }


    public function editOrganization($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->start_date = Carbon::parse($organization->start_date)->format('m/Y');
        $organization->end_date = $organization->end_date
        ? Carbon::parse($organization->end_date)->format('m/Y')
        : null;

        return view('user.profile.organization.update', compact('organization'));
    }

    public function updateOrganization(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);

        $validated = $request->validate([
            'organization' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'associated' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required',
            'end_date' => 'nullable',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? null;
        if ($endDate) {
            $endDate = Carbon::createFromFormat('m/Y', $endDate)->endOfMonth()->format('Y-m-d');
        }

        $organization->update([
            'organization' => $validated['organization'],
            'position' => $validated['position'],
            'associated' => $validated['associated'],
            'description' => $validated['description'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('getProfile')->with('message', 'Organization Updated Successfully');
    }

    public function destroyOrganization($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return redirect()->back()->with('message', 'Organization deleted successfully.');
    }
}
