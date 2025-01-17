<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserInterview;
use App\Models\UserHrjob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Mail;

class UserInterviewAdminController extends Controller
{
    public function getUserInterview(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $hrjobId = $request->query('id_job');

        $userinterviewsQuery = UserInterview::with('userHrjob', 'user_interviewers');

        if (Auth::user()->role === 'hiring_manager') {
            // Filter wawancara untuk hiring_manager
            $userinterviewsQuery->whereHas('user_interviewers', function ($query) {
                $query->where('role', '!=', 'super_admin');
            });
        } elseif (Auth::user()->role === 'recruiter') {
            $userinterviewsQuery->where(function ($query) {
                $query->whereHas('user_interviewers', function ($query) {
                    $query->where('id_user', Auth::id());
                })
                ->orWhereHas('userHrjob.hrjob', function ($query) {
                    $query->where('id_user', Auth::id());
                });
            });
        } elseif (Auth::user()->role === 'interviewer') {
            $userinterviewsQuery->where(function ($query) {
                $query->whereHas('user_interviewers', function ($query) {
                    $query->where('id_user', Auth::id());
                });
            });
        }

        if ($hrjobId) {
            $interviewsQuery->whereHas('userHrjob.hrjob', function ($query) use ($hrjobId) {
                $query->where('id', $hrjobId);
            });
        }

        if ($startDate) {
            $userinterviewsQuery->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $userinterviewsQuery->whereDate('created_at', '<=', $endDate);
        }

        // Ambil hasil query
        $userinterviews = $userinterviewsQuery->get();
        $userhrjobs = UserHrJob::with('user', 'hrjob')->get();
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.userinterview.index', compact('userinterviews', 'userhrjobs', 'users'));
    }

    public function storeUserInterview(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                session()->flash('toast_type', 'failed');
                session()->flash('toast_message', 'You are not authorized to create this user interview.');
                return back()->withInput();
            }

            if (Auth::user()->role === 'hiring_manager') {
                // Validasi: Cek apakah ada super_admin di daftar interviewer
                $superAdminExists = User::whereIn('id', $request->user_interviewers)
                    ->where('role', 'super_admin')
                    ->exists();

                if ($superAdminExists) {
                    session()->flash('toast_type', 'failed');
                    session()->flash('toast_message', 'You cannot manage user interviews for Super Admin.');
                    return back()->withInput();
                }
            }

