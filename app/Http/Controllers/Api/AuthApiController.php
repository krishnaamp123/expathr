<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthApiController extends Controller
{
    /**
     * Create a new AuthApiController instance.
     *
     * @return void
     */

    public function registerapi()
    {
        $validator = Validator::make(request()->all(),[
            'id_city' => 'required',
            'employee_id' => 'nullable',
            'email' =>'required|unique:users',
            'password' => 'required',
            'fullname' =>'required',
            'nickname' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'profile_pict' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable',
            'role' => "required|in:super_admin,hiring_manager,recruiter,applicant"
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'id_city' => request('id_city'),
            'employee_id' => request('employee_id'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'fullname' => request('fullname'),
            'nickname' => request('nickname'),
            'phone' => request('phone'),
            'address' => request('address'),
            'profile_pict' => request('profile_pict'),
            'link' => request('link'),
            'role' => request('role'),
        ]);

        if ($user){
            return response()->json(['message' => 'Successfully Registered']);
        }else{
            return response()->json(['message' => 'Something went wrong']);
        }
    }

    public function indexapi()
    {
        $user = User::all();
        // return response()->json(['data' => $user]);
        return UserResource::collection($user);
    }

    public function indexapplicantapi()
    {
        $users = User::where('role', 'applicant')->get();
        return UserResource::collection($users);
    }

    public function showapi($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function updateapi(Request $request, $id)
    {
        $validated = $request->validate([
            'id_city' => 'required',
            'employee_id' => 'nullable',
            'email' =>'required|unique:users',
            'password' => 'required',
            'fullname' =>'required',
            'nickname' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'profile_pict' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable',
        ]);

        $user = User::findorFail($id);
        $user->update($request->all());
        return response()->json(['data' => $user]);
    }

    /**
     * Change the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePasswordapi(Request $request)
    {
        // Validasi input untuk memastikan password baru disediakan
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ]);

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Memeriksa apakah password saat ini sesuai dengan yang disimpan
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        // Mengubah password dan menyimpannya
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password successfully changed']);
    }

    public function destroyapi($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['data' => $user]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginapi()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function meapi()
    {
        $user = auth()->user();
        if (empty($user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutapi()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully Logged Out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshapi()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = Auth::user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null,
            // 'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }
}
