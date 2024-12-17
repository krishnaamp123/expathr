<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Support\Facades\Storage;

class OutletAdminController extends Controller
{
    public function getOutlet()
    {
        return view('admin.outlet.index', [
            'outlets' => Outlet::latest()->get()
        ]);
    }

    public function addOutlet()
    {
        return view('admin.outlet.store');
    }

    public function storeOutlet(Request $request)
    {
        $validated = $request->validate([
            'outlet_name' => 'required|max:255',
        ]);

        Outlet::create([
            'outlet_name' => $request->outlet_name,
        ]);

        return redirect()->route('getOutlet')->with('message', 'Data Added Successfully');
    }

    public function editOutlet($id)
    {
        $outlet = Outlet::findOrFail($id);

        return view('admin.outlet.update', compact('outlet'));
    }

    public function updateOutlet(Request $request, $id)
    {
        $outlet = Outlet::findOrFail($id);

        $validated = $request->validate([
            'outlet_name' => 'required|max:255',
        ]);

        $outlet->outlet_name = $request->outlet_name;

        $outlet->save();

        return redirect()->route('getOutlet')->with('message', 'Outlet Updated Successfully');
    }

    public function destroyOutlet($id)
    {
        $outlet = Outlet::findOrFail($id);

        $outlet->delete();

        return redirect()->route('getOutlet')->with('message', 'Outlet deleted successfully');
    }

}
