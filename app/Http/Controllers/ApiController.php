<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Domain;
use App\Models\TrashMail;
use Illuminate\Http\Request;
use App\Services\TrashMailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class ApiController extends Controller
{

    protected $trashMailService;

    public function __construct(TrashMailService $trashMailService)
    {
        $this->trashMailService = $trashMailService;
    }

    /**
     * Display a listing of the resource.
     */
    public function getDomains($apiKey, $type)
    {
        // Validate API key
        if ($apiKey != getSetting("api_key")) {
            return response()->json([
                'status' => false,
                'message' => "You are not authorized"
            ], 401);
        }

        // Validate the type parameter
        $typeMapping = [
            'free' => Domain::TYPE_FREE,
            'premium' => Domain::TYPE_PREMIUM,
            'all' => 'all'
        ];

        // Check if the type is valid
        if (!array_key_exists($type, $typeMapping)) {
            return response()->json([
                'status' => false,
                'message' => "Invalid domain type"
            ], 404);
        }

        // Fetch domains based on the type
        if ($typeMapping[$type] === 'all') {
            $domains = Domain::where('status', 1)->whereIn('type', [0, 1])->get(['domain', 'type']);
        } else {
            $domains = Domain::where('status', 1)->where('type', $typeMapping[$type])->get(['domain', 'type']);
        }

        // Check if domains exist
        if ($domains->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => "No domains found for the selected type"
            ], 404);
        }

        $typeLabels = Domain::getTypeLabels();

        // Prepare response
        return response()->json([
            'status' => true,
            'data' => [
                'domains' => $domains->map(function ($domain) use ($typeLabels) {
                    return [
                        'domain' => $domain->domain,
                        'type' => $typeLabels[$domain->type] // Map type number to name
                    ];
                })
            ]
        ]);
    }


    public function generateFingerprint()
    {
        // Get the user's IP
        $ip = get_user_ip();

        // Create a unique cache key for the user's IP
        $cacheKey = 'fingerprint_' . $ip;

        // Check if a fingerprint exists in the cache for this IP
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Generate a new fingerprint using the user's IP
        $fingerprint = md5($ip);

        // Store the fingerprint in the cache for one year (60 minutes * 24 hours * 365 days)
        Cache::put($cacheKey, $fingerprint, 60 * 24 * 365);

        return $fingerprint;
    }



    public function createEmail($email = null, $domain = null)
    {
        if ($email === null) {
            $random_email = $this->trashMailService->generateRandomEmail();
            if ($random_email == false) {
                return false;
            }
            $email = $random_email['email'];
            $domain = $random_email['domain'];
        }

        $trashmail = new TrashMail();
        $trashmail->user_id = Auth::user()->id ?? null;
        $trashmail->email = $email;
        $trashmail->domain = $domain;
        $trashmail->ip = get_user_ip();
        $trashmail->fingerprint = $this->generateFingerprint();
        $trashmail->expire_at = $this->trashMailService->expired();
        $trashmail->save();


        return $trashmail;
    }



    /**
     * Store a newly created resource in storage.
     */
    public function create($apiKey)
    {
        if ($apiKey != getSetting("api_key")) {
            return response()->json([
                'status' => false,
                'message' => "You are not authorized"
            ], 401);
        }

        $data = $this->createEmail();
        // Remove `user_id` and `updated_at` from the data
        unset($data['user_id'], $data['updated_at']);
        $data['email_token'] = encrypt_email($data['email']);
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }



    public function updateEmail($apiKey, $email, $username, $domain)
    {
        if ($apiKey != getSetting("api_key")) {
            return response()->json([
                'status' => false,
                'message' => "You are not authorized"
            ], 401);
        }

        if (in_array($username, explode(',', getSetting('forbidden_ids')))) {
            return response()->json([
                'status' => false,
                'message' => "This username is not allowed"
            ], 404);
        }


        $domains = Domain::where('status', 1)->pluck('domain')->toArray(); // Fetch only the 'domain' column as a flat array

        if (!in_array($domain, $domains)) {
            return response()->json([
                'status' => false,
                'message' => "This domain is not allowed"
            ], 404);
        }


        $new_email = $username . "@" . $domain;

        $now = Carbon::now();



        $trash = TrashMail::where('email', $email)->first();
        if ($trash) {
            $trash->update([
                'expire_at' => $now,
            ]);
        }

        $data = $this->createEmail($new_email, $domain);
        if ($data == false) {
            return response()->json([
                'status' => false,
                'message' => "Something went wrong please try again"
            ], 404);
        }

        // Remove `user_id` and `updated_at` from the data
        unset($data['user_id'], $data['updated_at']);

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }


    public function deleteEmail($apiKey, $email)
    {
        if ($apiKey != getSetting("api_key")) {
            return response()->json([
                'status' => false,
                'message' => "You are not authorized"
            ], 401);
        }

        $now = Carbon::now();

        $trash = TrashMail::where('email', $email)->first();
        if ($trash) {
            $trash->update([
                'expire_at' => $now,
            ]);
            $trash->delete();

            return response()->json([
                'status' => true,
                'message' => "Email has been successfully deleted."
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => "Email not found."
        ], 404);
    }



    public function getMessages($apiKey, $email)
    {

        abort(404);
    }



    public function getMessage($apiKey, $emailID)
    {

        abort(404);
    }


    public function DeleteMessage($apiKey, $emailID)
    {

        abort(404);
    }


    public function download($hash_id, $file)
    {
        abort(404);
    }
}
