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
use App\Models\Form;
use App\Models\FormHrjob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HrjobAdminController extends Controller
{
    public function getHrjob()
    {
        // Tentukan logika penyaringan berdasarkan role pengguna
        if (Auth::user()->role === 'hiring_manager') {
            $hrjobs = Hrjob::with('user', 'category', 'city', 'outlet', 'offerings')
                ->whereHas('user', function ($query) {
                    $query->where('role', '!=', 'super_admin');
                })
                ->get();
        } elseif (Auth::user()->role === 'recruiter') {
            $hrjobs = Hrjob::with('user', 'category', 'city', 'outlet', 'offerings')
                ->whereHas('user', function ($query) {
                    $query->where('id', Auth::id());
                })
                ->get();
        } else {
            $hrjobs = Hrjob::with('user', 'category', 'city', 'outlet', 'offerings')->get();
        }

        $offerings = Offering::with('hrjob', 'userHrjob')->get();

        return view('admin.hrjob.index', compact('hrjobs', 'offerings'));
    }

    public function addHrjob()
    {
        $users = User::whereNotIn('role', ['applicant', 'interviewer'])->get();
        $hrjobcategories = HrjobCategory::all();
        $cities = City::all();
        $outlets = Outlet::all();
        $forms = Form::all();
        return view('admin.hrjob.store', compact('users','hrjobcategories', 'cities', 'outlets', 'forms'));
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
            'job_report' => 'nullable|string',
            'price' => 'nullable',
            'hide_salary' => 'nullable|boolean',
            'description' => 'nullable|string',
            'qualification' => 'nullable|string',
            'location_type' => 'required|in:on_site,hybrid,remote',
            'experience_min' => 'required|string|max:255',
            'education_min' => 'required|string|max:255',
            'expired' => 'required',
            'number_hired' => 'required',
            'id_form' => 'nullable|array',
            'id_form.*' => 'exists:forms,id',
        ]);

        $loggedInUser = Auth::user();

        // Jika user yang login adalah recruiter
        if ($loggedInUser->role === 'recruiter') {
            session()->flash('toast_type', 'failed');
            session()->flash('toast_message', 'Recruiters are not allowed to create jobs.');
            return redirect()->route('getHrjob');
        }

        // Jika user yang login adalah hiring_manager
        if ($loggedInUser->role === 'hiring_manager') {
            $selectedUser = User::find($validated['id_user']);
            if ($selectedUser && $selectedUser->role === 'super_admin') {
                session()->flash('toast_type', 'failed');
                session()->flash('toast_message', 'You cannot manage job for Super Admin.');
                return redirect()->route('getHrjob');
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

        if (!empty($validated['id_form'])) {
            foreach ($validated['id_form'] as $formId) {
                FormHrjob::create([
                    'id_job' => $hrjob->id,
                    'id_form' => $formId,
                ]);
            }
        }

        session()->flash('toast_type', 'success');
        session()->flash('toast_message', 'Job Added Successfully!');
        return redirect()->route('getHrjob');
    }

    public function editHrjob($id)
    {
        $hrjob = Hrjob::findOrFail($id);
        $users = User::where('role', '!=', 'applicant')->get();
        $hrjobcategories = HrjobCategory::all();
        $cities = City::all();
        $outlets = Outlet::all();
        $forms = Form::all();
        $selectedForms = FormHrjob::where('id_job', $id)->pluck('id_form')->toArray();

        return view('admin.hrjob.update', compact('hrjob', 'users', 'hrjobcategories', 'cities', 'outlets', 'forms', 'selectedForms'));
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
            'job_report' => 'nullable|string',
            'price' => 'nullable',
            'hide_salary' => 'nullable|boolean',
            'description' => 'nullable|string',
            'qualification' => 'nullable|string',
            'location_type' => 'required|in:on_site,hybrid,remote',
            'experience_min' => 'required|string|max:255',
            'education_min' => 'required|string|max:255',
            'expired' => 'required',
            'number_hired' => 'required',
            'is_ended' => 'nullable|in:yes,no',
            'hiring_cost' => 'nullable',
            'job_closed' => 'nullable',
            'id_form' => 'nullable|array',
            'id_form.*' => 'exists:forms,id',
        ]);

        $loggedInUser = Auth::user();

        // Jika user yang login adalah recruiter
        if ($loggedInUser->role === 'recruiter') {
            session()->flash('toast_type', 'failed');
            session()->flash('toast_message', 'Recruiters are not allowed to update jobs.');
            return redirect()->route('getHrjob');
        }

        // Jika user yang login adalah hiring_manager
        if ($loggedInUser->role === 'hiring_manager') {
            $selectedUser = User::find($validated['id_user']);
            if ($selectedUser && $selectedUser->role === 'super_admin') {
                session()->flash('toast_type', 'failed');
                session()->flash('toast_message', 'You cannot manage job for Super Admin.');
                return redirect()->route('getHrjob');
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

        FormHrjob::where('id_job', $id)->delete();
        if (!empty($validated['id_form'])) {
            foreach ($validated['id_form'] as $formId) {
                FormHrjob::create([
                    'id_job' => $hrjob->id,
                    'id_form' => $formId,
                ]);
            }
        }

        session()->flash('toast_type', 'success');
        session()->flash('toast_message', 'Job Updated Successfully!');
        return redirect()->route('getHrjob');
    }

    public function destroyHrjob($id)
    {
        $hrjob = Hrjob::findOrFail($id);

        $loggedInUser = Auth::user();

        // Jika user yang login adalah recruiter
        if ($loggedInUser->role === 'recruiter') {
            session()->flash('toast_type', 'failed');
            session()->flash('toast_message', 'Recruiters are not allowed to delete jobs.');
            return redirect()->route('getHrjob');
        }

        // Jika user yang login adalah hiring_manager
        if ($loggedInUser->role === 'hiring_manager') {
            $selectedUser = User::find($validated['id_user']);
            if ($selectedUser && $selectedUser->role === 'super_admin') {
                session()->flash('toast_type', 'failed');
                session()->flash('toast_message', 'You cannot manage job for Super Admin.');
                return redirect()->route('getHrjob');
            }
        }

        $hrjob->delete();

        session()->flash('toast_type', 'success');
        session()->flash('toast_message', 'Job Deleted Successfully!');
        return redirect()->route('getHrjob');
    }

    public function updateIsEnded(Request $request, $id)
    {
        $hrjob = Hrjob::findOrFail($id);

        $loggedInUser = Auth::user();

        try {
        // Jika user yang login adalah recruiter
        if ($loggedInUser->role === 'recruiter') {
            $selectedUser = User::find($validated['id_user']);
            if ($selectedUser && in_array($selectedUser->role, ['super_admin', 'hiring_manager'])) {
                return response()->json(['message' => 'You cannot manage job for Super Admin & Hiring Manager.'], 403);
            }
        }

        // Jika user yang login adalah hiring_manager
        if ($loggedInUser->role === 'hiring_manager') {
            $selectedUser = User::find($validated['id_user']);
            if ($selectedUser && $selectedUser->role === 'super_admin') {
                return response()->json(['message' => 'You cannot manage job for Super Admin.'], 403);
            }
        }

        // Validasi input
        $validated = $request->validate([
            'is_ended' => 'required|in:yes,no',
            'hiring_cost' => 'required_if:is_ended,yes|numeric',
            'selected_offerings' => 'array',
            'selected_offerings.*' => 'exists:offerings,id',
        ]);

        $hrjob->is_ended = $validated['is_ended'];
        if ($validated['is_ended'] === 'yes') {
            $hrjob->hiring_cost = $validated['hiring_cost'];

            // Update kandidat terpilih
            if (!empty($validated['selected_offerings'])) {
                // Hapus `id_job` untuk kandidat sebelumnya (jika ada)
                Offering::where('id_job', $hrjob->id)->update(['id_job' => null]);

                // Tambahkan kandidat baru ke pekerjaan ini
                Offering::whereIn('id', $validated['selected_offerings'])
                    ->update(['id_job' => $hrjob->id]);
            }
        } else {
            // Reset jika pekerjaan tidak selesai
            $hrjob->hiring_cost = null;

            // Hapus semua kandidat terkait
            Offering::where('id_job', $hrjob->id)->update(['id_job' => null]);
        }

        $hrjob->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Job status updated successfully!',
            'updatedRow' => [
                'id' => $hrjob->id,
                'is_ended' => $hrjob->is_ended,
                'hiring_cost' => $hrjob->hiring_cost,
                'job_closed' => $hrjob->job_closed->format('Y-m-d H:i:s'),
                'selected_offerings' => Offering::where('id_job', $hrjob->id)->pluck('id'),
            ],
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            return response()->json(['message' => implode(' ', $errors)], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }

}
