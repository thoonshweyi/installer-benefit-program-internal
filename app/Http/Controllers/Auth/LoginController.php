<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'login_value' => 'required',
            'password' => 'required|min:6',
        ]);
        $remember_me = $request->has('remember_me') ? true : false;
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $input = $request->all();
        $login_value =  $input['login_value'];
        $pattern_1 = '/^([0-9\s\-\+\(\)]*)$/';
        $case_1 = preg_match($pattern_1, $login_value);
        $pattern_2 = filter_var($login_value, FILTER_VALIDATE_EMAIL);
        $case_2 = $pattern_2 ? 1 : 0;
        $pattern_3 = '/^\d{3}(-\d{6})?$/';
        $case_3 = preg_match($pattern_3, $login_value);
        if ($case_3 === 1) {
            $user = User::where('employee_id', $login_value)->first();
        } else if ($case_2 === 1) {
            $user = User::where('email', $login_value)->first();
        } else if ($case_1 === 1) {
            $user = User::where('phone_no', $login_value)->first();
        } else {
            return redirect()->to('login')->withInput()->withErrors(['login_value' => 'Something was wrong. Please Try Again...!!']);
        }

        if (isset($user)) {

            $currentURL = URL::current();
            if (str_contains($currentURL, '192.168.2.221')) {
                if($user->roles->pluck('name')->first() == 'Cashier' || $user->roles->pluck('name')->first() == 'Branch Manager'){
                    return redirect()->to('login')->withInput()->withErrors(['login_value' => 'Permission not allow in this Link Change Branch...!!']);
                }
            }
            //
            // if($user->roles->pluck('name')->first()){
                // return redirect()->to('login')->withInput()->withErrors(['login_value' => 'Something was wrong...!!']);
            // }
            // Set Auth Details
            // dd($user->status);
            if($user->status == 1)
            {

                if (Hash::check($input['password'], $user->password)) {
                    Auth::login($user,$remember_me);
                    // Redirect home page
                    return redirect()->route('home');
                } else {
                    // if(session()->get('user_login'))
                    // {
                    //     $id = session()->get('id');
                    //     $pass = session()->get('pass');
                    //     return redirect("/user_login/$id/$pass")->withInput()->withErrors(['password' => 'Password is not correct']);
                    // }

                    return redirect()->route('login')->withInput()->withErrors(['password' => 'Password is not correct']);
                }
            }
            else{
                // if(session()->get('user_login'))
                // {
                //     $id = session()->get('id');
                //     $pass = session()->get('pass');
                //     return redirect("/user_login/$id/$pass")->withInput()->withErrors(['login_value' => 'Inactive User']);
                // }

                return redirect()->to('login')->withInput()->withErrors(['login_value' => 'Inactive User']);
            }
        } else {
            // if(session()->get('user_login'))
            // {
            //     $id = session()->get('id');
            //     $pass = session()->get('pass');
            //     return redirect("/user_login/$id/$pass")->withInput()->withErrors(['login_value' => 'Something was wrong...!!']);
            // }
            return redirect()->to('login')->withInput()->withErrors(['login_value' => 'Something was wrong...!!']);
        }
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function logout(Request $request){
        $user = Auth::user();
        $user_uuid = $user->uuid;
        $user->branch_id = null;
        $user->save();

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();



        return redirect('/');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
