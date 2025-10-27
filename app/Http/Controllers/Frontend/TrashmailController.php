<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Carbon\Carbon;
use App\Models\Domain;
use App\Models\TrashMail;
use Illuminate\Http\Request;
use App\Services\TrashMailService;
use Webklex\PHPIMAP\ClientManager;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Controller;
use eXorus\PhpMimeMailParser\Parser;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;


class TrashmailController extends Controller
{




    protected $trashMailService;

    public function __construct(TrashMailService $trashMailService)
    {
        $this->trashMailService = $trashMailService;
    }






    public function get_messages(Request $request)
    {
        $recaptcha = false;
        if (isPluginEnabled('recaptcha_invisible')) {
            $secretKey = env("ROCAPTCHA_SECRET_INVISIBLE");

            if (isset($request->captcha) && !empty($request->captcha)) {
                $rq = $request->captcha;
                $responseData = Cache::remember($rq, 30 * 60, function () use ($rq, $secretKey) {
                    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $rq);
                    return json_decode($verifyResponse);
                });

                if ($responseData->success) {
                    $recaptcha = true;
                } else {
                    return response()->json(['message' => translate('Recaptcha verification failed, please try again later', 'alerts')], 404);
                }
            } else {
                return response()->json(['message' => translate('Captcha response is missing, please try again', 'alerts')], 404);
            }
        } else {
            $recaptcha = true;
        }


