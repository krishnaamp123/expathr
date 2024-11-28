<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HrjobCategory;
use Illuminate\Support\Facades\Storage;

class HrjobCategoryAdminController extends Controller
{
    public function getHrjobCategory()
    {
        return view('admin.hrjobcategory.index', [
            'hrjobcategories' => HrjobCategory::latest()->get()
        ]);
    }

    public function addHrjobCategory()
    {
        return view('admin.hrjobcategory.store');
    }

    public function storeHrjobCategory(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|max:255',
        ]);

        HrjobCategory::create([
            'category_name' => $request->category_name,
        ]);

        return redirect()->route('getHrjobCategory')->with('message', 'Data Added Successfully');
    }

    public function editHrjobCategory($id)
    {
        $hrjobcategory = HrjobCategory::findOrFail($id);

        return view('admin.hrjobcategory.update', compact('hrjobcategory'));
    }

    public function updateHrjobCategory(Request $request, $id)
    {
        $hrjobcategory = HrjobCategory::findOrFail($id);

        $validated = $request->validate([
            'category_name' => 'required|max:255',
        ]);

        $hrjobcategory->category_name = $request->category_name;

        $hrjobcategory->save();

        return redirect()->route('getHrjobCategory')->with('message', 'Job Category Updated Successfully');
    }

    public function destroyHrjobCategory($id)
    {
        $hrjobcategory = HrjobCategory::findOrFail($id);

        $hrjobcategory->delete();

        return redirect()->route('getHrjobCategory')->with('message', 'Job Category deleted successfully');
    }

}
