<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Skill;
use App\Models\WorkSkill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WorkSkillController extends Controller
{
    public function addWorkSkill()
    {
        // Ambil data kota untuk dropdown
        $skills = Skill::all();
        return view('user.profile.workskill.store', compact('skills'));
    }

    public function storeWorkSkill(Request $request)
    {
        $validated = $request->validate([
            'id_skill' => 'required|exists:skills,id',
        ]);

        // Periksa apakah kombinasi id_user dan id_skill sudah ada
        $exists = WorkSkill::where('id_user', Auth::id())
            ->where('id_skill', $validated['id_skill'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'id_skill' => 'This skill is already added to your work skills.',
            ]);
        }

        // Jika tidak ada, tambahkan data baru
        WorkSkill::create([
            'id_user' => Auth::id(),
            'id_skill' => $validated['id_skill'],
        ]);

        return redirect()->route('getProfile')->with('message', 'Work Skill Added Successfully');
    }


    public function editWorkSkill($id)
    {
        $workskill = WorkSkill::findOrFail($id);
        $skills = Skill::all();

        return view('user.profile.workskill.update', compact('workskill', 'skills'));
    }

    public function updateWorkSkill(Request $request, $id)
    {
        $workskill = WorkSkill::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'id_skill' => 'required|exists:skills,id',
        ]);

        // Perbarui hanya skill yang diperlukan
        $workskill->id_skill = $validated['id_skill'];
        $workskill->save();

        return redirect()->route('getProfile')->with('message', 'Work Skill Updated Successfully');
    }

    public function destroyWorkSkill($id)
    {
        $workskill = WorkSkill::findOrFail($id);
        $workskill->delete();

        return redirect()->back()->with('message', 'Work Skill deleted successfully.');
    }
}
