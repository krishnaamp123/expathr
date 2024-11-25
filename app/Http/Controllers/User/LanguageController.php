<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LanguageController extends Controller
{
    public function addLanguage()
    {
        return view('user.profile.language.store');
    }

    public function storeLanguage(Request $request)
    {
        $validated = $request->validate([
            'language' => 'required|string|max:100',
            'skill' => 'required|in:basic,intermediate,profesional,advanced_profesional,native',
        ]);

        // Jika tidak ada, tambahkan data baru
        Language::create([
            'id_user' => Auth::id(),
            'language' => $request->language,
            'skill' => $request->skill,
        ]);

        return redirect()->route('getProfile')->with('message', 'Language Added Successfully');
    }


    public function editLanguage($id)
    {
        $language = Language::findOrFail($id);

        return view('user.profile.language.update', compact('language'));
    }

    public function updateLanguage(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $validated = $request->validate([
            'language' => 'required|string|max:100',
            'skill' => 'required|in:basic,intermediate,profesional,advanced_profesional,native',
        ]);

        $language->language = $validated['language'];
        $language->skill = $validated['skill'];
        $language->save();

        return redirect()->route('getProfile')->with('message', 'Language Updated Successfully');
    }

    public function destroyLanguage($id)
    {
        $language = Language::findOrFail($id);
        $language->delete();

        return redirect()->back()->with('success', 'Language deleted successfully.');
    }
}
