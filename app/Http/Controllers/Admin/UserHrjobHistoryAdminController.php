<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserHrjobStatusHistory;
use App\Models\UserHrjob;
use Illuminate\Support\Facades\Auth;

class UserHrjobHistoryAdminController extends Controller
{

    public function getUserHrjobHistory()
    {
        $histories = UserHrjobStatusHistory::with(['userHrjob'])
            ->when(Auth::user()->role === 'hiring_manager', function ($query) {
                // Exclude data where associated hrjob has a super_admin user
                $query->whereHas('userHrjob', function ($subQuery) {
                    $subQuery->whereHas('hrjob.user', function ($q) {
                        $q->where('role', '!=', 'super_admin');
                    });
                });
            })
            ->when(Auth::user()->role === 'recruiter', function ($query) {
                // Show only answers associated with hrjob that the recruiter owns
                $query->whereHas('userHrjob', function ($subQuery) {
                    $subQuery->whereHas('hrjob', function ($q) {
                        $q->where('id_user', Auth::id());
                    });
                });
            })
            ->get();

        return view('admin.userhrjobhistory.index', compact('histories'));
    }

    public function destroyUserHrjobHistory(Request $request, $id)
    {
        $history = UserHrjobStatusHistory::findOrFail($id);
        $loggedInUser = Auth::user();

        try {
            // Logika validasi seperti sebelumnya
            if ($loggedInUser->role === 'recruiter') {
                $selectedUser = $history->userHrjob->hrjob->user;
                if ($selectedUser && in_array($selectedUser->role, ['super_admin', 'hiring_manager'])) {
                    return response()->json(['message' => 'You cannot manage histories for Super Admin & Hiring Manager.'], 403);
                }
            }

            if ($loggedInUser->role === 'hiring_manager') {
                $selectedUser = $history->userHrjob->hrjob->user;
                if ($selectedUser && $selectedUser->role === 'super_admin') {
                    return response()->json(['message' => 'You cannot manage histories for Super Admin.'], 403);
                }
            }

            // Hapus history
            $history->delete();

            return response()->json(['message' => 'User Job History deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the history.'], 500);
        }
    }
}
