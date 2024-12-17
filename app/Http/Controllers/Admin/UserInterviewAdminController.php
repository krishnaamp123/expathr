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

class UserInterviewAdminController extends Controller
{
    public function getUserInterview()
    {
        if (Auth::user()->role === 'hiring_manager') {
            $userinterviews = UserInterview::with('userHrjob', 'user')
                ->whereHas('user', function ($query) {
                    $query->where('role', '!=', 'super_admin');
                })
                ->get();
        } elseif (Auth::user()->role === 'recruiter') {
            $userinterviews = UserInterview::with('userHrjob', 'user')
                ->whereHas('user', function ($query) {
                    $query->whereNotIn('role', ['super_admin', 'hiring_manager']);
                })
                ->get();
        } else {
            $userinterviews = UserInterview::with('userHrjob', 'user')->get();
        }

        return view('admin.userinterview.index', compact('userinterviews'));
    }

    public function addUserInterview()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserInterview')->with('error', 'You are not authorized to create interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();
        $redirectTo = 'pageUserInterview';

        return view('admin.userinterview.store', compact('userhrjobs', 'users', 'redirectTo'));
    }

    public function addUserHrjobUserInterview()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to create interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();
        $redirectTo = 'pageUserHrjobUserInterview';

        return view('admin.userinterview.store', compact('userhrjobs', 'users', 'redirectTo'));
    }


    public function storeUserInterview(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            if ($request->redirectTo === 'pageUserInterview') {
                return redirect()->route('getUserInterview')->with('error', 'You are not authorized to create user interview');
            } elseif ($request->redirectTo === 'pageUserHrjobUserInterview') {
                return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to create user interview');
            }
        }

        // Prevent hiring_manager from adding interviews for super_admin
        $user = User::findOrFail($request->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            if ($request->redirectTo === 'pageUserInterview') {
                return redirect()->route('getUserInterview')->with('error', 'You cannot manage user interviews for Super Admin');
            } elseif ($request->redirectTo === 'pageUserHrjobUserInterview') {
                return redirect()->route('getUserHrjob')->with('error', 'You cannot manage user interviews for Super Admin');
            }
        }

        $validated = $request->validate([
            'id_user_job' => 'required',
            'id_user' => 'required',
            'interview_date' => 'nullable',
            'time' => 'nullable',
            'location' => 'nullable',
            'link' => 'nullable',
        ]);

        UserInterview::create([
            'id_user_job' => $request->id_user_job,
            'id_user' => $request->id_user,
            'interview_date' => $request->interview_date,
            'time' => $request->time,
            'location' => $request->location,
            'link' => $request->link,
        ]);

        if ($request->redirectTo === 'pageUserInterview') {
            return redirect()->route('getUserInterview')->with('message', 'User Interview Scheduled Successfully');
        } elseif ($request->redirectTo === 'pageUserHrjobUserInterview') {
            return redirect()->route('getUserHrjob')->with('message', 'User Interview Scheduled Successfully');
        }
    }

    public function editUserInterview($id)
    {
        $userinterview = UserInterview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserInterview')->with('error', 'You are not authorized to edit interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();
        $redirectTo = 'pageUserInterview';

        return view('admin.userinterview.update', compact('userinterview', 'userhrjobs', 'users', 'redirectTo'));
    }

    public function editUserHrjobUserInterview($id)
    {
        $userinterview = UserInterview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to edit interview');
        }

        $userhrjobs = UserHrjob::all();
        $users = User::where('role', '!=', 'applicant')->get();
        $redirectTo = 'pageUserHrjobUserInterview';

        return view('admin.userinterview.update', compact('userinterview', 'userhrjobs', 'users', 'redirectTo'));
    }

    public function updateUserInterview(Request $request, $id)
    {
        $userinterview = UserInterview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            if ($request->redirectTo === 'pageUserInterview') {
                return redirect()->route('getUserInterview')->with('error', 'You are not authorized to edit user interview');
            } elseif ($request->redirectTo === 'pageUserHrjobUserInterview') {
                return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to edit user interview');
            }
        }

        // Prevent hiring_manager from updating interviews for super_admin
        $user = User::findOrFail($request->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            if ($request->redirectTo === 'pageUserInterview') {
                return redirect()->route('getUserInterview')->with('error', 'You cannot manage user interviews for Super Admin');
            } elseif ($request->redirectTo === 'pageUserHrjobUserInterview') {
                return redirect()->route('getUserHrjob')->with('error', 'You cannot manage user interviews for Super Admin');
            }
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

        $userinterview->id_user_job = $request->id_user_job;
        $userinterview->id_user = $request->id_user;
        $userinterview->interview_date = $request->interview_date;
        $userinterview->time = $request->time;
        $userinterview->location = $request->location;
        $userinterview->link = $request->link;
        $userinterview->arrival = $request->arrival;

        $userinterview->save();

        if ($request->redirectTo === 'pageUserInterview') {
            return redirect()->route('getUserInterview')->with('message', 'User Interview Updated Successfully');
        } elseif ($request->redirectTo === 'pageUserHrjobUserInterview') {
            return redirect()->route('getUserHrjob')->with('message', 'User Interview Updated Successfully');
        }
    }

    public function editUserRating($id)
    {
        $userinterview = UserInterview::findOrFail($id);
        $redirectTo = 'pageUserInterview';
        return view('admin.userinterview.rating', compact('userinterview', 'redirectTo'));
    }

    public function editUserHrjobUserRating($id)
    {
        $userinterview = UserInterview::findOrFail($id);
        $redirectTo = 'pageUserHrjobUserInterview';
        return view('admin.userinterview.rating', compact('userinterview', 'redirectTo'));
    }

    public function updateUserRating(Request $request, $id)
    {
        $userinterview = UserInterview::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $userinterview->rating = $validated['rating'];
        $userinterview->comment = $validated['comment'];

        $userinterview->save();

        if ($request->redirectTo === 'pageUserInterview') {
            return redirect()->route('getUserInterview')->with('message', 'Rating Updated Successfully');
        } elseif ($request->redirectTo === 'pageUserHrjobUserInterview') {
            return redirect()->route('getUserHrjob')->with('message', 'Rating Updated Successfully');
        }
    }


    public function destroyUserInterview($id)
    {
        $userinterview = UserInterview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserInterview')->with('error', 'You are not authorized to delete interview.');
        }

        // Prevent hiring_manager from deleting interviews for super_admin
        $user = User::findOrFail($userinterview->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getUserInterview')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $userinterview->delete();

        return redirect()->route('getUserInterview')->with('message', 'Interview deleted successfully');
    }

    public function destroyUserHrjobUserInterview($id)
    {
        $userinterview = UserInterview::findOrFail($id);

        if (!in_array(Auth::user()->role, ['super_admin', 'hiring_manager'])) {
            return redirect()->route('getUserHrjob')->with('error', 'You are not authorized to delete interview.');
        }

        // Prevent hiring_manager from deleting interviews for super_admin
        $user = User::findOrFail($userinterview->id_user);
        if (Auth::user()->role === 'hiring_manager' && $user->role === 'super_admin') {
            return redirect()->route('getUserHrjob')->with('error', 'You cannot manage interviews for Super Admin');
        }

        $userinterview->delete();

        return redirect()->route('getUserHrjob')->with('message', 'Interview deleted successfully');
    }

    public function exportUserInterview()
    {
        // Ambil data dari model Interview
        $interviews = UserInterview::with(['userHrjob.hrjob', 'userHrjob.user', 'user'])->get();

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

    public function exportdateUserInterview(Request $request)
    {
        // Validasi input tanggal
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Filter data berdasarkan rentang tanggal
        $interviews = UserInterview::with(['userHrjob.hrjob', 'userHrjob.user', 'user'])
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
