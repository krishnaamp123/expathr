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
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        Certification::create([
            'id_user' => Auth::id(),
            'lisence_name' => $validated['lisence_name'],
            'organization' => $validated['organization'],
            'id_credentials' => $validated['id_credentials'],
            'url_credentials' => $validated['url_credentials'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->route('getProfile')->with('message', 'Certification Added Successfully');
    }


    public function editCertification($id)
    {
        $certification = Certification::findOrFail($id);

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
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $certification->lisence_name = $validated['lisence_name'];
        $certification->organization = $validated['organization'];
        $certification->id_credentials = $validated['id_credentials'];
        $certification->url_credentials = $validated['url_credentials'];
        $certification->description = $validated['description'];
        $certification->start_date = $validated['start_date'];
        $certification->end_date = $validated['end_date'];
        $certification->save();

        return redirect()->route('getProfile')->with('message', 'Certification Updated Successfully');
    }

    public function destroyCertification($id)
    {
        $certification = Certification::findOrFail($id);
        $certification->delete();

        return redirect()->back()->with('message', 'Certification deleted successfully.');
    }
}
