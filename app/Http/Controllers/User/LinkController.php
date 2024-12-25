<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LinkController extends Controller
{
    public function addLink()
    {
        return view('user.profile.link.store');
    }

    public function storeLink(Request $request)
    {
        $validated = $request->validate([
            'media' => 'required|in:facebook,instagram,x,linkedin,portofolio,tiktok',
            'media_url' => 'required|url',
        ]);

        // Jika tidak ada, tambahkan data baru
        Link::create([
            'id_user' => Auth::id(),
            'media' => $request->media,
            'media_url' => $request->media_url,
        ]);

        return redirect()->route('getProfile')->with('message', 'Link Added Successfully');
    }


    public function editLink($id)
    {
        $link = Link::findOrFail($id);

        return view('user.profile.link.update', compact('link'));
    }

    public function updateLink(Request $request, $id)
    {
        $link = Link::findOrFail($id);

        $validated = $request->validate([
            'media' => 'required|in:facebook,instagram,x,linkedin,portofolio,tiktok',
            'media_url' => 'required|url',
        ]);

        $link->media = $validated['media'];
        $link->media_url = $validated['media_url'];
        $link->save();

        return redirect()->route('getProfile')->with('message', 'Link Updated Successfully');
    }

    public function destroyLink($id)
    {
        $link = Link::findOrFail($id);
        $link->delete();

        return redirect()->back()->with('message', 'Link deleted successfully.');
    }
}