        if ($recaptcha) {
            if (Cookie::has('email')) {
                $email = Cookie::get('email');
                $histories = $this->trashMailService->emailCollections($email);
            } else {
                $email = $this->trashMailService->createEmail();
                $histories = $this->trashMailService->emailCollections($email);
            }

            if ($email == false) {
                return response()->json(['message' => translate('Something went wrong please try again', 'alerts')], 404);
            }




            $response = $this->trashMailService->allMessages($email);

            if ($response['status'] == false) {
                return response()->json(['message' => translate($response['mailbox'], 'alerts')], 404);
            }

            return array_merge($response, $histories);
        } else {
            return response()->json(['message' => translate('Something went wrong please try again', 'alerts')], 404);
        }
    }


    //! delete emails from histories
    public function delete_emails(Request $request)
    {
        if ($request->has('emails')) {

            if (Cookie::has('email')) {
                $emailToRemove =  Cookie::get('email');
                $request->emails = array_filter($request->emails, function ($email) use ($emailToRemove) {
                    return $email !== $emailToRemove;
                });
            }

            if (is_array($request->emails) && count($request->emails) > 0) {
                if (Auth::check()) {
                    $emails = TrashMail::where("user_id", Auth::user()->id)->whereIn('email', $request->emails)->delete();
                } else {
                    $emails = TrashMail::where("fingerprint", $this->trashMailService->generateFingerprint())->whereNull('user_id')->whereIn('email', $request->emails)->delete();
                }
                return response()->json(['success' => true]);
            }
        }
        return "return";
    }


    public function delete()
    {

        $now = Carbon::now();

        //dd($now);

        if (Cookie::has('email')) {

            $email =  Cookie::get('email');
            $trash = TrashMail::where('email', $email)->first();
            if ($trash) {
                $trash->update([
                    'expire_at' => $now,
                ]);
            }

            Cookie::queue(Cookie::forget('email'));

            $email = $this->trashMailService->createEmail();
            $histories = $this->trashMailService->emailCollections($email);

            if ($email == false) {
                return response()->json(['message' => translate('Something went wrong please try again', 'alerts')], 404);
            }

            $response = [
                'mailbox' => $email,
                'email_token' => encrypt_email($email),
                'messages' => []
            ];

            return array_merge($response, $histories);
        }

        return response()->json(['message' => translate('Something went wrong please try again', 'alerts')], 404);
    }



    public function change(Request $request)
    {
        //dd(getSetting('forbidden_ids'));
        $request->validate([
            'name' => 'required|max:30|min:2|alpha_num|notIn:' . implode(',', explode(',', getSetting('forbidden_ids'))),
            'domain' => 'required',
        ]);



        if (!in_array($request->domain, $this->trashMailService->getDomains())) {
            return response()->json([
                'errors' => [
                    'domain' => [
                        'problem in this domain'
                    ]
                ]
            ], 422);
        }

        $new_email = $request->name . "@" . $request->domain;



        /*
        if (Auth::check()) {
            $check = TrashMail::where("user_id", Auth::user()->id)->where('email', '=', $new_email)->count();
        } else {
            $check = TrashMail::where("fingerprint", $this->trashMailService->generateFingerprint())->whereNull('user_id')->where('email', '=', $new_email)->count();
        }
            */


        /*
        if ($check > 0) {

            return response()->json([
                'errors' => [
                    'email' => [
                        'The address you have chosen is already in use. Please choose a different one.'
                    ]
                ]
            ], 422);
        }

        */



        $now = Carbon::now();

        if (Cookie::has('email')) {
            $email =  Cookie::get('email');
            $trash = TrashMail::where('email', $email)->first();
            if ($trash) {
                $trash->update([
                    'expire_at' => $now,
                ]);
            }

            Cookie::queue(Cookie::forget('email'));

            $email = $this->trashMailService->createEmail($new_email, $request->domain);
            if ($email == false) {
                return response()->json(['message' => translate('Something went wrong please try again', 'alerts')], 404);
            }

            $histories = $this->trashMailService->emailCollections($email);

            $response = [
                'mailbox' => $email,
                'email_token' => encrypt_email($email),
                'messages' => []
            ];

            return array_merge($response, $histories);
        }

        return "return";
    }


    // change email from histories
    public function change_email(Request $request)
    {
        //dd(getSetting('forbidden_ids'));
        $request->validate([
            'name' => 'required|max:30|min:1|alpha_num|notIn:' . implode(',', explode(',', getSetting('forbidden_ids'))),
            'domain' => 'required',
        ]);

        if (!in_array($request->domain, $this->trashMailService->getDomains())) {
            return response()->json([
                'errors' => [
                    'domain' => [
                        'problem in this domain '
                    ]
                ]
            ], 422);
        }

        $new_email = $request->name . "@" . $request->domain;

        if (Auth::check()) {
            $old_email = TrashMail::where("user_id", Auth::user()->id)->where('email', '=', $new_email)->first();
        } else {
            $old_email = TrashMail::where("fingerprint", $this->trashMailService->generateFingerprint())->whereNull('user_id')->where('email', '=', $new_email)->first();
        }




        if ($old_email === null) {

            return response()->json([
                'errors' => [
                    'email' => [
                        'Error'
                    ]
                ]
            ], 422);
        }

        $now = Carbon::now();

        if (Cookie::has('email')) {
            $email =  Cookie::get('email');
            $trash = TrashMail::where('email', $email)->first();
            if ($trash) {
                $trash->update([
                    'expire_at' => $now,
                ]);
            }
            Cookie::queue(Cookie::forget('email'));

            //$old_email->forceDelete();
            $old_email->delete();

            $email = $this->trashMailService->createEmail($new_email, $request->domain);
            if ($email == false) {
                return response()->json(['message' => translate('Something went wrong please try again', 'alerts')], 404);
            }

            $histories = $this->trashMailService->emailCollections($email);

            $response = [
                'mailbox' => $email,
                'email_token' => encrypt_email($email),
                'messages' => []
            ];

            return array_merge($response, $histories);
        }

        return "return";
    }


    // change email from user panel

    public function choose(Request $request)
    {


        //dd(getSetting('forbidden_ids'));
        $request->validate([
            'name' => 'required|max:30|min:1|alpha_num|notIn:' . implode(',', explode(',', getSetting('forbidden_ids'))),
            'domain' => 'required',
        ]);

        if (!in_array($request->domain, $this->trashMailService->getDomains())) {
            return back()->with('error', "You are not allowed to use this domain");
        }

        $new_email = $request->name . "@" . $request->domain;

        if (Auth::check()) {
            // If the user is authenticated, check for the old email in TrashMail
            $old_email = TrashMail::where("user_id", Auth::user()->id)
                ->where('email', $new_email)
                ->first();
        } else {
            return redirect()->route('login')->with('error', "You must be logged in to use this feature");
        }

        if ($old_email === null) {
            return back()->with('error', "The provided email does not exist");
        }

        $now = Carbon::now();

        if (Cookie::has('email')) {
            $email =  Cookie::get('email');
            $trash = TrashMail::where('email', $email)->first();
            if ($trash) {
                $trash->update([
                    'expire_at' => $now,
                ]);
            }
            Cookie::queue(Cookie::forget('email'));

            //$old_email->forceDelete();
            $old_email->delete();

            $email = $this->trashMailService->createEmail($new_email, $request->domain);
            if ($email == false) {
                return back()->with('error', translate('Something went wrong please try again', 'alerts'));
            }

            $histories = $this->trashMailService->emailCollections($email);

            $response = [
                'mailbox' => $email,
                'email_token' => encrypt_email($email),
                'messages' => []
            ];

            return redirect()->route('index')->with('success', "The email has been changed successfully!");
        }

        return redirect()->route('index')->with('success', "The email has been changed successfully!");
    }


    public function getEmailFromToken($token)
    {
        try {
            // Decrypt the token to get the email
            $new_email = decrypt_email($token);

            if ($new_email != false) {



                if (Cookie::has('email')) {


                    $now = Carbon::now();

                    $email =  Cookie::get('email');
                    $trash = TrashMail::where('email', $email)->first();
                    if ($trash) {
                        $trash->update([
                            'expire_at' => $now,
                        ]);
                    }

                    Cookie::queue(Cookie::forget('email'));
                }

                $parts = explode('@', $new_email);
                // The domain is the second part
                $domain = $parts[1];

                $email = $this->trashMailService->createEmail($new_email, $domain);
                if ($email == false) {
                    abort(404);
                }

                $histories = $this->trashMailService->emailCollections($new_email);
                return redirect()->route('index')->with('success', "The email has been changed successfully!");
            }
        } catch (\Exception $e) {
            // Handle decryption errors (e.g., invalid token)
            abort(404);
        }
    }
}
