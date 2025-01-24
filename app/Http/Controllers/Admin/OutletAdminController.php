<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class OutletAdminController extends Controller
{
    public function getOutlet()
    {
        $outlets = Outlet::with('company')->get();
        return view('admin.outlet.index', compact('outlets'));
    }

    public function addOutlet()
    {
        $companies = Company::all();
        return view('admin.outlet.store', compact('companies'));
    }

    public function storeOutlet(Request $request)
    {
        $validated = $request->validate([
            'id_company' => 'required',
            'outlet_name' => 'required|max:255',
        ]);

        Outlet::create([
            'id_company' => $request->id_company,
            'outlet_name' => $request->outlet_name,
        ]);

        return redirect()->route('getOutlet')->with('message', 'Data Added Successfully');
    }

    public function editOutlet($id)
    {
        $outlet = Outlet::findOrFail($id);
        $companies = Company::all();

        return view('admin.outlet.update', compact('outlet', 'companies'));
    }

    public function updateOutlet(Request $request, $id)
    {
        $outlet = Outlet::findOrFail($id);

        $validated = $request->validate([
            'id_company' => 'required',
            'outlet_name' => 'required|max:255',
        ]);

        $outlet->id_company = $request->id_company;
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
