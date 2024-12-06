<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Field;
use App\Models\WorkField;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WorkFieldController extends Controller
{
    public function addWorkField()
    {
        // Ambil data kota untuk dropdown
        $fields = Field::all();
        return view('user.profile.workfield.store', compact('fields'));
    }

    public function storeWorkField(Request $request)
    {
        $validated = $request->validate([
            'id_field' => 'required|exists:fields,id',
        ]);

        // Periksa apakah kombinasi id_user dan id_field sudah ada
        $exists = WorkField::where('id_user', Auth::id())
            ->where('id_field', $validated['id_field'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'id_field' => 'This field is already added to your work fields.',
            ]);
        }

        // Jika tidak ada, tambahkan data baru
        WorkField::create([
            'id_user' => Auth::id(),
            'id_field' => $validated['id_field'],
        ]);

        return redirect()->route('getProfile')->with('message', 'Work Field Added Successfully');
    }


    public function editWorkField($id)
    {
        $workfield = WorkField::findOrFail($id);
        $fields = Field::all();

        return view('user.profile.workfield.update', compact('workfield', 'fields'));
    }

    public function updateWorkField(Request $request, $id)
    {
        $workfield = WorkField::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'id_field' => 'required|exists:fields,id',
        ]);

        // Perbarui hanya field yang diperlukan
        $workfield->id_field = $validated['id_field'];
        $workfield->save();

        return redirect()->route('getProfile')->with('message', 'Work Field Updated Successfully');
    }

    public function destroyWorkField($id)
    {
        $workfield = WorkField::findOrFail($id);
        $workfield->delete();

        return redirect()->back()->with('message', 'Work field deleted successfully.');
    }
}
