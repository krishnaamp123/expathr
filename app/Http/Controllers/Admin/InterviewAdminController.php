<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\UserHrjob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Mail;

class InterviewAdminController extends Controller
{
    public function getInterview(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $interviewsQuery = Interview::with('userHrjob', 'user');

        if (Auth::user()->role === 'hiring_manager') {
            // Filter wawancara untuk hiring_manager
            $interviewsQuery->whereHas('user', function ($query) {
                $query->where('role', '!=', 'super_admin');
            });
        } elseif (Auth::user()->role === 'recruiter') {
            // Filter wawancara untuk recruiter
            $interviewsQuery->whereHas('user', function ($query) {
                $query->whereNotIn('role', ['super_admin', 'hiring_manager']);
            });
        }

        // Tambahkan filter tanggal jika tersedia
        if ($startDate) {
            $interviewsQuery->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $interviewsQuery->whereDate('created_at', '<=', $endDate);
        }

        // Ambil hasil query
        $interviews = $interviewsQuery->get();
        $userhrjobs = UserHrJob::with('user', 'hrjob')->get();
        $users = User::where('role', '!=', 'applicant')->get();

        return view('admin.interview.index', compact('interviews', 'userhrjobs', 'users'));
    }

    public function storeInterview(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
                session()->flash('failed', 'You are not authorized to create this interview.');
                return back()->withInput();
            }

