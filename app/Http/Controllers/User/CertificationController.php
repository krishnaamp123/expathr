<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Certification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class CertificationController extends Controller
{
    public function addCertification()
    {
        return view('user.profile.certification.store');
    }

    public function storeCertification(Request $request)
    {
        $validated = $request->validate([
            'lisence_name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'id_credentials' => 'nullable|string|max:255',
            'url_credentials' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::createFromFormat('m/Y', $validated['end_date'])->startOfMonth()->format('Y-m-d');

        Certification::create([
            'id_user' => Auth::id(),
            'lisence_name' => $validated['lisence_name'],
            'organization' => $validated['organization'],
            'id_credentials' => $validated['id_credentials'],
            'url_credentials' => $validated['url_credentials'],
            'description' => $validated['description'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('getProfile')->with('message', 'Certification Added Successfully');
    }


    public function editCertification($id)
    {
        $certification = Certification::findOrFail($id);
        $certification->start_date = Carbon::parse($certification->start_date)->format('m/Y');
        $certification->end_date = Carbon::parse($certification->end_date)->format('m/Y');

        return view('user.profile.certification.update', compact('certification'));
    }

    public function updateCertification(Request $request, $id)
    {
        $certification = Certification::findOrFail($id);

        $validated = $request->validate([
            'lisence_name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'id_credentials' => 'nullable|string|max:255',
            'url_credentials' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $startDate = Carbon::createFromFormat('m/Y', $validated['start_date'])->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::createFromFormat('m/Y', $validated['end_date'])->endOfMonth()->format('Y-m-d');

        $organization->update([
            'lisence_name' => $validated['lisence_name'],
            'organization' => $validated['organization'],
            'id_credentials' => $validated['id_credentials'],
            'url_credentials' => $validated['url_credentials'],
            'description' => $validated['description'],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('getProfile')->with('message', 'Certification Updated Successfully');
    }

    public function destroyCertification($id)
    {
        $certification = Certification::findOrFail($id);
        $certification->delete();

        return redirect()->back()->with('message', 'Certification deleted successfully.');
    }
}
