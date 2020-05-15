<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Socialite;
use App\User;


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
    //protected $redirectTo = '/Dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo(){

        // User role
        $role = Auth::user()->type;

        // Check user role
        switch ($role) {
            case 'user':
                    return '/';
                break;
            default:
                    return '/Dashboard';
                break;
        }
    }

     /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return void
     */
    public function handleProviderCallback($provider)
    {
        $userSocial = Socialite::driver($provider)->user();

        $user = $this->checkExitUser($provider, $userSocial->getEmail(), $userSocial->id);

        if(!$user){

            $user = User::create([
                'name'        => $userSocial->name?:$userSocial->nickname,
                'email'       => $userSocial->email,
                'provider'    => $provider,
                'provider_id' => $userSocial->id
            ]);
        }

        Auth::login($user);

        return redirect()->action('HomeController@index');
    }

    public function checkExitUser($provider, $email, $provider_id){

        if ($email){
            $user = User::where(['email' => $email])->where('provider', $provider)->first();
            if ($user) return $user;
        }

        $user = User::where('provider_id', $provider_id)->where('provider', $provider)->first();
        if ($user) return $user;

        return false;
    }
}
