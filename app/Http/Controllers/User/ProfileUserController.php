<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\Field;
use App\Models\WorkLocation;
use App\Models\Emergency;
use App\Models\About;
use App\Models\Language;
use App\Models\WorkField;
use App\Models\Education;
use App\Models\Project;
use App\Models\Organization;
use App\Models\Volunteer;
use App\Models\Experience;
use App\Models\Certification;
use App\Models\Skill;
use App\Models\Source;
use Illuminate\Support\Facades\Storage;

class ProfileUserController extends Controller
{
    public function getProfile()
    {
        $user = auth()->user()->load('city');
        $cities = City::all();
        $fields = Field::all();
        $worklocation = WorkLocation::where('id_user', $user->id)->get();
        $emergency = Emergency::where('id_user', $user->id)->get();
        $about = About::where('id_user', $user->id)->get();
        $language = Language::where('id_user', $user->id)->get();
        $workfield = WorkField::where('id_user', $user->id)->get();
        $education = Education::where('id_user', $user->id)->get();
        $project = Project::where('id_user', $user->id)->get();
        $organization = Organization::where('id_user', $user->id)->get();
        $volunteer = Volunteer::where('id_user', $user->id)->get();
        $experience = Experience::where('id_user', $user->id)->get();
        $certification = Certification::where('id_user', $user->id)->get();
        $skill = Skill::where('id_user', $user->id)->get();
        $source = Source::where('id_user', $user->id)->get();

        return view('user.profile.index', compact('user', 'cities', 'fields', 'worklocation', 'emergency', 'about', 'language',
        'workfield', 'education', 'project', 'organization', 'volunteer', 'experience', 'certification', 'skill', 'source'));
    }

    public function editProfile($id)
    {
        $user = User::findOrFail($id);
        $cities = City::all();

        return view('user.profile.profileuser.update', compact('user', 'cities'));
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
            'birth_date' => 'required',
            'gender' => 'required|in:male,female',
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
        $user->birth_date = $validated['birth_date'];
        $user->gender = $validated['gender'];
        $user->link = $validated['link'];

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
