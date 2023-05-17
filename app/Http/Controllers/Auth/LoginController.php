<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
        $this->middleware('guest')->except('logout','login_demo');
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'is_active' => 1)))
        {
            if (auth()->user()->type == 'manager') {
                return redirect()->route('manager.client.index',1);
            }else{
                Stat::create(['user_id' => auth()->user()->id, 'action' => 'Авторизация']);
                return redirect()->route('client.main');
            }
        }else{
            return redirect()->route('login')
                ->with('error','Email или пароль введён неверно.')
                ->withInput();
        }
    }

    public function login_demo(Request $request){
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $this->guard()->loginUsingId(config('global.intDemoObject'));
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Авторизация']);
        return redirect()->route('client.main');
    }

}
