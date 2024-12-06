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
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
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

        Certification::create([
            'id_user' => Auth::id(),
            'lisence_name' => $validated['lisence_name'],
            'organization' => $validated['organization'],
            'id_credentials' => $validated['id_credentials'],
            'url_credentials' => $validated['url_credentials'],
            'media' => $mediaName,
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
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
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
            if ($certification->media) {
                $oldFilePath = public_path($certification->media);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Simpan nama file ke database
            $certification->media = 'storage/images/' . $mediaName;
        }

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

    public function destroyCertification($id)
    {
        $certification = Certification::findOrFail($id);
        $certification->delete();

        return redirect()->back()->with('message', 'Certification deleted successfully.');
    }
}
