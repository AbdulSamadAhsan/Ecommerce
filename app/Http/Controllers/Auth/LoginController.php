<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
          $this->middleware('guest:web')->except('logout');
        $this->middleware('auth:web')->only('logout');
    }
    protected function guard()
    {
        return Auth::guard('web');
    }
protected function authenticated(Request $request, $user)
{
    if ($user->role->name === 'Customer') {
        Auth::logout();

        return redirect('/login')
            ->withErrors(['email' => 'Unauthorized access.']);
    }
}

    public function logout(Request $request)
    {
      Auth::guard('web')->logout();
     

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}