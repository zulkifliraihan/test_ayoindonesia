<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        // Start : Collecting All Request
        $data = $request->all();
        // End : Collecting All Request

        // Start : Validation
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $messages = [
            'email.required' => 'Email tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail-validator',
                'message' => $validator->errors()->first()
            ], 400);
        }
        // End : Validation

        // Start : Get Data User
        $user = User::where('email', $data['email'])->first();
        // End : Get Data User

        // Start : Validation False
        if (is_null($user)) {
            return response()->json([
                'status' => 'fail-email',
                'message' => 'Email belum terdaftar!'
            ], 400);
        }

        if (Hash::check($data['password'], $user->password) == false) {
            return response()->json([
                'status' => 'fail-password',
                'message' => 'Password salah!'
            ], 400);
        }
        // End : Validation False

        Auth::loginUsingId($user->id);

        return response()->json([
            'status' => 'ok',
            'response' => 'login-user',
            'message' => 'Berhasil login!',
            'route' => route('admin.teams.index')
        ], 200);
    }
}
