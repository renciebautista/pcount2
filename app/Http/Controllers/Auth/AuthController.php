<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;
use Carbon\Carbon;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Auth\AuthManager;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(AuthManager $auth)
    {
         $this->auth = $auth;
        // $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getLogin(){
        return view('auth.form1');
    }

    public function postLogin(Request $request){
        $usernameinput =  $request->access;
        $password = $request->password;
        $field = filter_var($usernameinput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt(array($field => $usernameinput, 'password' => $password), false)) {

            if(Auth::user()->hasRole('FIELD')){
                Auth::logout();
                Session::flash('flash_message', 'Account not allowed to access the site.');
                Session::flash('flash_class', 'alert alert-danger');
                return \Redirect::back();
            }
            
            // if(Auth::user()->isActive()){
            //     Session::flash('message', '<h4>Welcome to E-TOP,</h4><p> '.ucwords(strtolower(Auth::user()->getFullname())).'</p>');
            //     Session::flash('class', 'alert alert-success');
            // }else{
            //     Auth::logout();
            //     Session::flash('message', 'User account is inactive, please contact the administrator');
            //     Session::flash('class', 'alert alert-danger');
            //     return Redirect::back();
            // }
            
            // return Redirect::action('DashboardController@index');
            return \Redirect::intended('/dashboard');
        }

        Auth::logout();
        Session::flash('flash_message', 'Invalid credentials, please try again.');
        Session::flash('flash_class', 'alert alert-danger');
        return \Redirect::back();
    }


    public function getLogout(){
        $user = Auth::user();
        $this->auth->logout();
        return redirect('/');

    }
}
