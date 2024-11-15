<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;

class AuthController extends Controller
{
    public function getLogin()
    {
        return view('auth.login');
    }

    public function postLogin()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return redirect()->route('getLogin')->with('error', 'Unauthorized');
        }

        // Check the authenticated user's role and redirect accordingly
    switch (auth()->user()->role) {
        case 'super_admin':
            cookie()->queue(cookie('token', $token, null));
            return redirect()->route('getUserr');
        // case 'hiring_manager':
        //     cookie()->queue(cookie('token', $token, null));
        //     return redirect()->route('hiring_manager.dashboard');
        // case 'applicant':
        //     cookie()->queue(cookie('token', $token, null));
        //     return redirect()->route('applicant.dashboard');
        // case 'recruiter':
        //     cookie()->queue(cookie('token', $token, null));
        //     return redirect()->route('recruiter.dashboard');
        default:
            auth()->logout();
            return redirect()->route('getLogin')->with('error', 'Access denied.');
        }
    }


    // public function getUser()
    // {
    //     $users = User::with('company')->get(); // Eager load the group data
    //     return view('admin.user.index', compact('users'));
    // }

    // public function addUser()
    // {
    //     $groups = Company::all();
    //     return view('admin.user.store', compact('groups'));
    // }

    // public function storeUser(Request $request)
    // {
    //     $validated = $request->validate([
    //         'id_group' => 'required',
    //         'username' =>'required|unique:users',
    //         'password' => 'required',
    //         'customer_name' =>'required',
    //         'pic_name' => 'required',
    //         'pic_phone' => 'required',
    //         'address' => 'required',
    //         'role' => "required|in:admin,retail,supermarket"
    //     ]);

    //     User::create([
    //         'id_group' => $request->id_group,
    //         'username' =>$request->username,
    //         'password' =>$request->password,
    //         'customer_name' =>$request->customer_name,
    //         'pic_name' =>$request->pic_name,
    //         'pic_phone' =>$request->pic_phone,
    //         'address' =>$request->address,
    //         'role' => $request->role,
    //     ]);

    //     return redirect()->route('getUser')->with('message', 'Data Added Successfully');
    // }

    // public function editUser($id)
    // {
    //     $user = User::findOrFail($id);

    //     return view('admin.user.update', compact('user'));
    // }

    // public function updateUser(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);

    //     $validated = $request->validate([
    //         'id_group' => 'required',
    //         'username' =>'required',
    //         'password' => 'required',
    //         'customer_name' =>'required',
    //         'pic_name' => 'required',
    //         'pic_phone' => 'required',
    //         'address' => 'required',
    //         'role' => "required|in:admin,retail,supermarket"
    //     ]);

    //     $user->id_group = $request->id_group;
    //     $user->username = $request->username;
    //     $user->password = $request->password;
    //     $user->customer_name = $request->customer_name;
    //     $user->pic_name = $request->pic_name;
    //     $user->pic_phone = $request->pic_phone;
    //     $user->address = $request->address;
    //     $user->role = $request->role;

    //     $user->save();

    //     return redirect()->route('getUser')->with('message', 'User Updated Successfully');
    // }

    // public function destroyUser($id)
    // {
    //     $user = User::findOrFail($id);

    //     $user->delete();

    //     return redirect()->route('getUser')->with('message', 'User deleted successfully');
    // }
}
