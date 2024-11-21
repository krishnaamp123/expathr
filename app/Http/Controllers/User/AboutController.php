<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\About;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AboutController extends Controller
{
    public function addAbout()
    {
        return view('user.profile.about.store');
    }

    public function storeAbout(Request $request)
    {
        $validated = $request->validate([
            'about' => 'required|string|max:1000',
        ]);

        // Jika tidak ada, tambahkan data baru
        About::create([
            'id_user' => Auth::id(),
            'about' => $request->about,
        ]);

        return redirect()->route('getProfile')->with('message', 'About Added Successfully');
    }


    public function editAbout($id)
    {
        $about = About::findOrFail($id);

        return view('user.profile.about.update', compact('about'));
    }

    public function updateAbout(Request $request, $id)
    {
        $about = About::findOrFail($id);

        $validated = $request->validate([
            'about' => 'required|string|max:1000',
        ]);

        $about->about = $validated['about'];
        $about->save();

        return redirect()->route('getProfile')->with('message', 'About Updated Successfully');
    }

    public function destroyAbout($id)
    {
        $about = About::findOrFail($id);
        $about->delete();

        return redirect()->back()->with('success', 'About deleted successfully.');
    }
}
