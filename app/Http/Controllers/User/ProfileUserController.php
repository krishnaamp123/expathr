<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use Illuminate\Support\Facades\Storage;

class ProfileUserController extends Controller
{
    public function getProfile()
    {
        $user = auth()->user()->load('city');
        $cities = City::all();

        return view('user.profile.index', compact('user', 'cities'));
    }

    public function editProfile($id)
    {
        $user = User::findOrFail($id);
        $cities = City::all();

        return view('user.profile.update', compact('user', 'cities'));
    }

    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'id_city' => 'required|exists:cities,id',
            'fullname' => 'required|string|max:255',
            'nickname' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
        ]);

        // Perbarui gambar jika ada
        if ($request->hasFile('file')) {
            // Generate nama file unik
            $filename = $this->generateRandomString();
            $extension = $request->file('file')->getClientOriginalExtension();
            $profilePictName = $filename . '.' . $extension;

            // Tentukan lokasi penyimpanan di public/storage/images
            $destinationPath = public_path('storage/images');

            // Buat folder jika belum ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Pindahkan file ke lokasi tujuan
            $request->file('file')->move($destinationPath, $profilePictName);

            // Hapus file lama jika ada
            if ($user->profile_pict) {
                $oldFilePath = public_path($user->profile_pict);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Simpan nama file ke database
            $user->profile_pict = 'storage/images/' . $profilePictName;
        }

        // Update hanya field yang divalidasi
        $user->id_city = $validated['id_city'];
        $user->fullname = $validated['fullname'];
        $user->nickname = $validated['nickname'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];
        $user->link = $validated['link'] ?? $user->link;

        $user->save();

        return redirect()->route('getProfile')->with('message', 'Profile Updated Successfully');
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
}
