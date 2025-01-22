<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hrjob;
use App\Models\HrjobCategory;
use App\Models\City;
use App\Models\Outlet;
use App\Models\Offering;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HrjobAdminController extends Controller
{
    public function getHrjob()
    {
        // Tentukan logika penyaringan berdasarkan role pengguna
        if (Auth::user()->role === 'hiring_manager') {
            $hrjobs = Hrjob::with('user', 'category', 'city', 'outlet', 'offerings.userHrjob.user')
                ->whereHas('user', function ($query) {
                    $query->where('role', '!=', 'super_admin');
                })
                ->get();
        } elseif (Auth::user()->role === 'recruiter') {
            $hrjobs = Hrjob::with('user', 'category', 'city', 'outlet', 'offerings.userHrjob.user')
                ->whereHas('user', function ($query) {
                    $query->where('id', Auth::id());
                })
                ->get();
        } else {
            $hrjobs = Hrjob::with('user', 'category', 'city', 'outlet', 'offerings.userHrjob.user')->get();
        }

        return view('admin.hrjob.index', compact('hrjobs'));
    }

    public function addHrjob()
    {
        $users = User::whereNotIn('role', ['applicant', 'interviewer'])->get();
        $hrjobcategories = HrjobCategory::all();
        $cities = City::all();
        $outlets = Outlet::all();
        return view('admin.hrjob.store', compact('users','hrjobcategories', 'cities', 'outlets'));
    }

    public function storeHrjob(Request $request)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_category' => 'required|exists:hrjob_categories,id',
            'id_outlet' => 'required|exists:outlets,id',
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
        ]);

        $loggedInUser = Auth::user();

        // Jika user yang login adalah recruiter
        if ($loggedInUser->role === 'recruiter') {
            return redirect()->route('getHrjob')->with('error', 'Recruiters are not allowed to create jobs.');
        }

        // Jika user yang login adalah hiring_manager
        if ($loggedInUser->role === 'hiring_manager') {
            $selectedUser = User::find($validated['id_user']);
            if ($selectedUser && $selectedUser->role === 'super_admin') {
                return redirect()->route('getHrjob')->with('error', 'You cannot manage job for Super Admin.');
            }
        }

        // Buat instance hrjob baru
        $hrjob = new Hrjob();

        $hrjob->id_user = $validated['id_user'];
        $hrjob->id_category = $validated['id_category'];
        $hrjob->id_outlet = $validated['id_outlet'];
        $hrjob->id_city = $validated['id_city'];
        $hrjob->job_name = $validated['job_name'];
        $hrjob->job_type = $validated['job_type'];
        $hrjob->job_report = $validated['job_report'];
        $hrjob->price = $validated['price'];
        $hrjob->hide_salary = $request->input('hide_salary', 0);
        $hrjob->description = $validated['description'];
        $hrjob->qualification = $validated['qualification'];
        $hrjob->location_type = $validated['location_type'];
        $hrjob->experience_min = $validated['experience_min'];
        $hrjob->education_min = $validated['education_min'];
        $hrjob->expired = $validated['expired'];
        $hrjob->number_hired = $validated['number_hired'];

        $hrjob->save();

        return redirect()->route('getHrjob')->with('message', 'Data Added Successfully');
    }

    public function editHrjob($id)
    {
        $hrjob = Hrjob::findOrFail($id);
        $users = User::where('role', '!=', 'applicant')->get();
        $hrjobcategories = HrjobCategory::all();
        $cities = City::all();
        $outlets = Outlet::all();

        return view('admin.hrjob.update', compact('hrjob', 'users', 'hrjobcategories', 'cities', 'outlets'));
    }

    public function updateHrjob(Request $request, $id)
    {
        $hrjob = Hrjob::findOrFail($id);

        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_category' => 'required|exists:hrjob_categories,id',
            'id_outlet' => 'required|exists:outlets,id',
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
            'is_ended' => 'nullable|in:yes,no',
            'hiring_cost' => 'nullable',
            'job_closed' => 'nullable',
        ]);

        $loggedInUser = Auth::user();

        // Jika user yang login adalah recruiter
        if ($loggedInUser->role === 'recruiter') {
            return redirect()->route('getHrjob')->with('error', 'Recruiters are not allowed to update jobs.');
        }

        // Jika user yang login adalah hiring_manager
        if ($loggedInUser->role === 'hiring_manager') {
            $selectedUser = User::find($validated['id_user']);
            if ($selectedUser && $selectedUser->role === 'super_admin') {
                return redirect()->route('getHrjob')->with('error', 'You cannot manage job for Super Admin.');
            }
        }

        // Set default value jika 'hide_salary' tidak ada
        $validated['hide_salary'] = $request->has('hide_salary') ? 1 : 0;

        $hrjob->id_user = $validated['id_user'];
        $hrjob->id_category = $validated['id_category'];
        $hrjob->id_outlet = $validated['id_outlet'];
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
        $hrjob->is_ended = $validated['is_ended'];
        $hrjob->hiring_cost = $validated['hiring_cost'];
        $hrjob->job_closed = $validated['job_closed'];

        $hrjob->save();

        return redirect()->route('getHrjob')->with('message', 'Job Updated Successfully');
    }

    public function destroyHrjob($id)
    {
        $hrjob = Hrjob::findOrFail($id);

        $loggedInUser = Auth::user();

        // Jika user yang login adalah recruiter
        if ($loggedInUser->role === 'recruiter') {
            return redirect()->route('getHrjob')->with('error', 'Recruiters are not allowed to delete jobs.');
        }

        // Jika user yang login adalah hiring_manager
        if ($loggedInUser->role === 'hiring_manager') {
            $selectedUser = User::find($validated['id_user']);
            if ($selectedUser && $selectedUser->role === 'super_admin') {
                return redirect()->route('getHrjob')->with('error', 'You cannot manage job for Super Admin.');
            }
        }

        $hrjob->delete();

        return redirect()->route('getHrjob')->with('message', 'Job deleted successfully');
    }

    public function updateIsEnded(Request $request, $id)
    {
        $hrjob = Hrjob::findOrFail($id);

        $loggedInUser = Auth::user();

        // Jika user yang login adalah recruiter
        if ($loggedInUser->role === 'recruiter') {
            $selectedUser = User::find($validated['id_user']);
            if ($selectedUser && in_array($selectedUser->role, ['super_admin', 'hiring_manager'])) {
                return redirect()->route('getHrjob')->with('error', 'You cannot manage job for Super Admin & Hiring Manager.');
            }
        }

        // Jika user yang login adalah hiring_manager
        if ($loggedInUser->role === 'hiring_manager') {
            $selectedUser = User::find($validated['id_user']);
            if ($selectedUser && $selectedUser->role === 'super_admin') {
                return redirect()->route('getHrjob')->with('error', 'You cannot manage job for Super Admin.');
            }
        }

        // Validasi input
        $validated = $request->validate([
            'is_ended' => 'required|in:yes,no', // Harus selalu diberikan
            'hiring_cost' => 'required_if:is_ended,yes|numeric',
            'selected_offerings' => 'array', // Tidak wajib
            'selected_offerings.*' => 'exists:offerings,id', // Pastikan ID valid jika diisi
        ]);

        // Tetapkan status pekerjaan
        if ($validated['is_ended'] === 'yes') {
            $hrjob->is_ended = 'yes';
            $hrjob->hiring_cost = $validated['hiring_cost'];

            // Jika ada kandidat yang dipilih
            if (!empty($validated['selected_offerings'])) {
                Offering::whereIn('id', $validated['selected_offerings'])
                    ->update(['id_job' => $hrjob->id]);
            }
        }

        $hrjob->save();

        return redirect()->route('getHrjob')->with('message', 'Job status updated successfully!');
    }

}
