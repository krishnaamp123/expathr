<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserHrjob;
use App\Models\Hrjob;
use App\Models\HrjobCategory;
use App\Models\Answer;
use App\Models\Form;
use App\Models\Interview;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    public function getVacancy()
    {
        $vacancies = Hrjob::with('category', 'city')
                          ->where('is_active', 'yes')
                          ->orderBy('created_at', 'desc')
                          ->get();

        // Cek kelengkapan profil pengguna
        $user = auth()->user();
        $isProfileComplete = $user->workLocation->count() > 0 && $user->emergency->count() > 0 && $user->language->count() > 0 &&
                        $user->skill->count() > 0 && $user->workField->count() > 0 &&
                        $user->education->count() > 0 && $user->experience->count() > 0;

        return view('user.vacancy.index', compact('vacancies', 'isProfileComplete'));
    }

    public function getMyVacancy(Request $request)
    {
        // Ambil parameter status dari URL
        $status = $request->query('status');

        // Filter data berdasarkan status jika parameter ada
        $userhrjobs = UserHrjob::with(['hrjob', 'user', 'answers'])
            ->where('id_user', Auth::id())
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();

        $userJobIds = $userhrjobs->pluck('id_job')->toArray();
        $formsByJob = Form::whereIn('id_job', $userJobIds)
            ->get()
            ->groupBy('id_job');

        // Tampilkan ke view dengan semua status untuk digunakan di topbar
        $statuses = [
            'applicant', 'shortlist', 'phone_screen', 'hr_interview',
            'user_interview', 'skill_test', 'reference_check',
            'offering', 'rejected', 'hired'
        ];

        // Tambahkan logika untuk mendapatkan interviews
        $userId = Auth::id();
        $interviews = Interview::whereHas('userHrjob', function ($query) use ($userId) {
                $query->where('id_user', $userId);
            })
            ->with('userHrjob.hrjob')
            ->get();

        return view('user.myvacancy.index', compact('userhrjobs', 'statuses', 'status', 'formsByJob', 'interviews'));
    }

    public function storeVacancy(Request $request)
    {
        $validated = $request->validate([
            'id_job' => 'required|exists:hrjobs,id',
            'salary_expectation' => 'required|numeric',
            'availability' => 'required|in:immediately,<1_month_notice,1_month_notice,>1_month_notice',
        ]);

        $userId = auth()->id();

        // Cek apakah data pengguna lengkap
        $user = User::findOrFail($userId);

        $isDataComplete = $user->workLocation->count() > 0 && $user->emergency->count() > 0 && $user->language->count() > 0 &&
                        $user->skill->count() > 0 && $user->workField->count() > 0 &&
                        $user->education->count() > 0 && $user->experience->count() > 0;

        if (!$isDataComplete) {
            return redirect()->route('getVacancy')->with('error', 'Please complete your profile before applying for a job.');
        }

        // Cek apakah pengguna telah melamar pekerjaan ini
        if (UserHrjob::where('id_user', $userId)->where('id_job', $validated['id_job'])->exists()) {
            return redirect()->route('getVacancy')->with('error', 'You have already applied for this job.');
        }

        // Buat aplikasi baru jika data lengkap
        UserHrjob::create([
            'id_user' => $userId,
            'id_job' => $validated['id_job'],
            'salary_expectation' => $validated['salary_expectation'],
            'availability' => $validated['availability'],
        ]);

        return redirect()->route('getVacancy')->with('message', 'Job application submitted successfully.');
    }


    public function storeMyAnswer(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'id_user_job' => 'required|exists:user_hrjobs,id',
            'answers.*.id_form' => 'required|exists:forms,id',
            'answers.*.answer' => 'required|integer|min:1|max:5',
        ]);

        foreach ($validated['answers'] as $answerData) {
            Answer::create([
                'id_user_job' => $validated['id_user_job'],
                'id_form' => $answerData['id_form'],
                'answer' => $answerData['answer'],
            ]);
        }

        return redirect()->route('getMyVacancy')->with('message', 'Answers submitted successfully.');
    }
}
