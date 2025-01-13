<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SkillTest;
use App\Models\UserHrjob;
use Illuminate\Support\Facades\Auth;

class SkillTestAdminController extends Controller
{
    public function getSkillTest(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $hrjobId = $request->query('id_job');

        // Tentukan role pengguna
        $role = Auth::user()->role;

        $skillTestsQuery = SkillTest::with('userHrjob.user', 'userHrjob.hrjob', 'userHrjob.interviews.interviewers', 'userHrjob.userinterviews.user_interviewers');

        if ($role === 'hiring_manager') {
            $skillTestsQuery->whereHas('userHrjob.hrjob.user', function ($query) {
                $query->where('role', '!=', 'super_admin');
                $query->whereHas('userHrjob.interviews.interviewers', function ($subQuery) {
                    $subQuery->where('role', '!=', 'super_admin');
                })
                ->orWhereHas('userHrjob.userinterviews.user_interviewers', function ($subQuery) {
                    $subQuery->where('role', '!=', 'super_admin');
                })
                ->orWhereDoesntHave('userHrjob.interviews')
                ->orWhereDoesntHave('userHrjob.userinterviews');
            });
        } elseif ($role === 'recruiter') {
            $skillTestsQuery->whereHas('userHrjob.hrjob.user', function ($query) {
                $query->where('role', 'recruiter');
                $query->whereHas('userHrjob.interviews.interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                })
                ->orWhereHas('userHrjob.userinterviews.user_interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                })
                ->orWhereDoesntHave('userHrjob.interviews')
                ->orWhereDoesntHave('userHrjob.userinterviews');
            });
        } elseif ($role === 'interviewer') {
            $skillTestsQuery->whereHas('userHrjob.hrjob.user', function ($query) {
                $query->whereHas('userHrjob.interviews.interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                })
                ->orWhereHas('userHrjob.userinterviews.user_interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                });
            });
        }

        if ($hrjobId) {
            $skillTestsQuery->whereHas('userHrjob', function ($query) use ($hrjobId) {
                $query->where('id_job', $hrjobId);
            });
        }

        if ($startDate) {
            $skillTestsQuery->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $skillTestsQuery->whereDate('created_at', '<=', $endDate);
        }

        $skilltests = $skillTestsQuery->get();
        $userhrjobs = UserHrJob::with('user', 'hrjob')->get();

        return view('admin.skilltest.index', compact('skilltests', 'userhrjobs'));
    }

    public function storeSkillTest(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('toast_type', 'failed');
                session()->flash('toast_message', 'You are not authorized to rate this skill test.');
                return back()->withInput();
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'score' => 'required|integer|min:1|max:10',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
            ]);

            $interview = Interview::create([
                'id_user_job' => $request->id_user_job,
                'score' => $request->score,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            session()->flash('toast_type', 'success');
            session()->flash('toast_message', 'Skill Test rated successfully!');
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

    public function updateSkillTest(Request $request, $id)
    {
        $skilltest = SkillTest::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to edit this skill test.'], 403);
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'score' => 'nullable|integer|min:1|max:10',
                'rating' => 'nullable|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            $interview->update([
                'id_user_job' => $request->id_user_job,
                'score' => $request->score,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Interview updated successfully!',
                'updatedRow' => [
                    'id' => $skilltest->id,
                    'id_user_job' => $skilltest->id_user_job,
                    'score' => $skilltest->score,
                    'rating' => $skilltest->rating,
                    'comment' => $skilltest->comment,
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

    public function destroySkillTest($id)
    {
        $skilltest = SkillTest::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to delete this skill test.'], 403);
            }
            $interview->delete();
            return response()->json(['message' => 'Skill Test deleted successfully.'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            return response()->json(['message' => implode(' ', $errors)], 422);
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            return response()->json(['message' => 'An error occurred while deleting the skill tests.'], 500);
        }
    }

    public function exportSkillTest()
    {
        $skilltests = SkillTest::with(['userHrjob.hrjob', 'userHrjob.user'])
            ->whereHas('userHrjob', function ($query) {
                $query->where('status', 'skill_test');
            })
            ->get();

        // Buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tambahkan header untuk file Excel
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Applicant Name');
        $sheet->setCellValue('C1', 'Job Name');
        $sheet->setCellValue('D1', 'Location');
        $sheet->setCellValue('E1', 'Outlet');
        $sheet->setCellValue('F1', 'Applied At');
        $sheet->setCellValue('G1', 'Score');
        $sheet->setCellValue('H1', 'Rating');
        $sheet->setCellValue('I1', 'Comment');

        // Membuat header bold
        $headerRange = 'A1:I1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data dari database ke dalam file Excel
        $rowNumber = 2; // Baris pertama adalah header
        foreach ($userhrjobs as $userhrjob) {

            $sheet->setCellValue('A' . $rowNumber, $skilltests->id);
            $sheet->setCellValue('B' . $rowNumber, $skilltests->userHrjob->user->fullname ?? 'No Applicant');
            $sheet->setCellValue('C' . $rowNumber, $skilltests->userHrjob->hrjob->job_name ?? 'No Job');
            $sheet->setCellValue('D' . $rowNumber, $skilltests->userHrjob->hrjob->city->city_name  ?? 'No Location');
            $sheet->setCellValue('E' . $rowNumber, $skilltests->userHrjob->hrjob->outlet->outlet_name ?? 'No Outlet');
            $sheet->setCellValue('F' . $rowNumber, $skilltests->userHrjob->created_at ?? 'No Applied');
            $sheet->setCellValue('G' . $rowNumber, $skilltests->score ?? 'No Score');
            $sheet->setCellValue('H' . $rowNumber, $skilltests->rating ?? 'No Rating');
            $sheet->setCellValue('I' . $rowNumber, $skilltests->comment ?? 'No Comment');

            $rowNumber++;
        }

        // Simpan file dan buat response untuk download
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Konfigurasi headers
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="user_job.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportdateSkillTest(Request $request)
    {
        // Validasi input tanggal
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Filter data berdasarkan rentang tanggal dan status
        $skilltests = SkillTest::with(['userHrjob.hrjob', 'userHrjob.user'])
            ->whereBetween('created_at', [
                Carbon::parse($validated['start_date'])->startOfDay(),
                Carbon::parse($validated['end_date'])->endOfDay()
            ])
            ->whereHas('userHrjob', function ($query) {
                $query->where('status', 'skill_test');
            })
            ->get();

        // Membuat spreadsheet dan isi data
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tambahkan header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Applicant Name');
        $sheet->setCellValue('C1', 'Job Name');
        $sheet->setCellValue('D1', 'Location');
        $sheet->setCellValue('E1', 'Outlet');
        $sheet->setCellValue('F1', 'Applied At');
        $sheet->setCellValue('G1', 'Score');
        $sheet->setCellValue('H1', 'Rating');
        $sheet->setCellValue('I1', 'Comment');

        // Membuat header bold
        $headerRange = 'A1:I1'; // Range dari header
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data
        $rowNumber = 2;
        foreach ($userhrjobs as $userhrjob) {
            $sheet->setCellValue('A' . $rowNumber, $skilltests->id);
            $sheet->setCellValue('B' . $rowNumber, $skilltests->userHrjob->user->fullname ?? 'No Applicant');
            $sheet->setCellValue('C' . $rowNumber, $skilltests->userHrjob->hrjob->job_name ?? 'No Job');
            $sheet->setCellValue('D' . $rowNumber, $skilltests->userHrjob->hrjob->city->city_name  ?? 'No Location');
            $sheet->setCellValue('E' . $rowNumber, $skilltests->userHrjob->hrjob->outlet->outlet_name ?? 'No Outlet');
            $sheet->setCellValue('F' . $rowNumber, $skilltests->userHrjob->created_at ?? 'No Applied');
            $sheet->setCellValue('G' . $rowNumber, $skilltests->score ?? 'No Score');
            $sheet->setCellValue('H' . $rowNumber, $skilltests->rating ?? 'No Rating');
            $sheet->setCellValue('I' . $rowNumber, $skilltests->comment ?? 'No Comment');

            $rowNumber++;
        }

        // Simpan file dan buat response untuk download
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Konfigurasi headers
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="user_job_date.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
