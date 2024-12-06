<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

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

        Volunteer::create([
            'id_user' => Auth::id(),
            'organization' => $validated['organization'],
            'role' => $validated['role'],
            'issue' => $validated['issue'],
            'description' => $validated['description'],
            'media' => $mediaName,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->route('getProfile')->with('message', 'Volunteer Added Successfully');
    }


    public function editVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);

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
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
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
            if ($volunteer->media) {
                $oldFilePath = public_path($volunteer->media);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Simpan nama file ke database
            $volunteer->media = 'storage/images/' . $mediaName;
        }

        $volunteer->organization = $validated['organization'];
        $volunteer->role = $validated['role'];
        $volunteer->issue = $validated['issue'];
        $volunteer->description = $validated['description'];
        $volunteer->start_date = $validated['start_date'];
        $volunteer->end_date = $validated['end_date'];
        $volunteer->save();

        return redirect()->route('getProfile')->with('message', 'Volunteer Updated Successfully');
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

    public function destroyVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->delete();

        return redirect()->back()->with('message', 'Volunteer deleted successfully.');
    }
}
