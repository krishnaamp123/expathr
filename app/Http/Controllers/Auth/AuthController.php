<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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
            return redirect()->route('login')->with('error', 'Unauthorized');
        }

        // Check the authenticated user's role and redirect accordingly
        $user = auth()->user();

        if ($user->role === 'applicant') {
            cookie()->queue(cookie('token', $token, null));
            return redirect()->route('getDashboardUser');
        // } elseif ($user->role === 'hiring_manager') {
        //     cookie()->queue(cookie('token', $token, null));
        //     return redirect()->route('hiring_manager.dashboard');
        // } elseif ($user->role === 'recruiter') {
        //     cookie()->queue(cookie('token', $token, null));
        //     return redirect()->route('recruiter.dashboard');
        } elseif ($user->role === 'super_admin') {
            cookie()->queue(cookie('token', $token, null));
            return redirect()->route('getDashboardAdmin');
        } else {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }

    public function getRegister()
    {
        $cities = City::all();
        return view('auth.register', compact('cities'));
    }

    public function postRegister(Request $request)
    {
        $this->validate($request, [
            'id_city' => 'required|exists:cities,id',
            'employee_id' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'fullname' => 'required|string|max:255',
            'nickname' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'birth_date' => 'required',
            'gender' => 'required|in:male,female',
            'profile_pict' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'role' => 'nullable|in:super_admin,hiring_manager,recruiter,applicant'
        ]);

        $role = $request->input('role', 'applicant');

            $user = User::create([
                'id_city' => $request->id_city,
                'employee_id' => $request->employee_id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'fullname' => $request->fullname,
                'nickname' => $request->nickname,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'profile_pict' => $request->profile_pict,
                'link' => $request->link,
                'role' => $role,
            ]);

            event(new Registered($user));

            auth()->login($user);

            return redirect()->route('verification.notice')->with('success', 'Registration successful. Please check your email to verify your account.');
    }

    public function postLogout(Request $request)
    {
        // Hapus JWT (gunakan library atau mekanisme yang Anda pakai)
        auth()->logout();

        // Hapus cookie
        return response()->redirectToRoute('login')
                        ->withCookie(cookie()->forget('token'));
    }

    public function getUser()
    {
        $users = User::with('city')->get(); // Eager load the group data
        return view('admin.user.index', compact('users'));
    }

    public function addUser()
    {
        $groups = Company::all();
        return view('admin.user.store', compact('groups'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'id_group' => 'required',
            'username' =>'required|unique:users',
            'password' => 'required',
            'customer_name' =>'required',
            'pic_name' => 'required',
            'pic_phone' => 'required',
            'address' => 'required',
            'role' => "required|in:admin,retail,supermarket"
        ]);

        User::create([
            'id_group' => $request->id_group,
            'username' =>$request->username,
            'password' =>$request->password,
            'customer_name' =>$request->customer_name,
            'pic_name' =>$request->pic_name,
            'pic_phone' =>$request->pic_phone,
            'address' =>$request->address,
            'role' => $request->role,
        ]);

        return redirect()->route('getUser')->with('message', 'Data Added Successfully');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.update', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'id_group' => 'required',
            'username' =>'required',
            'password' => 'required',
            'customer_name' =>'required',
            'pic_name' => 'required',
            'pic_phone' => 'required',
            'address' => 'required',
            'role' => "required|in:admin,retail,supermarket"
        ]);

        $user->id_group = $request->id_group;
        $user->username = $request->username;
        $user->password = $request->password;
        $user->customer_name = $request->customer_name;
        $user->pic_name = $request->pic_name;
        $user->pic_phone = $request->pic_phone;
        $user->address = $request->address;
        $user->role = $request->role;

        $user->save();

        return redirect()->route('getUser')->with('message', 'User Updated Successfully');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('getUser')->with('message', 'User deleted successfully');
    }

    // Show the email verification notice
    public function showVerificationNotice()
    {
        return view('auth.verify-email');
    }

    // Handle email verification
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('login')->with('success', 'Email verification successful. Please log in.');
    }

    // Resend the email verification link
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()) {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('message', 'Verification link sent!');
        }

        return back()->with('error', 'User not authenticated.');
    }

    // Show the form to request a password reset link
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Send a password reset link to the given user
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    // Handle the password reset
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
