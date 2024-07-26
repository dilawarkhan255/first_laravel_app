<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function email()
    {
        return 'email';
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            $this->email() => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only($this->email(), 'password');
        $remember = $request->filled('remember');

        if (auth()->attempt($credentials, $remember)) {
            return redirect()->intended($this->redirectTo);
        }

        return back()
            ->withErrors(['email' => 'Invalid credentials.'])
            ->withInput($request->only('email', 'remember'));
    }


    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