            // Prevent hiring_manager from adding interviews for super_admin
            $user = User::findOrFail($request->id_user);
            if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
                session()->flash('failed', 'You cannot manage interviews for Super Admin.');
                return back()->withInput();
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'id_user' => 'required',
                'interview_date' => 'nullable',
                'time' => 'nullable',
                'location' => 'nullable',
                'link' => 'nullable',
            ]);

            $interview = Interview::create([
                'id_user_job' => $request->id_user_job,
                'id_user' => $request->id_user,
                'interview_date' => $request->interview_date,
                'time' => $request->time,
                'location' => $request->location,
                'link' => $request->link,
            ]);

            // Ambil data untuk email
            $jobName = $interview->userHrjob->hrjob->job_name ?? 'No Job';
            $applicantName = $interview->userHrjob->user->fullname ?? 'No Applicant';
            $interviewDate = $interview->interview_date ?? 'No Date';
            $time = $interview->time ?? 'No Time';
            $location = $interview->location ?? 'No Location';
            $link = $interview->link ?? 'No Link';

            // Kirim Email
            $emailData = [
                'job_name' => $jobName,
                'applicant_name' => $applicantName,
                'interview_date' => $interviewDate,
                'time' => $time,
                'location' => $location,
                'link' => $link,
            ];

            $recipientEmail = $interview->userHrjob->user->email; // Ganti dengan email tujuan yang sesuai
            Mail::send('admin.interview.email', $emailData, function ($message) use ($recipientEmail) {
                $message->to($recipientEmail)
                        ->subject('Interview Scheduled');
            });

            session()->flash('success', 'Interview created successfully & Email sent successfully!');
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

        return back()->withInput();
    }

    public function updateInterview(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        try {

            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
                session()->flash('failed', 'You are not authorized to edit this interview.');
                return back()->withInput();
            }

            // Prevent hiring_manager from updating interviews for super_admin
            $user = User::findOrFail($request->id_user);
            if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
                session()->flash('failed', 'You cannot manage interviews for Super Admin.');
                return back()->withInput();
            }

            $validated = $request->validate([
                'id_user_job' => 'required',
                'id_user' => 'required',
                'interview_date' => 'nullable',
                'time' => 'nullable',
                'location' => 'nullable',
                'link' => 'nullable',
                'arrival' => 'nullable',

            ]);

            $interview->id_user_job = $request->id_user_job;
            $interview->id_user = $request->id_user;
            $interview->interview_date = $request->interview_date;
            $interview->time = $request->time;
            $interview->location = $request->location;
            $interview->link = $request->link;
            $interview->arrival = $request->arrival;

            $interview->save();

            session()->flash('success', 'Interview updated successfully!');
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

        return back()->withInput();
    }

    public function updateRating(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        try {
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
            ]);

            $interview->rating = $validated['rating'];
            $interview->comment = $validated['comment'];
            $interview->save();

            // Pesan sukses
            session()->flash('success', 'Rating updated successfully!');
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

    public function destroyInterview($id)
    {
        $interview = Interview::findOrFail($id);

        try {
            if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
                session()->flash('failed', 'You are not authorized to delete this interview.');
                return back()->withInput();
            }

            // Prevent hiring_manager from deleting interviews for super_admin
            $user = User::findOrFail($interview->id_user);
            if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
                session()->flash('failed', 'You cannot manage user interviews for Super Admin.');
                return back()->withInput();
            }

            $interview->delete();
            session()->flash('success', 'Interview deleted successfully!');
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

        return back()->withInput();
    }

    public function exportInterview()
    {
        // Ambil data dari model Interview
        $interviews = Interview::with(['userHrjob.hrjob', 'userHrjob.user', 'user'])->get();

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
            $phone = $interview->userHrjob->user->phone ?? 'No Phone';
            $city = $interview->userHrjob->hrjob->city->city_name  ?? 'No Location';

            $sheet->setCellValue('A' . $rowNumber, $interview->id);
            $sheet->setCellValue('B' . $rowNumber, $applicantName);
            $sheet->setCellValue('C' . $rowNumber, $interview->user->fullname ?? 'No Interviewer');
            $sheet->setCellValue('D' . $rowNumber, $jobName);
            $sheet->setCellValue('E' . $rowNumber, $city);
            $sheet->setCellValue('F' . $rowNumber, $interview->userHrjob->hrjob->outlet->outlet_name ?? 'No Outlet');
            $sheet->setCellValue('G' . $rowNumber, $date);
            $sheet->setCellValue('H' . $rowNumber, $time);
            $sheet->setCellValue('I' . $rowNumber, $interview->location ?? 'No Location');
            $sheet->setCellValue('J' . $rowNumber, $interview->link ?? 'No Link');
            $sheet->setCellValue('K' . $rowNumber, $phone);
            $sheet->setCellValue('M' . $rowNumber, $interview->arrival ?? 'No Confirm');

            // Tambahkan formula HYPERLINK di kolom J
            $whatsappMessage = "Selamat sore {$applicantName},

Terima kasih telah melamar di Expat. Roasters. Perkenalkan kami dari HR Expat. Roasters. Selanjutnya kami mengundang Anda untuk mengikuti proses Phone Screen Interview untuk posisi {$jobName} ($city) pada:

Tanggal: {$date}
Waktu: {$time}
Platform: Whatsapp Call, sekitar 10-15 menit

Dimohon untuk melakukan konfirmasi dengan mengirimkan format berikut:
Nama Lengkap_Hadir/Tidak Hadir

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
        $response->headers->set('Content-Disposition', 'attachment;filename="interviews.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportdateInterview(Request $request)
    {
        // Validasi input tanggal
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Filter data berdasarkan rentang tanggal
        $interviews = Interview::with(['userHrjob.hrjob', 'userHrjob.user', 'user'])
            ->whereBetween('interview_date', [$validated['start_date'], $validated['end_date']])
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
            $phone = $interview->userHrjob->user->phone ?? 'No Phone';
            $city = $interview->userHrjob->hrjob->city->city_name  ?? 'No Location';

            $sheet->setCellValue('A' . $rowNumber, $interview->id);
            $sheet->setCellValue('B' . $rowNumber, $applicantName);
            $sheet->setCellValue('C' . $rowNumber, $interview->user->fullname ?? 'No Interviewer');
            $sheet->setCellValue('D' . $rowNumber, $jobName);
            $sheet->setCellValue('E' . $rowNumber, $city);
            $sheet->setCellValue('F' . $rowNumber, $interview->userHrjob->hrjob->outlet->outlet_name ?? 'No Outlet');
            $sheet->setCellValue('G' . $rowNumber, $date);
            $sheet->setCellValue('H' . $rowNumber, $time);
            $sheet->setCellValue('I' . $rowNumber, $interview->location ?? 'No Location');
            $sheet->setCellValue('J' . $rowNumber, $interview->link ?? 'No Link');
            $sheet->setCellValue('K' . $rowNumber, $phone);
            $sheet->setCellValue('M' . $rowNumber, $interview->arrival ?? 'No Confirm');

            // Tambahkan formula HYPERLINK di kolom J
            $whatsappMessage = "Selamat sore {$applicantName},

Terima kasih telah melamar di Expat. Roasters. Perkenalkan kami dari HR Expat. Roasters. Selanjutnya kami mengundang Anda untuk mengikuti proses Phone Screen Interview untuk posisi {$jobName} ($city) pada:

Tanggal: {$date}
Waktu: {$time}
Platform: Whatsapp Call, sekitar 10-15 menit

Dimohon untuk melakukan konfirmasi dengan mengirimkan format berikut:
Nama Lengkap_Hadir/Tidak Hadir

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
        $response->headers->set('Content-Disposition', 'attachment;filename="interviews.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
