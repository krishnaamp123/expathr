<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserHrjob;
use App\Models\Hrjob;
use App\Models\HrjobCategory;
use App\Models\Answer;
use App\Models\FormHrjob;
use App\Models\Interview;
use App\Models\UserInterview;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VacancyController extends Controller
{
    public function getVacancy()
    {
        $vacancies = Hrjob::with('category', 'city')
                          ->where('is_ended', 'no')
                          ->orderBy('created_at', 'desc')
                          ->get();

        // Cek kelengkapan profil pengguna
        $user = auth()->user();
        $isProfileComplete = $user->workLocation->count() > 0 && $user->emergency->count() > 0 && $user->language->count() > 0 &&
                        $user->workSkill->count() > 0 && $user->workField->count() > 0 && $user->source->count() > 0 &&
                        $user->education->count() > 0 && $user->reference->count() >= 2 && $user->experience->count() > 0;

        return view('user.vacancy.index', compact('vacancies', 'isProfileComplete'));
    }

    public function getMyVacancy(Request $request)
    {
        \Log::info('getmyvacancy function is running');

        // Ambil parameter status dari URL
        $status = $request->query('status');

        // Filter data berdasarkan status jika parameter ada
        $userhrjobs = UserHrjob::with(['hrjob', 'user'])
            ->where('id_user', Auth::id())
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();

        $userJobIds = $userhrjobs->pluck('id_job')->toArray();
        // Ambil forms beserta relasi questions & answers
        $formsByJob = FormHrjob::whereIn('id_job', $userJobIds)
        ->with(['form.questions.answers'])
        ->get()
        ->groupBy('id_job');

        // Tampilkan ke view dengan semua status untuk digunakan di topbar
        $statuses = [
            'applicant', 'shortlist', 'phone_screen', 'hr_interview',
            'user_interview', 'skill_test', 'reference_check',
            'offering', 'rejected', 'hired'
        ];

        return view('user.myvacancy.index', compact('userhrjobs', 'statuses', 'status', 'formsByJob'));
    }

    public function storeVacancy(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_job' => 'required|exists:hrjobs,id',
                'salary_expectation' => 'required|numeric',
                'availability' => 'required|in:immediately,<1_month_notice,1_month_notice,>1_month_notice',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('getVacancy')
                ->with('error', implode( $e->validator->errors()->all())); // Gabungkan semua error
        }

        $userId = auth()->id();

        // Cek apakah data pengguna lengkap
        $user = User::findOrFail($userId);

        $isDataComplete = $user->workLocation->count() > 0 && $user->emergency->count() > 0 && $user->language->count() > 0 &&
                        $user->workSkill->count() > 0 && $user->workField->count() > 0 && $user->source->count() > 0 &&
                        $user->education->count() > 0 && $user->reference->count() >= 2 && $user->experience->count() > 0;

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

        return redirect()->route('getMyVacancy')->with('message', 'Job application successfully submitted, please fill in the required assessment to proceed.');
    }

    public function storeMyAnswer(Request $request)
    {
        $validatedData = $request->validate([
            'id_user_job' => 'required|exists:user_hrjobs,id',
            'answers' => 'required|array',
            'answers.*' => 'required|exists:answers,id',
        ]);

        $idUserJob = $validatedData['id_user_job'];
        $userId = Auth::id();

        foreach ($validatedData['answers'] as $questionId => $answerId) {
            UserAnswer::updateOrCreate(
                [
                    'id_user' => $userId,
                    'id_user_job' => $idUserJob,
                    'id_question' => $questionId,
                ],
                [
                    'id_answer' => $answerId,
                ]
            );
        }

        return redirect()->route('getMyVacancy')->with('message', 'Answers submitted successfully.');
    }

    public function confirmArrival(Request $request, Interview $interview)
    {
        // Periksa apakah user memiliki akses ke interview ini
        if ($interview->userhrjob->id_user !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }

        // Update nilai arrival menjadi 'yes'
        $interview->update(['arrival' => 'yes']);

        return redirect()->back()->with('message', 'Attendance confirmed successfully.');
    }

    public function confirmUserArrival(Request $request, UserInterview $userinterview)
    {
        // Periksa apakah user memiliki akses ke interview ini
        if ($userinterview->userhrjob->id_user !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }

        // Update nilai arrival menjadi 'yes'
        $userinterview->update(['arrival' => 'yes']);

        return redirect()->back()->with('message', 'Attendance confirmed successfully.');
    }
}
