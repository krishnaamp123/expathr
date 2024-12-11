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
        } elseif ($user->role === 'hiring_manager') {
            cookie()->queue(cookie('token', $token, null));
            return redirect()->route('getDashboardAdmin');
        } elseif ($user->role === 'recruiter') {
            cookie()->queue(cookie('token', $token, null));
            return redirect()->route('getDashboardAdmin');
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
            'password' => 'required|min:6',
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

            // event(new Registered($user));
            // $user->sendEmailVerificationNotification();

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

    // Show the email verification notice
    public function showVerificationNotice()
    {
        return view('auth.verify-email');
    }

    // Handle email verification
    public function verify(EmailVerificationRequest $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to verify your email.');
        }

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
            'password' => 'required|min:6|confirmed',
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
