<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hrjob;
use App\Models\HrjobCategory;
use App\Models\City;
use Illuminate\Support\Facades\Storage;

class HrjobAdminController extends Controller
{
    public function getHrjob()
    {
        $hrjobs = Hrjob::with('category', 'city')->get();
        return view('admin.hrjob.index', compact('hrjobs'));
    }

    public function addHrjob()
    {
        $hrjobcategories = HrjobCategory::all();
        $cities = City::all();
        return view('admin.hrjob.store', compact('hrjobcategories', 'cities'));
    }

    public function storeHrjob(Request $request)
    {
        $validated = $request->validate([
            'id_category' => 'required|exists:hrjob_categories,id',
            'id_city' => 'required|exists:cities,id',
            'job_name' => 'required|string|max:255',
            'job_type' => 'required|in:full_time,part_time,self_employed,freelancer,contract,internship,seasonal',
            'job_report' => 'nullable|string|max:1000',
            'price' => 'nullable',
            'hide_salary' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
            'qualification' => 'nullable|string|max:1000',
            'location_type' => 'required|in:on_site,hybrid,remote',
            'experience_min' => 'required|string|max:255',
            'education_min' => 'required|string|max:255',
            'expired' => 'required',
            'number_hired' => 'required',
            'is_active' => 'required|in:yes,no',
        ]);

        // Buat instance hrjob baru
        $hrjob = new Hrjob();

        $hrjob->id_category = $validated['id_category'];
        $hrjob->id_city = $validated['id_city'];
        $hrjob->job_name = $validated['job_name'];
        $hrjob->job_type = $validated['job_type'];
        $hrjob->job_report = $validated['job_report'];
        $hrjob->price = $validated['price'];
        $hrjob->hide_salary = $validated['hide_salary'];
        $hrjob->description = $validated['description'];
        $hrjob->qualification = $validated['qualification'];
        $hrjob->location_type = $validated['location_type'];
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
        $cities = City::all();

        return view('admin.hrjob.update', compact('hrjob', 'hrjobcategories', 'cities'));
    }

    public function updateHrjob(Request $request, $id)
    {
        $hrjob = Hrjob::findOrFail($id);

        $validated = $request->validate([
            'id_category' => 'required|exists:hrjob_categories,id',
            'id_city' => 'required|exists:cities,id',
            'job_name' => 'required|string|max:255',
            'job_type' => 'required|in:full_time,part_time,self_employed,freelancer,contract,internship,seasonal',
            'job_report' => 'nullable|string|max:1000',
            'price' => 'nullable',
            'hide_salary' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
            'qualification' => 'nullable|string|max:1000',
            'location_type' => 'required|in:on_site,hybrid,remote',
            'experience_min' => 'required|string|max:255',
            'education_min' => 'required|string|max:255',
            'expired' => 'required',
            'number_hired' => 'required',
            'is_active' => 'required|in:yes,no',
        ]);

        // Set default value jika 'hide_salary' tidak ada
        $validated['hide_salary'] = $request->has('hide_salary') ? 1 : 0;

        $hrjob->id_category = $validated['id_category'];
        $hrjob->id_city = $validated['id_city'];
        $hrjob->job_name = $validated['job_name'];
        $hrjob->job_type = $validated['job_type'];
        $hrjob->job_report = $validated['job_report'];
        $hrjob->price = $validated['price'];
        $hrjob->hide_salary = $validated['hide_salary'];
        $hrjob->description = $validated['description'];
        $hrjob->qualification = $validated['qualification'];
        $hrjob->location_type = $validated['location_type'];
        $hrjob->experience_min = $validated['experience_min'];
        $hrjob->education_min = $validated['education_min'];
        $hrjob->expired = $validated['expired'];
        $hrjob->number_hired = $validated['number_hired'];
        $hrjob->is_active = $validated['is_active'];

        $hrjob->save();

        return redirect()->route('getHrjob')->with('message', 'Job Updated Successfully');
    }

    public function destroyHrjob($id)
    {
        $hrjob = Hrjob::findOrFail($id);

        $hrjob->delete();

        return redirect()->route('getHrjob')->with('message', 'Job deleted successfully');
    }
}
