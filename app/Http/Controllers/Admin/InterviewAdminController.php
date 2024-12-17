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


class InterviewAdminController extends Controller
{
    public function getInterview()
    {
        if (Auth::user()->role === 'hiring_manager') {
            // Filter wawancara untuk hiring_manager
            $interviews = Interview::with('userHrjob', 'user')
                ->whereHas('user', function ($query) {
                    $query->where('role', '!=', 'super_admin');
                })
                ->get();
        } elseif (Auth::user()->role === 'recruiter') {
            // Filter wawancara untuk recruiter
            $interviews = Interview::with('userHrjob', 'user')
                ->whereHas('user', function ($query) {
                    $query->whereNotIn('role', ['super_admin', 'hiring_manager']);
                })
                ->get();
        } else {
            $interviews = Interview::with('userHrjob', 'user')->get();
        }

        return view('admin.interview.index', compact('interviews'));
    }

    public function addInterview()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getInterview')->with('error', 'You are not authorized to create interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();
        $redirectTo = 'pageInterview';

        return view('admin.interview.store', compact('userhrjobs', 'users', 'redirectTo'));
    }

    public function addUserHrjobInterview()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to create interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();
        $redirectTo = 'pageUserHrjobInterview';

        return view('admin.interview.store', compact('userhrjobs', 'users', 'redirectTo'));
    }


    public function storeInterview(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            if ($request->redirectTo === 'pageInterview') {
                return redirect()->route('getInterview')->with('error', 'You are not authorized to create interview');
            } elseif ($request->redirectTo === 'pageUserHrjobInterview') {
                return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to create interview');
            }
            // return redirect()->route('getInterview')->with('error', 'You are not authorized to create interview');
        }

        // Prevent hiring_manager from adding interviews for super_admin
        $user = User::findOrFail($request->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            if ($request->redirectTo === 'pageInterview') {
                return redirect()->route('getInterview')->with('error', 'You cannot manage interviews for Super Admin');
            } elseif ($request->redirectTo === 'pageUserHrjobInterview') {
                return redirect()->route('getUserHrjob')->with('error', 'You cannot manage interviews for Super Admin');
            }
            // return redirect()->route('getInterview')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $validated = $request->validate([
            'id_user_job' => 'required',
            'id_user' => 'required',
            'interview_date' => 'nullable',
            'time' => 'nullable',
            'location' => 'nullable',
            'link' => 'nullable',
        ]);

        Interview::create([
            'id_user_job' => $request->id_user_job,
            'id_user' => $request->id_user,
            'interview_date' => $request->interview_date,
            'time' => $request->time,
            'location' => $request->location,
            'link' => $request->link,
        ]);

        // Redirect ke halaman sesuai input hidden
        if ($request->redirectTo === 'pageInterview') {
            return redirect()->route('getInterview')->with('message', 'Interview Scheduled Successfully');
        } elseif ($request->redirectTo === 'pageUserHrjobInterview') {
            return redirect()->route('getUserHrjob')->with('message', 'Interview Scheduled Successfully');
        }

        // return redirect()->route('getInterview')->with('message', 'Interview Scheduled Successfully');
    }

    public function editInterview($id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getInterview')->with('error', 'You are not authorized to edit interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();
        $redirectTo = 'pageInterview';

        return view('admin.interview.update', compact('interview', 'userhrjobs', 'users', 'redirectTo'));
    }

    public function editUserHrjobInterview($id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to edit interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();
        $redirectTo = 'pageUserHrjobInterview';

        return view('admin.interview.update', compact('interview', 'userhrjobs', 'users', 'redirectTo'));
    }

    public function updateInterview(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getInterview')->with('error', 'You are not authorized to edit interview');
        }

        // Prevent hiring_manager from updating interviews for super_admin
        $user = User::findOrFail($request->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getInterview')->with('error', 'You cannot manage interviews for Super Admin');
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

        // Redirect ke halaman sesuai input hidden
        if ($request->redirectTo === 'pageInterview') {
            return redirect()->route('getInterview')->with('message', 'Interview Updated Successfully');
        } elseif ($request->redirectTo === 'pageUserHrjobInterview') {
            return redirect()->route('getUserHrjob')->with('message', 'Interview Updated Successfully');
        }
        // return redirect()->route('getInterview')->with('message', 'Interview Updated Successfully');
    }

    public function editRating($id)
    {
        $interview = Interview::findOrFail($id);
        $redirectTo = 'pageInterview';
        return view('admin.interview.rating', compact('interview', 'redirectTo'));
    }

    public function editUserHrjobRating($id)
    {
        $interview = Interview::findOrFail($id);
        $redirectTo = 'pageUserHrjobInterview';
        return view('admin.interview.rating', compact('interview', 'redirectTo'));
    }

    public function updateRating(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $interview->rating = $validated['rating'];
        $interview->comment = $validated['comment'];

        $interview->save();

        // Redirect ke halaman sesuai input hidden
        if ($request->redirectTo === 'pageInterview') {
            return redirect()->route('getInterview')->with('message', 'Rating Updated Successfully');
        } elseif ($request->redirectTo === 'pageUserHrjobInterview') {
            return redirect()->route('getUserHrjob')->with('message', 'Rating Updated Successfully');
        }

        // return redirect()->route('getInterview')->with('message', 'Rating Updated Successfully');
    }


    public function destroyInterview($id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getInterview')->with('error', 'You are not authorized to delete interview.');
        }

        // Prevent hiring_manager from deleting interviews for super_admin
        $user = User::findOrFail($interview->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getInterview')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $interview->delete();

        return redirect()->route('getInterview')->with('message', 'Interview deleted successfully');
    }

    public function destroyUserHrjobInterview($id)
    {
        $interview = Interview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to delete interview.');
        }

        // Prevent hiring_manager from deleting interviews for super_admin
        $user = User::findOrFail($interview->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getUserHrjob')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $interview->delete();

        return redirect()->route('getUserHrjob')->with('message', 'Interview deleted successfully');
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
        $sheet->setCellValue('H1', 'Time');
        $sheet->setCellValue('I1', 'Location Interview');
        $sheet->setCellValue('J1', 'Link');
        $sheet->setCellValue('K1', 'Arrival');

        // Membuat header bold
        $headerRange = 'A1:K1'; // Range dari header
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data dari database ke dalam file Excel
        $rowNumber = 2; // Baris pertama adalah header
        foreach ($interviews as $interview) {
            $sheet->setCellValue('A' . $rowNumber, $interview->id);
            $sheet->setCellValue('B' . $rowNumber, $interview->userHrjob->user->fullname ?? 'No Applicant');
            $sheet->setCellValue('C' . $rowNumber, $interview->user->fullname ?? 'No Interviewer');
            $sheet->setCellValue('D' . $rowNumber, $interview->userHrjob->hrjob->job_name ?? 'No Job');
            $sheet->setCellValue('E' . $rowNumber, $interview->userHrjob->hrjob->location ?? 'No Location');
            $sheet->setCellValue('F' . $rowNumber, $interview->userHrjob->hrjob->outlet->outlet_name ?? 'No Outlet');
            $sheet->setCellValue('G' . $rowNumber, $interview->interview_date ?? 'No Date');
            $sheet->setCellValue('H' . $rowNumber, $interview->time ?? 'No Time');
            $sheet->setCellValue('I' . $rowNumber, $interview->location ?? 'No Location');
            $sheet->setCellValue('J' . $rowNumber, $interview->link ?? 'No Link');
            $sheet->setCellValue('K' . $rowNumber, $interview->arrival ?? 'No Arrival');
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
        $sheet->setCellValue('H1', 'Time');
        $sheet->setCellValue('I1', 'Location Interview');
        $sheet->setCellValue('J1', 'Link');
        $sheet->setCellValue('K1', 'Arrival');

        // Membuat header bold
        $headerRange = 'A1:K1'; // Range dari header
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Isi data
        $rowNumber = 2;
        foreach ($interviews as $interview) {
            $sheet->setCellValue('A' . $rowNumber, $interview->id);
            $sheet->setCellValue('B' . $rowNumber, $interview->userHrjob->user->fullname ?? 'No Applicant');
            $sheet->setCellValue('C' . $rowNumber, $interview->user->fullname ?? 'No Interviewer');
            $sheet->setCellValue('D' . $rowNumber, $interview->userHrjob->hrjob->job_name ?? 'No Job');
            $sheet->setCellValue('E' . $rowNumber, $interview->userHrjob->hrjob->location ?? 'No Location');
            $sheet->setCellValue('F' . $rowNumber, $interview->userHrjob->hrjob->outlet->outlet_name ?? 'No Outlet');
            $sheet->setCellValue('G' . $rowNumber, $interview->interview_date ?? 'No Date');
            $sheet->setCellValue('H' . $rowNumber, $interview->time ?? 'No Time');
            $sheet->setCellValue('I' . $rowNumber, $interview->location ?? 'No Location');
            $sheet->setCellValue('J' . $rowNumber, $interview->link ?? 'No Link');
            $sheet->setCellValue('K' . $rowNumber, $interview->arrival ?? 'No Arrival');
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
