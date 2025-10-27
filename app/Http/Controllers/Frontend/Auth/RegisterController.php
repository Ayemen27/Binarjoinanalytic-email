<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\User;
use App\Models\Domain;
use Illuminate\Http\Request;
use Lobage\Planify\Models\Plan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        if (getSetting('enable_verification')) {
            $this->redirectTo = '/verify';
        }
    }


    public function showRegistrationForm()
    {
        return view('frontend.user.auth.register');
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // List of restricted domains
        $restrictedDomains = Domain::pluck('domain')->toArray();

        $rules = [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) use ($restrictedDomains) {
                    $emailDomain = substr(strrchr($value, "@"), 1);
                    if (in_array($emailDomain, $restrictedDomains)) {
                        $fail("The email domain $emailDomain is not allowed.");
                    }
                }
            ],
            'password' => ['required', 'string', 'min:8'],
            'firstname' => ['required', 'string', 'min:2'],
            'lastname' => ['required', 'string', 'min:2'],
        ];

        if (getSetting('captcha_register')) {

            if (getSetting('captcha') == 'hcaptcha' && isPluginEnabled('hcaptcha')) {
                $rules['h-captcha-response'] = 'required|hcaptcha';
            } elseif (getSetting('captcha') == 'recaptcha' && isPluginEnabled('recaptcha')) {
                $rules['g-recaptcha-response'] = 'captcha';
            }
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            $country =  get_user_location()['country'];
        } catch (\Throwable $th) {
            $country = "UNKNOWN";
        }

        return User::create([
            'email' => $data['email'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'country' =>  $country,
            'avatar' => config('lobage.default_avatar'),
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered(Request $request, $user)
    {
        $plan = Plan::where('tag', 'free')->first();
        $user->newSubscription(
            'main',
            $plan,
        );

        sendNotification(
            "{$user->firstname} {$user->lastname} has registered",
            'user',
            true, // To admin
            $user->id, // No specific user ID
            route('admin.users.edit', $user->id) // Action link
        );


        if (getSetting('enable_verification') && is_null($user->email_verified_at)) {
            return redirect(route('verification.notice'));
        }

        return redirect($this->redirectPath());
    }
}
