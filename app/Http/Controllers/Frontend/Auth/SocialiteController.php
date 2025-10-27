<?php

namespace App\Http\Controllers\Frontend\Auth;


use Str;
use Auth;
use Hash;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use Lobage\Planify\Models\Plan;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;




class SocialiteController extends Controller
{


    public function redirectToProvider($provider)
    {
        if (is_demo()) {
            return back()->with('error', "Demo version some features are disabled");
        }

        return Socialite::driver($provider)->redirect();
    }


    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            $isUser = User::where($provider . '_id', $user->id)->first();

            if ($isUser) {
                Auth::login($isUser);
            } else {

                $email = $user->getEmail();

                if (is_null($email)) {
                    $email = $user->getId() . '@' . $provider . '.com';
                }

                $existingUser = User::where('email', $email)->first();

                if ($existingUser) {
                    // Update the provider ID if the email exists
                    $existingUser->{$provider . '_id'} = $user->id;
                    $existingUser->save();
                    Auth::login($existingUser);
                } else {

                    $parts = explode(' ', $user->getName());
                    $firstName = array_shift($parts);
                    $lastName = count($parts) > 0 ? implode(' ', $parts) : null;
                    $createUser = new User();
                    $createUser->firstname = $firstName;
                    $createUser->lastname = $lastName;
                    $createUser->email = $email;
                    $createUser->{$provider . '_id'} = $user->id;
                    $createUser->avatar = AvatarFromUrl($user->getAvatar());
                    $createUser->country = get_user_location()['country'] ?? 'UNKNOWN';
                    $createUser->email_verified_at = Carbon::now();
                    $createUser->password = Hash::make(Str::random(16));
                    $createUser->save();
                    $plan = Plan::where('tag', 'free')->first();
                    $createUser->newSubscription(
                        'main',
                        $plan,
                    );

                    sendNotification(
                        "{$firstName} {$lastName} has registered",
                        'user',
                        true, // To admin
                        $createUser->id, // No specific user ID
                        route('admin.users.edit', $createUser->id) // Action link
                    );
                    Auth::login($createUser);
                }
            }

            return redirect('/');
        } catch (Exception $e) {
            $msg = "Error " . $provider . " Socialite ";
            MyLog($msg, ['exception' => $e->getMessage()]);
        }
    }
}
