<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;

class UserAnswerAdminController extends Controller
{

    public function getUserAnswer()
    {
        $useranswers = UserAnswer::with(['user', 'userHrjob', 'question', 'answer'])
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

        return view('admin.useranswer.index', compact('useranswers'));
    }

    public function destroyUserAnswer($id)
    {
        $useranswers = UserAnswer::findOrFail($id);
        $loggedInUser = Auth::user();

        try {
            // Jika user yang login adalah recruiter
            if ($loggedInUser->role === 'recruiter') {
                $selectedUser = $useranswers->userHrjob->hrjob->user; // Ambil user yang terkait dengan hrjob
                if ($selectedUser && in_array($selectedUser->role, ['super_admin', 'hiring_manager'])) {
                    return response()->json(['message' => 'You cannot manage answers for Super Admin & Hiring Manager.'], 403);
                }
            }

            // Jika user yang login adalah hiring_manager
            if ($loggedInUser->role === 'hiring_manager') {
                $selectedUser = $useranswers->userHrjob->hrjob->user; // Ambil user yang terkait dengan hrjob
                if ($selectedUser && $selectedUser->role === 'super_admin') {
                    return response()->json(['message' => 'You cannot manage answers for Super Admin.'], 403);
                }
            }

            // Hapus answer
            $useranswers->delete();
            return response()->json(['message' => 'User Job History deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the history.'], 500);
        }
    }
}
