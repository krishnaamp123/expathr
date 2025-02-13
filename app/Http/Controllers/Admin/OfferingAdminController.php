<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offering;
use App\Models\Hrjob;
use App\Models\UserHrjob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OfferingAdminController extends Controller
{
    public function getOffering(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $hrjobId = $request->query('id_job');

        // Tentukan role pengguna
        $role = Auth::user()->role;

        $offeringQuery = Offering::with('userHrjob.user', 'userHrjob.hrjob', 'userHrjob.interviews.interviewers', 'userHrjob.userinterviews.user_interviewers');

        if ($role === 'hiring_manager') {
            $offeringQuery->whereHas('userHrjob.hrjob.user', function ($query) {
                $query->where('role', '!=', 'super_admin');
                $query->whereHas('userHrjobs.interviews.interviewers', function ($subQuery) {
                    $subQuery->where('role', '!=', 'super_admin');
                })
                ->orWhereHas('userHrjobs.userinterviews.user_interviewers', function ($subQuery) {
                    $subQuery->where('role', '!=', 'super_admin');
                })
                ->orWhereDoesntHave('userHrjobs.interviews')
                ->orWhereDoesntHave('userHrjobs.userinterviews');
            });
        } elseif ($role === 'recruiter') {
            $offeringQuery->whereHas('userHrjob.hrjob.user', function ($query) {
                $query->where('role', 'recruiter');
                $query->whereHas('userHrjobs.interviews.interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                })
                ->orWhereHas('userHrjobs.userinterviews.user_interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                })
                ->orWhereDoesntHave('userHrjobs.interviews')
                ->orWhereDoesntHave('userHrjobs.userinterviews');
            });
        } elseif ($role === 'interviewer') {
            $offeringQuery->whereHas('userHrjob.hrjob.user', function ($query) {
                $query->whereHas('userHrjobs.interviews.interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                })
                ->orWhereHas('userHrjobs.userinterviews.user_interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                });
            });
        }

        if ($hrjobId) {
            $offeringQuery->whereHas('userHrjob', function ($query) use ($hrjobId) {
                $query->where('id_job', $hrjobId);
            });
        }

        if ($startDate) {
            $offeringQuery->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $offeringQuery->whereDate('created_at', '<=', $endDate);
        }

        $offerings = $offeringQuery->get();
        $userhrjobs = UserHrJob::with('user', 'hrjob')->get();
        $hrjobs = Hrjob::all();

        return view('admin.offering.index', compact('offerings', 'userhrjobs', 'hrjobs'));
    }

    public function storeOffering(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('toast_type', 'failed');
                session()->flash('toast_message', 'You are not authorized to manage this offering.');
                return back()->withInput();
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'id_job' => 'nullable',
                'file' => 'nullable|mimes:pdf|max:5120',
            ]);

            $offering = new Offering();

            // Periksa dan simpan file gambar jika ada
            if ($request->hasFile('file')) {
                // Generate nama file unik
                $filename = $this->generateRandomString();
                $extension = $request->file('file')->getClientOriginalExtension();
                $offeringName = $filename . '.' . $extension;

                // Tentukan lokasi penyimpanan di public/storage/offerings
                $destinationPath = public_path('storage/offerings');

                // Buat folder jika belum ada
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Pindahkan file ke lokasi tujuan
                $request->file('file')->move($destinationPath, $offeringName);

                // Simpan nama file ke database
                $offering->offering_file = 'storage/offerings/' . $offeringName;
            }

            $offering->id_user_job = $validated['id_user_job'];
            $offering->id_job = $validated['id_job'];

            $offering->save();

            session()->flash('toast_type', 'success');
            session()->flash('toast_message', 'Offering added successfully!');
            return back()->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            session()->flash('toast_type', 'failed');
            session()->flash('toast_message', implode(' ', $errors));
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            session()->flash('toast_type', 'failed');
            session()->flash('toast_message', 'An unexpected error occurred. Please try again.');
        }
        return back()->withInput();
    }

    public function updateOffering(Request $request, $id)
    {
        $offering = Offering::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to edit this offering.'], 403);
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'id_job' => 'nullable',
                'file' => 'nullable|mimes:pdf|max:5120',
            ]);

            if ($request->hasFile('file')) {
                $filename = $this->generateRandomString();
                $extension = $request->file('file')->getClientOriginalExtension();
                $offeringName = $filename . '.' . $extension;

                $destinationPath = public_path('storage/offerings');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $request->file('file')->move($destinationPath, $offeringName);

                if ($offering->offering_file) {
                    $oldFilePath = public_path($offering->offering_file);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                // Simpan nama file ke database
                $offering->offering_file = 'storage/offerings/' . $offeringName;
            }

            $offering->id_user_job = $validated['id_user_job'];
            $offering->id_job = $validated['id_job'];

            $offering->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Offering updated successfully!',
                'updatedRow' => [
                    'id' => $offering->id,
                    'id_user_job' => $offering->id_user_job,
                    'id_job' => $offering->id_job,
                    'offering_file' => $offering->offering_file,
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

    public function destroyOffering($id)
    {
        $offering = Offering::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to delete this offering.'], 403);
            }
            $offering->delete();
            return response()->json(['message' => 'Offering deleted successfully.'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            return response()->json(['message' => implode(' ', $errors)], 422);
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            return response()->json(['message' => 'An error occurred while deleting the offerings.'], 500);
        }
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