            if (Auth::user()->role === 'recruiter') {
                $invalidRolesExist = User::whereIn('id', $request->user_interviewers)
                    ->whereIn('role', ['super_admin', 'hiring_manager'])
                    ->exists();

                if ($invalidRolesExist) {
                    session()->flash('toast_type', 'failed');
                    session()->flash('toast_message', 'You cannot manage user interviews for Super Admin and Hiring Manager.');
                    return back()->withInput();
                }
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'interview_date' => 'nullable',
                'time' => 'nullable',
                'location' => 'nullable',
                'link' => 'nullable',
                'user_interviewers' => 'required|array',
                'user_interviewers.*' => 'exists:users,id',
            ]);

            $userinterview = UserInterview::create([
                'id_user_job' => $request->id_user_job,
                'interview_date' => $request->interview_date,
                'time' => $request->time,
                'location' => $request->location,
                'link' => $request->link,
            ]);

            $userinterview->user_interviewers()->sync($request->user_interviewers);

            // Ambil data untuk email
            $jobName = $userinterview->userHrjob->hrjob->job_name ?? 'No Job';
            $applicantName = $userinterview->userHrjob->user->fullname ?? 'No Applicant';
            $interviewDate = $userinterview->interview_date ?? 'No Date';
            $time = $userinterview->time ?? 'No Time';
            $location = $userinterview->location ?? 'No Location';
            $link = $userinterview->link ?? 'No Link';

            // Kirim Email
            $emailData = [
                'job_name' => $jobName,
                'applicant_name' => $applicantName,
                'interview_date' => $interviewDate,
                'time' => $time,
                'location' => $location,
                'link' => $link,
            ];

            $recipientEmail = $userinterview->userHrjob->user->email; // Ganti dengan email tujuan yang sesuai
            Mail::send('admin.userinterview.email', $emailData, function ($message) use ($recipientEmail) {
                $message->to($recipientEmail)
                        ->subject('Interview Scheduled');
            });

            session()->flash('toast_type', 'success');
            session()->flash('toast_message', 'User Interview created successfully and Email sent successfully!');
            return back()->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            session()->flash('toast_type', 'failed');
            session()->flash('toast_message', implode(' ', $errors));
        } catch (\Exception $e) {
            session()->flash('toast_type', 'failed');
            session()->flash('toast_message', 'An unexpected error occurred. Please try again.');
        }

        return back()->withInput();
    }

    public function updateUserInterview(Request $request, $id)
    {
        $userinterview = UserInterview::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to edit this user interview.'], 403);
            }

            // Prevent hiring_manager from deleting interviews for super_admin
            if (Auth::user()->role === 'hiring_manager') {
                $superAdminExists = User::whereIn('id', $request->user_interviewers)
                    ->where('role', 'super_admin')
                    ->exists();

                if ($superAdminExists) {
                    return response()->json(['message' => 'You cannot manage user interviews for Super Admin.'], 403);
                }
            }

            if (Auth::user()->role === 'recruiter') {
                $invalidRolesExist = User::whereIn('id', $request->user_interviewers)
                    ->whereIn('role', ['super_admin', 'hiring_manager'])
                    ->exists();

                if ($invalidRolesExist) {
                    return response()->json(['message' => 'You cannot manage interviews for Super Admin or Hiring Manager.'], 403);
                }
            }


            $validated = $request->validate([
                'id_user_job' => 'required',
                'interview_date' => 'nullable',
                'time' => 'nullable',
                'location' => 'nullable',
                'link' => 'nullable',
                'arrival' => 'nullable',
                'rating' => 'nullable|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
                'user_interviewers' => 'required|array',
                'user_interviewers.*' => 'exists:users,id',
            ]);

            $userinterview->update([
                'id_user_job' => $request->id_user_job,
                'interview_date' => $request->interview_date,
                'time' => $request->time,
                'location' => $request->location,
                'link' => $request->link,
                'arrival' => $request->arrival,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            $userinterview->user_interviewers()->sync($request->user_interviewers);

            return response()->json([
                'status' => 'success',
                'message' => 'User Interview updated successfully!',
                'updatedRow' => [
                    'id' => $userinterview->id,
                    'id_user_job' => $userinterview->id_user_job,
                    'interview_date' => $userinterview->interview_date,
                    'time' => $userinterview->time,
                    'location' => $userinterview->location,
                    'link' => $userinterview->link,
                    'arrival' => $userinterview->arrival,
                    'rating' => $userinterview->rating,
                    'comment' => $userinterview->comment,
                    'updated_at' => $userinterview->updated_at->format('Y-m-d H:i:s'),
                    'user_interviewers' => $userinterview->user_interviewers->map(function ($user_interviewer) {
                        return ['id' => $user_interviewer->id, 'name' => $user_interviewer->fullname];
                    }),
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            return response()->json(['message' => implode(' ', $errors)], 422);
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            return response()->json(['message' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }

    public function updateUserRating(Request $request, $id)
    {
        $userinterview = UserInterview::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to rate this user interview.'], 403);
            }
            // Validasi input
            $validated = $request->validate([
                'rating' => 'nullable|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            // Perbarui rating dan komentar di tabel Interview
            $userinterview->rating = $validated['rating'];
            $userinterview->comment = $validated['comment'];
            $userinterview->save();

            // Perbarui status di tabel UserHrjob
            $userHrjob = $userinterview->userHrjob;
            if (!$userHrjob) {
                throw new \Exception('UserHrjob record not found.');
            }

            if ($request->button_action === 'reject') {
                $userHrjob->status = 'rejected';
                $userHrjob->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Status updated to Rejected!',
                    'updatedRow' => [
                        'id' => $userinterview->id,
                        'rating' => $userinterview->rating,
                        'comment' => $userinterview->comment,
                        'updated_at' => $userinterview->updated_at->format('Y-m-d H:i:s'),
                    ],
                ]);
            } elseif ($request->button_action === 'next') {
                $userHrjob->status = 'user_interview';
                $userHrjob->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Status updated to Skill Test!',
                    'modalType' => 'user_interview',
                    'modalData' => [
                        'userJobId' => $userHrjob->id,
                        'userJobName' => $userhrjob->user->fullname,
                    ],
                    'updatedRow' => [
                        'id' => $userinterview->id,
                        'rating' => $userinterview->rating,
                        'comment' => $userinterview->comment,
                        'updated_at' => $userinterview->updated_at->format('Y-m-d H:i:s'),
                    ],
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Rating updated successfully!',
                    'updatedRow' => [
                        'id' => $userinterview->id,
                        'rating' => $userinterview->rating,
                        'comment' => $userinterview->comment,
                        'updated_at' => $userinterview->updated_at->format('Y-m-d H:i:s'),
                    ],
                ]);
            }

            $userHrjob->save();

            // Pesan sukses
            session()->flash('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            session()->flash('failed', implode(' ', $errors));
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            session()->flash('failed', 'An unexpected error occurred. Please try again.');
        }

        return back()->withInput(); // Kembali ke posisi semula dengan input
    }

    public function destroyUserInterview($id)
    {
        $userinterview = UserInterview::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager', 'recruiter'])) {
                return response()->json(['message' => 'You are not authorized to delete this user interview.'], 403);
            }

            // Validasi untuk hiring_manager
            if (Auth::user()->role === 'hiring_manager') {
                // Cek apakah ada Super Admin di daftar interviewer
                $superAdminExists = $userinterview->user_interviewers()->where('role', 'super_admin')->exists();

                if ($superAdminExists) {
                    return response()->json(['message' => 'You cannot manage interviews for Super Admin.'], 403);
                }
            }

            // Validasi untuk recruiter
            if (Auth::user()->role === 'recruiter') {
                $invalidRolesExist = $userinterview->user_interviewers()->whereIn('role', ['super_admin', 'hiring_manager'])->exists();

                if ($invalidRolesExist) {
                    return response()->json(['message' => 'You cannot manage interviews for Super Admin & Hiring Manager.'], 403);
                }
            }

            $userinterview->delete();

            return response()->json(['message' => 'User interview deleted successfully.'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gabungkan semua pesan validasi
            $errors = [];
            foreach ($e->errors() as $fieldErrors) {
                $errors = array_merge($errors, $fieldErrors);
            }
            return response()->json(['message' => implode(' ', $errors)], 422);
        } catch (\Exception $e) {
            // Pesan error untuk kesalahan umum
            return response()->json(['message' => 'An error occurred while deleting the interviews.'], 500);
        }
    }

    public function exportUserInterview()
    {
        // Ambil data dari model UserInterview dengan status user_interview
        $interviews = UserInterview::with(['userHrjob.hrjob', 'userHrjob.user', 'user_interviewers'])
            ->whereHas('userHrjob', function ($query) {
                $query->where('status', 'user_interview');
            })
            ->get();

        // Buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tambahkan header untuk file Excel
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Applicant Name');
        $sheet->setCellValue('C1', 'Interviewer Name');
        $sheet->setCellValue('D1', 'Job Name');
        $sheet->setCellValue('E1', 'Location');
        $sheet->setCellValue('F1', 'Outlet');
        $sheet->setCellValue('G1', 'Interview Date');
        $sheet->setCellValue('H1', 'Interview Time');
        $sheet->setCellValue('I1', 'Location Interview');
        $sheet->setCellValue('J1', 'Interview Link');
        $sheet->setCellValue('K1', 'Applicant Phone');
        $sheet->setCellValue('L1', 'Whatsapp Link');
        $sheet->setCellValue('M1', 'Confirm Attendance');

        // Membuat header bold
        $headerRange = 'A1:M1'; // Range dari header
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data dari database ke dalam file Excel
        $rowNumber = 2; // Baris pertama adalah header
        foreach ($interviews as $interview) {
            $applicantName = $interview->userHrjob->user->fullname ?? 'No Applicant';
            $jobName = $interview->userHrjob->hrjob->job_name ?? 'No Job';
            $date = $interview->interview_date ?? 'No Date';
            $time = $interview->time ?? 'No Time';
            $location = $interview->location ?? 'No Location';
            $link = $interview->link ?? 'No Link';
            $phone = $interview->userHrjob->user->phone ?? 'No Phone';
            $city = $interview->userHrjob->hrjob->city->city_name  ?? 'No Location';
            $interviewerNames = $interview->user_interviewers->pluck('fullname')->implode(', ') ?: 'No Interviewers';

            $sheet->setCellValue('A' . $rowNumber, $interview->id);
            $sheet->setCellValue('B' . $rowNumber, $applicantName);
            $sheet->setCellValue('C' . $rowNumber, $interviewerNames);
            $sheet->setCellValue('D' . $rowNumber, $jobName);
            $sheet->setCellValue('E' . $rowNumber, $city);
            $sheet->setCellValue('F' . $rowNumber, $interview->userHrjob->hrjob->outlet->outlet_name ?? 'No Outlet');
            $sheet->setCellValue('G' . $rowNumber, $date);
            $sheet->setCellValue('H' . $rowNumber, $time);
            $sheet->setCellValue('I' . $rowNumber, $location);
            $sheet->setCellValue('J' . $rowNumber, $link);
            $sheet->setCellValue('K' . $rowNumber, $phone);
            $sheet->setCellValue('M' . $rowNumber, $interview->arrival ?? 'No Confirm');

            // Tambahkan formula HYPERLINK di kolom J
            $whatsappMessage = "Halo {$applicantName},

Thank you for applying to Expat. Roasters. Let us introduce ourselves as HR Expat. Roasters. We would like to invite you to join the User Interview process for the position of {$jobName} ($city) at:

Date: {$date}
Time: {$time}
Location: {$location}
Link: {$link}

Please confirm your attendance via our website on the following page:
http://127.0.0.1:8000/user/myjob/get?status=hr_interview

Regards,

HR
Expat. Roasters";
            $whatsappLink = "https://api.whatsapp.com/send?phone=62{$phone}&text=" . urlencode($whatsappMessage);

            $sheet->setCellValue('L' . $rowNumber, "=HYPERLINK(\"{$whatsappLink}\", \"WHATSAPP\")");

            $rowNumber++;
        }

        // Simpan file dan buat response untuk download
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Konfigurasi headers
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="user_interviews.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportdateUserInterview(Request $request)
    {
        // Validasi input tanggal
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Filter data berdasarkan rentang tanggal dan status
        $interviews = UserInterview::with(['userHrjob.hrjob', 'userHrjob.user', 'user_interviewers'])
            ->whereBetween('interview_date', [$validated['start_date'], $validated['end_date']])
            ->whereHas('userHrjob', function ($query) {
                $query->where('status', 'user_interview');
            })
            ->get();

        // Membuat spreadsheet dan isi data
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tambahkan header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Applicant Name');
        $sheet->setCellValue('C1', 'Interviewer Name');
        $sheet->setCellValue('D1', 'Job Name');
        $sheet->setCellValue('E1', 'Location');
        $sheet->setCellValue('F1', 'Outlet');
        $sheet->setCellValue('G1', 'Interview Date');
        $sheet->setCellValue('H1', 'Interview Time');
        $sheet->setCellValue('I1', 'Location Interview');
        $sheet->setCellValue('J1', 'Interview Link');
        $sheet->setCellValue('K1', 'Applicant Phone');
        $sheet->setCellValue('L1', 'Whatsapp Link');
        $sheet->setCellValue('M1', 'Confirm Attendance');

        // Membuat header bold
        $headerRange = 'A1:M1'; // Range dari header
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data
        $rowNumber = 2;
        foreach ($interviews as $interview) {
            $applicantName = $interview->userHrjob->user->fullname ?? 'No Applicant';
            $jobName = $interview->userHrjob->hrjob->job_name ?? 'No Job';
            $date = $interview->interview_date ?? 'No Date';
            $time = $interview->time ?? 'No Time';
            $location = $interview->location ?? 'No Location';
            $link = $interview->link ?? 'No Link';
            $phone = $interview->userHrjob->user->phone ?? 'No Phone';
            $city = $interview->userHrjob->hrjob->city->city_name  ?? 'No Location';
            $interviewerNames = $interview->user_interviewers->pluck('fullname')->implode(', ') ?: 'No Interviewers';

            $sheet->setCellValue('A' . $rowNumber, $interview->id);
            $sheet->setCellValue('B' . $rowNumber, $applicantName);
            $sheet->setCellValue('C' . $rowNumber, $interviewerNames);
            $sheet->setCellValue('D' . $rowNumber, $jobName);
            $sheet->setCellValue('E' . $rowNumber, $city);
            $sheet->setCellValue('F' . $rowNumber, $interview->userHrjob->hrjob->outlet->outlet_name ?? 'No Outlet');
            $sheet->setCellValue('G' . $rowNumber, $date);
            $sheet->setCellValue('H' . $rowNumber, $time);
            $sheet->setCellValue('I' . $rowNumber, $location);
            $sheet->setCellValue('J' . $rowNumber, $link);
            $sheet->setCellValue('K' . $rowNumber, $phone);
            $sheet->setCellValue('M' . $rowNumber, $interview->arrival ?? 'No Confirm');

            // Tambahkan formula HYPERLINK di kolom J
            $whatsappMessage = "Halo {$applicantName},

Thank you for applying to Expat. Roasters. Let us introduce ourselves as HR Expat. Roasters. We would like to invite you to join the User Interview process for the position of {$jobName} ($city) at:

    Date: {$date}
    Time: {$time}
    Location: {$location}
    Link: {$link}

    Please confirm your attendance via our website on the following page:
    http://127.0.0.1:8000/user/myjob/get?status=hr_interview

    Regards,

    HR
    Expat. Roasters";
            $whatsappLink = "https://api.whatsapp.com/send?phone=62{$phone}&text=" . urlencode($whatsappMessage);

            $sheet->setCellValue('L' . $rowNumber, "=HYPERLINK(\"{$whatsappLink}\", \"WHATSAPP\")");

            $rowNumber++;
        }

        // Simpan file dan buat response untuk download
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Konfigurasi headers
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="user_interviews_date.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
