<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hrjob;
use App\Models\HrjobCategory;
use Illuminate\Support\Facades\Storage;

class HrjobAdminController extends Controller
{
    public function getHrjob()
    {
        $hrjobs = Hrjob::with('category')->get();
        return view('admin.hrjob.index', compact('hrjobs'));
    }

    public function addHrjob()
    {
        $hrjobcategories = HrjobCategory::all();
        return view('admin.hrjob.store', compact('hrjobcategories'));
    }

    public function storeHrjob(Request $request)
    {
        $validated = $request->validate([
            'id_category' => 'required|exists:hrjob_categories,id',
            'job_name' => 'required|string|max:255',
            'job_type' => 'required|in:full_time,part_time,self_employed,freelancer,contract,internship,seasonal',
            'job_report' => 'nullable|string|max:1000',
            'price' => 'required',
            'description' => 'nullable|string|max:1000',
            'qualification' => 'nullable|string|max:1000',
            'location_type' => 'required|in:on_site,hybrid,remote',
            'location' => 'required|string|max:255',
            'experience_min' => 'required|string|max:255',
            'education_min' => 'required|string|max:255',
            'expired' => 'required',
            'number_hired' => 'required',
            'is_active' => 'required|in:yes,no',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Buat instance hrjob baru
        $hrjob = new Hrjob();

        // Periksa dan simpan file gambar jika ada
        if ($request->hasFile('file')) {
            // Generate nama file unik
            $filename = $this->generateRandomString();
            $extension = $request->file('file')->getClientOriginalExtension();
            $jobImageName = $filename . '.' . $extension;

            // Tentukan lokasi penyimpanan di public/storage/images
            $destinationPath = public_path('storage/job_images');

            // Buat folder jika belum ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Pindahkan file ke lokasi tujuan
            $request->file('file')->move($destinationPath, $jobImageName);

            // Simpan nama file ke database
            $hrjob->job_image = 'storage/job_images/' . $jobImageName;
        }

        $hrjob->id_category = $validated['id_category'];
        $hrjob->job_name = $validated['job_name'];
        $hrjob->job_type = $validated['job_type'];
        $hrjob->job_report = $validated['job_report'];
        $hrjob->price = $validated['price'];
        $hrjob->description = $validated['description'];
        $hrjob->qualification = $validated['qualification'];
        $hrjob->location_type = $validated['location_type'];
        $hrjob->location = $validated['location'];
        $hrjob->experience_min = $validated['experience_min'];
        $hrjob->education_min = $validated['education_min'];
        $hrjob->expired = $validated['expired'];
        $hrjob->number_hired = $validated['number_hired'];
        $hrjob->is_active = $validated['is_active'];

        $hrjob->save();

        return redirect()->route('getHrjob')->with('message', 'Data Added Successfully');
    }

    public function editHrjob($id)
    {
        $hrjob = Hrjob::findOrFail($id);
        $hrjobcategories = HrjobCategory::all();

        return view('admin.hrjob.update', compact('hrjob', 'hrjobcategories'));
    }

    public function updateHrjob(Request $request, $id)
    {
        $hrjob = Hrjob::findOrFail($id);

        $validated = $request->validate([
            'id_category' => 'required|exists:hrjob_categories,id',
            'job_name' => 'required|string|max:255',
            'job_type' => 'required|in:full_time,part_time,self_employed,freelancer,contract,internship,seasonal',
            'job_report' => 'nullable|string|max:1000',
            'price' => 'required',
            'description' => 'nullable|string|max:1000',
            'qualification' => 'nullable|string|max:1000',
            'location_type' => 'required|in:on_site,hybrid,remote',
            'location' => 'required|string|max:255',
            'experience_min' => 'required|string|max:255',
            'education_min' => 'required|string|max:255',
            'expired' => 'required',
            'number_hired' => 'required',
            'is_active' => 'required|in:yes,no',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Perbarui gambar jika ada
        if ($request->hasFile('file')) {
            // Generate nama file unik
            $filename = $this->generateRandomString();
            $extension = $request->file('file')->getClientOriginalExtension();
            $jobImageName = $filename . '.' . $extension;

            // Tentukan lokasi penyimpanan di public/storage/images
            $destinationPath = public_path('storage/job_images');

            // Buat folder jika belum ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Pindahkan file ke lokasi tujuan
            $request->file('file')->move($destinationPath, $jobImageName);

            // Hapus file lama jika ada
            if ($hrjob->job_image) {
                $oldFilePath = public_path($hrjob->job_image);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Simpan nama file ke database
            $hrjob->job_image = 'storage/job_images/' . $jobImageName;
        }

        $hrjob->id_category = $validated['id_category'];
        $hrjob->job_name = $validated['job_name'];
        $hrjob->job_type = $validated['job_type'];
        $hrjob->job_report = $validated['job_report'];
        $hrjob->price = $validated['price'];
        $hrjob->description = $validated['description'];
        $hrjob->qualification = $validated['qualification'];
        $hrjob->location_type = $validated['location_type'];
        $hrjob->location = $validated['location'];
        $hrjob->experience_min = $validated['experience_min'];
        $hrjob->education_min = $validated['education_min'];
        $hrjob->expired = $validated['expired'];
        $hrjob->number_hired = $validated['number_hired'];
        $hrjob->is_active = $validated['is_active'];

        $hrjob->save();

        return redirect()->route('getHrjob')->with('message', 'Job Category Updated Successfully');
    }

    public function destroyHrjob($id)
    {
        $hrjob = Hrjob::findOrFail($id);

        $hrjob->delete();

        return redirect()->route('getHrjob')->with('message', 'Job Category deleted successfully');
    }

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
