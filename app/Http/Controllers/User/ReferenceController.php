<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ReferenceController extends Controller
{
    public function addReference()
    {
        return view('user.profile.reference.store');
    }

    public function storeReference(Request $request)
    {
        $validated = $request->validate([
            'reference_name' => 'required|string|max:255',
            'relation' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'is_call' => 'required|in:yes,no',
        ]);

        // Jika tidak ada, tambahkan data baru
        Reference::create([
            'id_user' => Auth::id(),
            'reference_name' => $request->reference_name,
            'relation' => $request->relation,
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'is_call' => $request->is_call,
        ]);

        return redirect()->route('getProfile')->with('message', 'Reference Added Successfully');
    }


    public function editReference($id)
    {
        $reference = Reference::findOrFail($id);

        return view('user.profile.reference.update', compact('reference'));
    }

    public function updateReference(Request $request, $id)
    {
        $reference = Reference::findOrFail($id);

        $validated = $request->validate([
            'reference_name' => 'required|string|max:255',
            'relation' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'is_call' => 'required|in:yes,no',
        ]);

        $reference->reference_name = $validated['reference_name'];
        $reference->relation = $validated['relation'];
        $reference->company_name = $validated['company_name'];
        $reference->phone = $validated['phone'];
        $reference->is_call = $validated['is_call'];
        $reference->save();

        return redirect()->route('getProfile')->with('message', 'Reference Updated Successfully');
    }

    public function destroyReference($id)
    {
        $reference = Reference::findOrFail($id);
        $reference->delete();

        return redirect()->back()->with('message', 'Reference deleted successfully.');
    }
}
