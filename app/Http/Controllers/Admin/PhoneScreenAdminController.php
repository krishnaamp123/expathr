<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PhoneScreen;
use App\Models\UserHrjob;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PhoneScreenAdminController extends Controller
{
    public function getPhoneScreen(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $hrjobId = $request->query('id_job');

        // Tentukan role pengguna
        $role = Auth::user()->role;

        $phoneScreenQuery = PhoneScreen::with('userHrjob.user', 'userHrjob.hrjob', 'userHrjob.interviews.interviewers', 'userHrjob.userinterviews.user_interviewers');

        if ($role === 'hiring_manager') {
            $phoneScreenQuery->whereHas('userHrjob.hrjob.user', function ($query) {
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
            $phoneScreenQuery->whereHas('userHrjob.hrjob.user', function ($query) {
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
            $phoneScreenQuery->whereHas('userHrjob.hrjob.user', function ($query) {
                $query->whereHas('userHrjob.interviews.interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                })
                ->orWhereHas('userHrjob.userinterviews.user_interviewers', function ($subQuery) {
                    $subQuery->where('id_user', Auth::id());
                });
            });
        }

        if ($hrjobId) {
            $phoneScreenQuery->whereHas('userHrjob', function ($query) use ($hrjobId) {
                $query->where('id_job', $hrjobId);
            });
        }

        if ($startDate) {
            $phoneScreenQuery->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $phoneScreenQuery->whereDate('created_at', '<=', $endDate);
        }

        $phonescreens = $phoneScreenQuery->get();
        $userhrjobs = UserHrJob::with('user', 'hrjob')->get();

        return view('admin.phonescreen.index', compact('phonescreens', 'userhrjobs'));
    }

    public function storePhoneScreen(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('toast_type', 'failed');
                session()->flash('toast_message', 'You are not authorized to schedule this phone screen.');
                return back()->withInput();
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'phonescreen_date' => 'nullable',
                'time' => 'nullable',
            ]);

            $phonescreen = PhoneScreen::create([
                'id_user_job' => $request->id_user_job,
                'phonescreen_date' => $request->phonescreen_date,
                'time' => $request->time,
            ]);

            session()->flash('toast_type', 'success');
            session()->flash('toast_message', 'Phone Screen scheduled successfully!');
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

    public function updatePhoneScreen(Request $request, $id)
    {
        $phonescreen = PhoneScreen::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to edit this phone screen schedule.'], 403);
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'phonescreen_date' => 'nullable',
                'time' => 'nullable',
            ]);

            $phonescreen->update([
                'id_user_job' => $request->id_user_job,
                'phonescreen_date' => $request->phonescreen_date,
                'time' => $request->time,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Phone Screen updated successfully!',
                'updatedRow' => [
                    'id' => $phonescreen->id,
                    'id_user_job' => $phonescreen->id_user_job,
                    'phonescreen_date' => $phonescreen->phonescreen_date,
                    'time' => $phonescreen->time,
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

    public function destroyPhoneScreen($id)
    {
        $phonescreen = PhoneScreen::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to delete this phone screen schedule.'], 403);
            }
            $phonescreen->delete();
            return response()->json(['message' => 'Phone Screen deleted successfully.'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            return response()->json(['message' => implode(' ', $errors)], 422);
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            return response()->json(['message' => 'An error occurred while deleting the phone screens.'], 500);
        }
    }

    public function exportPhoneScreen()
    {
        $phonescreens = PhoneScreen::with(['userHrjob.hrjob', 'userHrjob.user'])
            ->whereHas('userHrjob', function ($query) {
                $query->where('status', 'phone_screen');
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
        $sheet->setCellValue('E1', 'Phone Screen Date');
        $sheet->setCellValue('F1', 'Phone Screen Time');
        $sheet->setCellValue('G1', 'Applicant Phone');
        $sheet->setCellValue('H1', 'Whatsapp Link');

        // Membuat header bold
        $headerRange = 'A1:H1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data dari database ke dalam file Excel
        $rowNumber = 2; // Baris pertama adalah header
        foreach ($phonescreens as $phonescreen) {
            $applicantName = $phonescreen->userHrjob->user->fullname ?? 'No Applicant';
            $jobName = $phonescreen->userHrjob->hrjob->job_name ?? 'No Job';
            $city = $phonescreen->userHrjob->hrjob->city->city_name  ?? 'No Location';
            $date = $phonescreen->phonescreen_date ?? 'No Date';
            $time = $phonescreen->time ?? 'No Time';
            $phone = $phonescreen->userHrjob->user->phone ?? 'No Phone';

            $sheet->setCellValue('A' . $rowNumber, $phonescreen->id);
            $sheet->setCellValue('B' . $rowNumber, $applicantName);
            $sheet->setCellValue('C' . $rowNumber, $jobName);
            $sheet->setCellValue('D' . $rowNumber, $city);
            $sheet->setCellValue('E' . $rowNumber, $date);
            $sheet->setCellValue('F' . $rowNumber, $time);
            $sheet->setCellValue('G' . $rowNumber, $phone);

            // Tambahkan formula HYPERLINK di kolom J
            $whatsappMessage = "Halo {$applicantName},

Thank you for applying to Expat. Roasters. Let us introduce ourselves as HR Expat. Roasters. We would like to invite you to join the Phone Screen Interview process for the position of {$jobName} ($city) at:

Date: {$date}
Time: {$time}
Platform: Whatsapp Call, around 10-15 minutes

Please confirm by sending the following format:
Full Name Present/Absent

Regards,

HR
Expat. Roasters";
            $whatsappLink = "https://api.whatsapp.com/send?phone=62{$phone}&text=" . urlencode($whatsappMessage);

            $sheet->setCellValue('H' . $rowNumber, "=HYPERLINK(\"{$whatsappLink}\", \"WHATSAPP\")");

            $rowNumber++;
        }

        // Simpan file dan buat response untuk download
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Konfigurasi headers
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="phone_screens.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportdatePhoneScreen(Request $request)
    {
        // Validasi input tanggal
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Filter data berdasarkan rentang tanggal dan status
        $phonescreens = PhoneScreen::with(['userHrjob.hrjob', 'userHrjob.user'])
            ->whereBetween('phonescreen_date', [$validated['start_date'], $validated['end_date']])
            ->whereHas('userHrjob', function ($query) {
                $query->where('status', 'phone_screen');
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
        $sheet->setCellValue('E1', 'Phone Screen Date');
        $sheet->setCellValue('F1', 'Phone Screen Time');
        $sheet->setCellValue('G1', 'Applicant Phone');
        $sheet->setCellValue('H1', 'Whatsapp Link');

        // Membuat header bold
        $headerRange = 'A1:H1'; // Range dari header
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data
        $rowNumber = 2;
        foreach ($phonescreens as $phonescreen) {
            $applicantName = $phonescreen->userHrjob->user->fullname ?? 'No Applicant';
            $jobName = $phonescreen->userHrjob->hrjob->job_name ?? 'No Job';
            $city = $phonescreen->userHrjob->hrjob->city->city_name  ?? 'No Location';
            $date = $phonescreen->phonescreen_date ?? 'No Date';
            $time = $phonescreen->time ?? 'No Time';
            $phone = $phonescreen->userHrjob->user->phone ?? 'No Phone';

            $sheet->setCellValue('A' . $rowNumber, $phonescreen->id);
            $sheet->setCellValue('B' . $rowNumber, $applicantName);
            $sheet->setCellValue('C' . $rowNumber, $jobName);
            $sheet->setCellValue('D' . $rowNumber, $city);
            $sheet->setCellValue('E' . $rowNumber, $date);
            $sheet->setCellValue('F' . $rowNumber, $time);
            $sheet->setCellValue('G' . $rowNumber, $phone);

            // Tambahkan formula HYPERLINK di kolom J
            $whatsappMessage = "Halo {$applicantName},

Thank you for applying to Expat. Roasters. Let us introduce ourselves as HR Expat. Roasters. We would like to invite you to join the Phone Screen Interview process for the position of {$jobName} ($city) at:

Date: {$date}
Time: {$time}
Platform: Whatsapp Call, around 10-15 minutes

Please confirm by sending the following format:
Full Name Present/Absent

Regards,

HR
Expat. Roasters";
            $whatsappLink = "https://api.whatsapp.com/send?phone=62{$phone}&text=" . urlencode($whatsappMessage);

            $sheet->setCellValue('H' . $rowNumber, "=HYPERLINK(\"{$whatsappLink}\", \"WHATSAPP\")");

            $rowNumber++;
        }

        // Simpan file dan buat response untuk download
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Konfigurasi headers
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="phone_screens_date.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
