<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SkillController extends Controller
{
    public function addSkill()
    {
        return view('user.profile.skill.store');
    }

    public function storeSkill(Request $request)
    {
        $validated = $request->validate([
            'skill_name' => 'required|string|max:255',
        ]);

        // Jika tidak ada, tambahkan data baru
        Skill::create([
            'id_user' => Auth::id(),
            'skill_name' => $request->skill_name,
        ]);

        return redirect()->route('getProfile')->with('message', 'Skill Added Successfully');
    }


    public function editSkill($id)
    {
        $skill = Skill::findOrFail($id);

        return view('user.profile.skill.update', compact('skill'));
    }

    public function updateSkill(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'skill_name' => 'required|string|max:255',
        ]);

        $skill->skill_name = $validated['skill_name'];
        $skill->save();

        return redirect()->route('getProfile')->with('message', 'Skill Updated Successfully');
    }

    public function destroySkill($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return redirect()->back()->with('message', 'Skill deleted successfully.');
    }
}
