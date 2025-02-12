<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Source;
use Illuminate\Support\Facades\Auth;

class SourceController extends Controller
{
    public function addSource()
    {
        return view('user.profile.source.store');
    }

    public function storeSource(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'nullable|in:jobstreet,indeed,linkedin,glints,glassdoor,whatsapp,telegram,facebook,instagram,tiktok,google',
            'referal' => 'nullable|string|max:255',
            'other' => 'nullable|string|max:255',
        ]);

        // Validasi hanya salah satu yang boleh diisi
        if (!$this->isSingleFieldFilled($request)) {
            return redirect()->route('getProfile')->with(['error' => 'Only one field between Platform, Referal, or Other should be filled.']);
        }

        // Cek jika id_user sudah memiliki data
        $existingSource = Source::where('id_user', Auth::id())->first();
        if ($existingSource) {
            return redirect()->route('getProfile')->with(['error' => 'You can only have one source of information.']);
        }

        // Tambahkan data baru
        Source::create([
            'id_user' => Auth::id(),
            'platform' => $request->platform,
            'referal' => $request->referal,
            'other' => $request->other,
        ]);

        return redirect()->route('getProfile')->with('message', 'Information Source Added Successfully');
    }

    public function editSource($id)
    {
        $source = Source::findOrFail($id);

        return view('user.profile.source.update', compact('source'));
    }

    public function updateSource(Request $request, $id)
    {
        $source = Source::findOrFail($id);

        $validated = $request->validate([
            'platform' => 'nullable|in:jobstreet,indeed,linkedin,glints,glassdoor,whatsapp,telegram,facebook,instagram,tiktok,google',
            'referal' => 'nullable|string|max:255',
            'other' => 'nullable|string|max:255',
        ]);

        if (!$this->isSingleFieldFilled($request)) {
            return redirect()->route('getProfile')->with(['error' => 'Only one field between Platform, Referal, or Other should be filled.']);
        }

        if ($source->id_user !== Auth::id()) {
            return redirect()->route('getProfile')->with(['error' => 'Unauthorized action.']);
        }

        // Update data
        $source->update([
            'platform' => $request->platform ?: null,
            'referal' => $request->referal ?: null,
            'other' => $request->other ?: null,
        ]);

        return redirect()->route('getProfile')->with('message', 'Information Source Updated Successfully');
    }


    public function destroySource($id)
    {
        $source = Source::findOrFail($id);
        $source->delete();

        return redirect()->back()->with('message', 'Information Source deleted successfully.');
    }

    /**
     * Helper method to ensure only one field is filled.
     */
    private function isSingleFieldFilled($request)
    {
        $fields = [
            'platform' => $request->platform,
            'referal' => $request->referal,
            'other' => $request->other,
        ];

        // Hitung field yang tidak kosong atau null
        $filledFieldsCount = collect($fields)->filter(function ($value) {
            return !is_null($value) && $value !== ''; // Periksa nilai non-kosong
        })->count();

        return $filledFieldsCount === 1; // Validasi hanya lolos jika satu field diisi
    }
}
