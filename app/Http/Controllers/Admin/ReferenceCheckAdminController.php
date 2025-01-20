<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ReferenceCheck;
use App\Models\Reference;
use App\Models\UserHrjob;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReferenceCheckAdminController extends Controller
{
    public function getReferenceCheck(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $hrjobId = $request->query('id_job');

        // Tentukan role pengguna
        $role = Auth::user()->role;

        $referenceCheckQuery = ReferenceCheck::with('userHrjob.user', 'userHrjob.hrjob', 'userHrjob.interviews.interviewers', 'userHrjob.userinterviews.user_interviewers');

        if ($role === 'hiring_manager') {
            $referenceCheckQuery->whereHas('userHrjob.hrjob.user', function ($query) {
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
            $referenceCheckQuery->whereHas('userHrjob.hrjob.user', function ($query) {
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
            $referenceCheckQuery->whereHas('userHrjob.hrjob.user', function ($query) {
                $query->whereHas('userHrjobs.interviews.interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                })
                ->orWhereHas('userHrjobs.userinterviews.user_interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                });
            });
        }

        if ($hrjobId) {
            $referenceCheckQuery->whereHas('userHrjob', function ($query) use ($hrjobId) {
                $query->where('id_job', $hrjobId);
            });
        }

        if ($startDate) {
            $referenceCheckQuery->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $referenceCheckQuery->whereDate('created_at', '<=', $endDate);
        }

        $referencechecks = $referenceCheckQuery->get();
        $userhrjobs = UserHrJob::with('user', 'hrjob')->get();
        $userIds = $userhrjobs->pluck('id_user')->unique();
        $references = Reference::whereIn('id_user', $userIds)->get();

        return view('admin.referencecheck.index', compact('referencechecks', 'userhrjobs', 'references'));
    }

    public function storeReferenceCheck(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('toast_type', 'failed');
                session()->flash('toast_message', 'You are not authorized to manage this reference check.');
                return back()->withInput();
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'id_reference' => 'required',
                'comment' => 'nullable',
            ]);

            $referencecheck = ReferenceCheck::create([
                'id_user_job' => $request->id_user_job,
                'id_reference' => $request->id_reference,
                'comment' => $request->comment,
            ]);

            session()->flash('toast_type', 'success');
            session()->flash('toast_message', 'Reference Check added successfully!');
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

    public function updateReferenceCheck(Request $request, $id)
    {
        $referencecheck = ReferenceCheck::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to edit this reference check.'], 403);
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'id_reference' => 'required',
                'comment' => 'nullable',
            ]);

            $referencecheck->update([
                'id_user_job' => $request->id_user_job,
                'id_reference' => $request->id_reference,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Reference Check updated successfully!',
                'updatedRow' => [
                    'id' => $referencecheck->id,
                    'id_user_job' => $referencecheck->id_user_job,
                    'id_reference' => $referencecheck->id_reference,
                    'comment' => $referencecheck->comment,
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

    public function destroyReferenceCheck($id)
    {
        $referencecheck = ReferenceCheck::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to delete this reference check.'], 403);
            }
            $referencecheck->delete();
            return response()->json(['message' => 'Reference Check deleted successfully.'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            return response()->json(['message' => implode(' ', $errors)], 422);
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            return response()->json(['message' => 'An error occurred while deleting the reference checks.'], 500);
        }
    }

    public function exportReferenceCheck()
    {
        $referencechecks = ReferenceCheck::with(['userHrjob.hrjob', 'userHrjob.user'])
            ->whereHas('userHrjob', function ($query) {
                $query->where('status', 'reference_check');
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
        $sheet->setCellValue('E1', 'Reference Name');
        $sheet->setCellValue('F1', 'Relation');
        $sheet->setCellValue('G1', 'Company Name');
        $sheet->setCellValue('H1', 'Reference Phone');
        $sheet->setCellValue('I1', 'Can Be Called');
        $sheet->setCellValue('J1', 'Comment');

        // Membuat header bold
        $headerRange = 'A1:J1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data dari database ke dalam file Excel
        $rowNumber = 2; // Baris pertama adalah header
        foreach ($referencechecks as $referencecheck) {
            $applicantName = $referencecheck->userHrjob->user->fullname ?? 'No Applicant';
            $jobName = $referencecheck->userHrjob->hrjob->job_name ?? 'No Job';
            $city = $referencecheck->userHrjob->hrjob->city->city_name  ?? 'No Location';
            $referenceName = $referencecheck->reference->reference_name  ?? 'No Reference Name';
            $relation = $referencecheck->reference->relation  ?? 'No Relation';
            $companyName = $referencecheck->reference->company_name  ?? 'No Company Name';
            $referencePhone = $referencecheck->reference->phone  ?? 'No Reference Phone';
            $canBeCalled = $referencecheck->reference->is_call  ?? 'No Can Be Called';

            $sheet->setCellValue('A' . $rowNumber, $referencecheck->id);
            $sheet->setCellValue('B' . $rowNumber, $applicantName);
            $sheet->setCellValue('C' . $rowNumber, $jobName);
            $sheet->setCellValue('D' . $rowNumber, $city);
            $sheet->setCellValue('E' . $rowNumber, $referenceName);
            $sheet->setCellValue('F' . $rowNumber, $relation);
            $sheet->setCellValue('G' . $rowNumber, $companyName);
            $sheet->setCellValue('H' . $rowNumber, $referencePhone);
            $sheet->setCellValue('I' . $rowNumber, $canBeCalled);
            $sheet->setCellValue('J' . $rowNumber, $referencecheck->comment  ?? 'No Comment');

            $rowNumber++;
        }

        // Simpan file dan buat response untuk download
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Konfigurasi headers
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="reference_checks.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportdateReferenceCheck(Request $request)
    {
        // Validasi input tanggal
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Filter data berdasarkan rentang tanggal dan status
        $referencechecks = ReferenceCheck::with(['userHrjob.hrjob', 'userHrjob.user'])
            ->whereBetween('created_at', [
                Carbon::parse($validated['start_date'])->startOfDay(),
                Carbon::parse($validated['end_date'])->endOfDay()
            ])
            ->whereHas('userHrjob', function ($query) {
                $query->where('status', 'reference_check');
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
        $sheet->setCellValue('E1', 'Reference Name');
        $sheet->setCellValue('F1', 'Relation');
        $sheet->setCellValue('G1', 'Company Name');
        $sheet->setCellValue('H1', 'Reference Phone');
        $sheet->setCellValue('I1', 'Can Be Called');
        $sheet->setCellValue('J1', 'Comment');

        // Membuat header bold
        $headerRange = 'A1:J1'; // Range dari header
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data
        $rowNumber = 2;
        foreach ($referencechecks as $referencecheck) {
            $applicantName = $referencecheck->userHrjob->user->fullname ?? 'No Applicant';
            $jobName = $referencecheck->userHrjob->hrjob->job_name ?? 'No Job';
            $city = $referencecheck->userHrjob->hrjob->city->city_name  ?? 'No Location';
            $referenceName = $referencecheck->reference->reference_name  ?? 'No Reference Name';
            $relation = $referencecheck->reference->relation  ?? 'No Relation';
            $companyName = $referencecheck->reference->company_name  ?? 'No Company Name';
            $referencePhone = $referencecheck->reference->phone  ?? 'No Reference Phone';
            $canBeCalled = $referencecheck->reference->is_call  ?? 'No Can Be Called';

            $sheet->setCellValue('A' . $rowNumber, $referencecheck->id);
            $sheet->setCellValue('B' . $rowNumber, $applicantName);
            $sheet->setCellValue('C' . $rowNumber, $jobName);
            $sheet->setCellValue('D' . $rowNumber, $city);
            $sheet->setCellValue('E' . $rowNumber, $referenceName);
            $sheet->setCellValue('F' . $rowNumber, $relation);
            $sheet->setCellValue('G' . $rowNumber, $companyName);
            $sheet->setCellValue('H' . $rowNumber, $referencePhone);
            $sheet->setCellValue('I' . $rowNumber, $canBeCalled);
            $sheet->setCellValue('J' . $rowNumber, $referencecheck->comment  ?? 'No Comment');

            $rowNumber++;
        }

        // Simpan file dan buat response untuk download
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Konfigurasi headers
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="reference_checks_date.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
