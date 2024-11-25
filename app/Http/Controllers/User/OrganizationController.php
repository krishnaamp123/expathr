<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
            'end_date' => 'required',
        ]);

        // Jika tidak ada, tambahkan data baru
        Organization::create([
            'id_user' => Auth::id(),
            'organization' => $request->organization,
            'position' => $request->position,
            'associated' => $request->associated,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('getProfile')->with('message', 'Organization Added Successfully');
    }


    public function editOrganization($id)
    {
        $organization = Organization::findOrFail($id);

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
            'end_date' => 'required',
        ]);

        $organization->organization = $validated['organization'];
        $organization->position = $validated['position'];
        $organization->associated = $validated['associated'];
        $organization->description = $validated['description'];
        $organization->start_date = $validated['start_date'];
        $organization->end_date = $validated['end_date'];
        $organization->save();

        return redirect()->route('getProfile')->with('message', 'Organization Updated Successfully');
    }

    public function destroyOrganization($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return redirect()->back()->with('success', 'Organization deleted successfully.');
    }
}
