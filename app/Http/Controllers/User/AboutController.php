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
        $about = $request->input('about');

        if (strlen($about) < 50) {
            return redirect()->route('getProfile')
                ->with('error', 'About must be at least 50 characters.')
                ->withInput();
        }

        if (strlen($about) > 1000) {
            return redirect()->route('getProfile')
                ->with('error', 'About must not exceed 1000 characters.')
                ->withInput();
        }

        About::create([
            'id_user' => Auth::id(),
            'about' => $about,
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
        $newAbout = $request->input('about');

        // Validasi menggunakan if-else
        if (strlen($newAbout) < 50) {
            return redirect()->route('getProfile')
                ->with('error', 'About must be at least 50 characters.')
                ->withInput();
        }

        if (strlen($newAbout) > 1000) {
            return redirect()->route('getProfile')
                ->with('error', 'About must not exceed 1000 characters.')
                ->withInput();
        }

        // Jika validasi lolos, perbarui data
        $about->about = $newAbout;
        $about->save();

        return redirect()->route('getProfile')->with('message', 'About Updated Successfully');
    }

    public function destroyAbout($id)
    {
        $about = About::findOrFail($id);
        $about->delete();

        return redirect()->back()->with('message', 'About deleted successfully.');
    }
}
