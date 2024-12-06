<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Emergency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EmergencyController extends Controller
{
    public function addEmergency()
    {
        return view('user.profile.emergency.store');
    }

    public function storeEmergency(Request $request)
    {
        $validated = $request->validate([
            'emergency_name' => 'required|string|max:255',
            'emergency_relation' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:15',
        ]);

        // Jika tidak ada, tambahkan data baru
        Emergency::create([
            'id_user' => Auth::id(),
            'emergency_name' => $request->emergency_name,
            'emergency_relation' => $request->emergency_relation,
            'emergency_phone' => $request->emergency_phone,
        ]);

        return redirect()->route('getProfile')->with('message', 'Emergency Added Successfully');
    }


    public function editEmergency($id)
    {
        $emergency = Emergency::findOrFail($id);

        return view('user.profile.emergency.update', compact('emergency'));
    }

    public function updateEmergency(Request $request, $id)
    {
        $emergency = Emergency::findOrFail($id);

        $validated = $request->validate([
            'emergency_name' => 'required|string|max:255',
            'emergency_relation' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:15',
        ]);

        $emergency->emergency_name = $validated['emergency_name'];
        $emergency->emergency_relation = $validated['emergency_relation'];
        $emergency->emergency_phone = $validated['emergency_phone'];
        $emergency->save();

        return redirect()->route('getProfile')->with('message', 'Emergency Updated Successfully');
    }

    public function destroyEmergency($id)
    {
        $emergency = Emergency::findOrFail($id);
        $emergency->delete();

        return redirect()->back()->with('message', 'Emergency deleted successfully.');
    }
}
