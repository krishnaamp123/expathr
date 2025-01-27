<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SkillAdminController extends Controller
{
    public function getSkill()
    {
        return view('admin.skill.index', [
            'skills' => Skill::latest()->get()
        ]);
    }

    public function addSkill()
    {
        return view('admin.skill.store');
    }

    public function storeSkill(Request $request)
    {
        $validated = $request->validate([
            'skill_name' => 'required|max:255',
        ]);

        Skill::create([
            'skill_name' => $request->skill_name,
        ]);

        return redirect()->route('getSkill')->with('message', 'Data Added Successfully');
    }

    public function editSkill($id)
    {
        $skill = Skill::findOrFail($id);

        return view('admin.skill.update', compact('skill'));
    }

    public function updateSkill(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'skill_name' => 'required|max:255',
        ]);

        $skill->skill_name = $request->skill_name;

        $skill->save();

        return redirect()->route('getSkill')->with('message', 'Job Skill Updated Successfully');
    }

    public function destroySkill($id)
    {
        $skill = Skill::findOrFail($id);

        $skill->delete();

        return redirect()->route('getSkill')->with('message', 'Job Skill deleted successfully');
    }

}
