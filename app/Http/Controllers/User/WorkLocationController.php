<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\WorkLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WorkLocationController extends Controller
{
    public function addWorkLocation()
    {
        // Ambil data kota untuk dropdown
        $cities = City::all();
        return view('user.profile.worklocation.store', compact('cities'));
    }

    public function storeWorkLocation(Request $request)
    {
        $validated = $request->validate([
            'id_city' => 'required|exists:cities,id',
        ]);

        // Periksa apakah kombinasi id_user dan id_city sudah ada
        $exists = WorkLocation::where('id_user', Auth::id())
            ->where('id_city', $validated['id_city'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'id_city' => 'This city is already added to your work locations.',
            ]);
        }

        // Jika tidak ada, tambahkan data baru
        WorkLocation::create([
            'id_user' => Auth::id(),
            'id_city' => $validated['id_city'],
        ]);

        return redirect()->route('getProfile')->with('message', 'Work Location Added Successfully');
    }


    public function editWorkLocation($id)
    {
        $worklocation = WorkLocation::findOrFail($id);
        $cities = City::all();

        return view('user.profile.worklocation.update', compact('worklocation', 'cities'));
    }

    public function updateWorkLocation(Request $request, $id)
    {
        $worklocation = WorkLocation::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'id_city' => 'required|exists:cities,id',
        ]);

        // Perbarui hanya field yang diperlukan
        $worklocation->id_city = $validated['id_city'];
        $worklocation->save();

        return redirect()->route('getProfile')->with('message', 'Work Location Updated Successfully');
    }

    public function destroyWorkLocation($id)
    {
        $workLocation = WorkLocation::findOrFail($id);
        $workLocation->delete();

        return redirect()->back()->with('message', 'Work location deleted successfully.');
    }
}
