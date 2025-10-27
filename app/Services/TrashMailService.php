<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Imap;
use App\Models\Domain;
use App\Models\Statistic;
use App\Models\TrashMail;
use Webklex\PHPIMAP\Message;
use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;


class TrashMailService
{
    protected $clientManager;

    public function __construct()
    {
        $this->clientManager = new ClientManager(base_path('config/imap.php'));
    }

    public function connection($mask = false, $imap)
    {
        $client = $this->clientManager->make([
            'protocol'      => 'imap',
            'host' => $imap->host,
            'port' => $imap->port,
            'encryption' => $imap->encryption,
            'validate_cert' => $imap->validate_certificates,
            'username' => $imap->username,
            'password' => $imap->password,
            'authentication' => null,
        ]);

        $client->connect();

        if ($mask) {
            $message_mask = \Webklex\PHPIMAP\Support\Masks\MessageMask::class;
            $client->setDefaultMessageMask($message_mask);
        }

        return $client;
    }

    protected function processString($inputString)
    {
        // Remove dots
        $step1 = str_replace('.', '', $inputString);

        // Find the position of the plus sign
        $plusPosition = strpos($step1, '+');

        // If plus sign is found, remove text after it
        if ($plusPosition !== false) {
            $result = substr($step1, 0, $plusPosition);
        } else {
            // If no plus sign, return the original string
            $result = $step1;
        }

        return $result;
    }



    public function extractEmail($email)
    {
        $parts = explode('@', $email);

        // Check if there are exactly two parts (prefix and domain)
        if (count($parts) === 2) {
            $prefix = $parts[0];
            $domain = $parts[1];
            $domainPrefix = explode('.', $domain);
            return [
                'prefix' => $prefix,
                'domain' => $domain,
                'domainPrefix' => $domainPrefix[0],
                'tag' => $domain == "gmail.com" ? $this->processString($prefix) : "main"
            ];
        } else {
            return false;
        }
    }


    public function allMessages($email, $time = 0)
    {
        $extractEmail = $this->extractEmail($email);

        $domainPrefix = $extractEmail['domainPrefix'];
        $prefix = $extractEmail['prefix'];
        $tag = $extractEmail['tag'];

        $imap = Imap::where('tag', $tag)->first();

        if ($imap === null) {
            return [
                'status' => false,
                'mailbox' => "Error: No IMAP server found. Please check your email server settings and try again.",
                'messages' => []
            ];
        }

        try {
            $client = $this->connection(true, $imap);
            $folder = $client->getFolderByName('INBOX');

            $messages = $time == 0
                ? $folder->query()->to($email)->get()
                : $folder->query()->to($email)->since(Carbon::now()->subDays($time)->format('d-M-Y'))->get();

            return $this->formatResponse($email, $messages, $domainPrefix, $prefix, $imap);
        } catch (\Exception $e) {
            Log::error("Failed to fetch messages: " . $e->getMessage());
            return [
                'status' => false,
                'mailbox' => "Unable to retrieve emails at this time. Please try Again",
                'messages' => []
            ];
        }
    }


    public function getMessage($hash_id)
    {
        try {

            //return Cache::remember($hash_id, 10000 * 60, function () use ($message, $hash_id, $domainPrefix, $prefix) {
            //return $this->extractMessageData($message, $hash_id, $domainPrefix, $prefix);


            $id = decode_hash(substr($hash_id, 0, 45), 'mail');

            $imap_id = substr($hash_id, 45);

            $imap = Imap::where('id', $imap_id)->first();

            if ($imap === null || $id === false) {
                return false;
            }

            $client = $this->connection(true, $imap);
            $folder = $client->getFolderByName('INBOX');
            $message = $folder->query()->getMessageByUid($id);
            $email = $message->getAttributes()["to"][0]->mail;

            $message->setFlag('Seen');

            $extractEmail = $this->extractEmail($email);

            $domainPrefix = $extractEmail['domainPrefix'];
            $prefix = $extractEmail['prefix'];

            return $this->extractMessageData($message, $hash_id, $domainPrefix, $prefix, $client, true);
            //});
        } catch (\Exception $e) {
            Log::error("Failed to fetch message: " . $e->getMessage());
            return $e->getMessage();
        }
    }




    public function saveMessage($hash_id)
    {
        try {

            $id = decode_hash(substr($hash_id, 0, 45), 'mail');

            $imap_id = substr($hash_id, 45);

            $imap = Imap::where('id', $imap_id)->first();

            if ($imap === null || $id === false) {
                return false;
            }

            $client = $this->connection(true, $imap);
            $folder = $client->getFolderByName('INBOX');
            $message = $folder->query()->getMessageByUid($id);

            makeDirectory(config('lobage.messages_eml_path'));
            $message->save(config('lobage.messages_eml_path') . $hash_id . ".eml");

            return true;
            //});
        } catch (\Exception $e) {
            Log::error("Failed to fetch message: " . $e->getMessage());
            return false;
        }
    }

    public function parseEmlFile($hash_id)
    {

        $file = config('lobage.messages_eml_path') . $hash_id . ".eml";

        if (!file_exists($file)) {
            return false;
        }

        try {

            $message = Message::fromFile($file);

            $data = [];

            $email = $message->getAttributes()["to"][0]->mail;

            $extractEmail = $this->extractEmail($email);
            $domainPrefix = $extractEmail['domainPrefix'];
            $prefix = $extractEmail['prefix'];

            return $this->extractMessageData($message, $hash_id, $domainPrefix, $prefix, null, true);
        } catch (\Exception $e) {
            Log::error("Failed to get message from file : " . $e->getMessage());
            return false;
        }
    }


    public function deleteMessage($hash_id)
    {
        try {
            $id = decode_hash(substr($hash_id, 0, 45), 'mail');
            $imap_id = substr($hash_id, 45);

            $imap = Imap::where('id', $imap_id)->first();

            if ($imap === null || $id === false) {
                return false;
            }

            $client = $this->connection(false, $imap);
            $folder = $client->getFolderByName('INBOX');
            $message = $folder->query()->getMessageByUid($id);
            $email = $message->getAttributes()['to'][0]->mail;
            $extractEmail = $this->extractEmail($email);
            $path = config('lobage.attachment_path') .  $extractEmail['domainPrefix'] . '/' . $extractEmail['prefix'] . '/' . $hash_id;

            removeFileOrFolder($path);

            $message->delete($expunge = true);
            $client->disconnect();

            return $email;
        } catch (\Exception $e) {
            Log::error("Failed to delete message: " . $e->getMessage());
            return false;
        }
    }






    protected function formatResponse($email, $messages, $domainPrefix, $prefix, $imap)
    {
        $response = [
            'status' => true,
            'mailbox' => $email,
            'email_token' => encrypt_email($email),
            'messages' => []
        ];

        foreach ($messages as $message) {
            $response['messages'][] = $this->formatMessage($message, $domainPrefix, $prefix, $imap);
        }

        return $response;
    }

    protected function formatMessage($message, $domainPrefix, $prefix, $imap)
    {
        $id = $message->getAttributes()["uid"];

        $hash_id = encode_hash($id, 'mail') . $imap->id;

        return Cache::remember($hash_id, 10000 * 60, function () use ($message, $hash_id, $domainPrefix, $prefix) {

            Statistic::firstOrCreate(
                ['key' => 'messages', 'value' => $hash_id], // Check for existence based on these fields
                ['key' => 'messages', 'value' => $hash_id]  // Create with these values if not found
            );


            return $this->extractMessageData($message, $hash_id, $domainPrefix, $prefix);
        });
    }




    protected function extractMessageData($message, $hash_id, $domainPrefix, $prefix, $client = null, $seenFlag = false)
    {
        $data = [];


        if (!$seenFlag) {
            try {
                $is_seen = $message->getFlags()['seen'] == 'Seen';
            } catch (\Throwable $th) {
                $is_seen = false;
            }
        } else {
            $is_seen = true;
        }

        $attributes = $message->getAttributes();
        $header = $message->getHeader();

        //MyLog('attributes', ['attributes' =>  $attributes]);
        //MyLog('header', ['header' =>  $header]);

        $date = Carbon::parse($message->getAttributes()['date'][0]);
        //$delivery_date = Carbon::parse($message->getAttributes()['delivery_date'][0]);
        $data['is_seen'] = $is_seen;
        $data['subject'] = strip_tags($this->decodeSubject($message->getAttributes()['subject'][0]));
        $data['from'] = $message->getAttributes()['from'][0]->personal;
        $data['from_email'] = $message->getAttributes()['from'][0]->mail;
        $data['to'] = $message->getAttributes()['to'][0]->mail;
        //$data['receivedAt'] =  $date;
        //$data['delivery_date'] =  $delivery_date;

        $data['receivedAt'] =  $date->setTimezone(config('app.timezone'))->toDateTimeString();
        //$data['Indian/Maldives'] =  $date->setTimezone('Indian/Maldives')->toDateTimeString();

        //$/data['UTC2'] =  $delivery_date->setTimezone('UTC')->toDateTimeString();
        //$data['Indian/Maldives2'] =  $delivery_date->setTimezone('Indian/Maldives')->toDateTimeString();

        $data['id'] = $hash_id;
        $data['html'] = $message->hasHTMLBody();

        $data['content'] = $message->hasHTMLBody()
            ? str_replace('<a', '<a target="blank"', $message->mask()->getHTMLBodyWithEmbeddedBase64Images())
            : str_replace(['\r\n', '\n'], '<br/>', str_replace('<a', '<a target="blank"', $message->getTextBody()));

        $data['attachments'] = $this->handleAttachments($message, $hash_id, $domainPrefix, $prefix);

        if ($client !== null) {
            $client->disconnect();
        }

        return $data;
    }



    protected function decodeSubject($subject)
    {

        $parts = preg_match_all("/(=\?[^\?]+\?[BQ]\?)([^\?]+)(\?=)[\r\n\t ]*/i", $subject, $m);

        $joined_parts = '';
        if (count($m[1]) > 1 && !empty($m[2])) {
            // Example: GyRCQGlNVTtZRTkhIT4uTlMbKEI=
            $joined_parts = $m[1][0] . implode('', $m[2]) . $m[3][0];

            $subject_decoded = iconv_mime_decode($joined_parts, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, "UTF-8");

            if ($subject_decoded && trim($subject_decoded) != trim(rtrim($joined_parts, '='))) {
                return $subject_decoded;
            }
        }

        // iconv_mime_decode() can't decode:
        // =?iso-2022-jp?B?IBskQiFaSEcyPDpuQC4wTU1qIVs3Mkp2JSIlLyU3JSItahsoQg==?=
        $subject_decoded = iconv_mime_decode($subject, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, "UTF-8");

        // Sometimes iconv_mime_decode() can't decode some parts of the subject:
        // =?iso-2022-jp?B?IBskQiFaSEcyPDpuQC4wTU1qIVs3Mkp2JSIlLyU3JSItahsoQg==?=
        // =?iso-2022-jp?B?GyRCQGlNVTtZRTkhIT4uTlMbKEI=?=
        if (preg_match_all("/=\?[^\?]+\?[BQ]\?/i", $subject_decoded)) {
            $subject_decoded = \imap_utf8($subject);
        }

        if (!$subject_decoded) {
            $subject_decoded = $subject;
        }

        return $subject_decoded;
    }





    protected function handleAttachments($message, $hash_id, $domainPrefix, $prefix)
    {
        $attachmentsData = [];

        if ($message->hasAttachments()) {
            $attachments = $message->getAttachments();
            $directory = $this->getDirectoryPath($hash_id, $domainPrefix, $prefix);
            $download = $this->getDownloadPath($hash_id, $domainPrefix, $prefix);

            foreach ($attachments as $attachment) {
                if ($attachment->getAttributes()['disposition'] == 'attachment') {
                    $processedAttachment = $this->processAttachment($attachment, $directory, $download);
                    if (!empty($processedAttachment)) {
                        $attachmentsData[] = $processedAttachment;
                    }
                }
            }
        }

        return $attachmentsData;
    }

    protected function getDirectoryPath($hash_id, $domainPrefix, $prefix)
    {
        $path =  config('lobage.attachment_path') . $domainPrefix . '/' . $prefix . '/' . $hash_id . '/';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    protected function getDownloadPath($hash_id, $domainPrefix, $prefix)
    {
        return url('/') . '/d/' . $hash_id . '/';
    }

    protected function processAttachment($attachment, $directory, $download)
    {
        $extension = $attachment->getExtension();
        $allowed = explode(',', getSetting('allowed_files'));
        $name = str_replace(' ', '_', basename(htmlspecialchars($attachment->getAttributes()['name'])));

        if (in_array($extension, $allowed)) {
            if (!file_exists($directory . $name)) {
                $attachment->save($directory, $name);
            }
            if ($name !== 'undefined') {
                $size = filesize($directory . $name);
                $url = $download . $name;
                return [
                    'name' => $name,
                    'extension' => $extension,
                    'size' => $size,
                    'url' => $url
                ];
            }
        }

        return [];
    }



    public function getDomains()
    {
        $domains = [];
        $free_domain = Domain::where('type', 0)->where('status', 1)->pluck('domain')->toArray();
        $domains = array_merge($domains, $free_domain);

        if (canUseFeature('premium_domains')) {
            $premium_domains = Domain::where('type', 1)->where('status', 1)->pluck('domain')->toArray();
            $domains = array_merge($domains, $premium_domains);
        }

        if (canUseFeature('domains', false) && auth()->check()) {
            $user = Auth::user();
            $custom_domains = Domain::where('user_id', $user->id)->where('status', 1)->pluck('domain')->toArray();
            $domains = array_merge($domains, $custom_domains);
        }

        return $domains;
    }

    public function generateRandomEmail($length = 7, $num = 2)
    {
        $email_length = getSetting('email_length');
        $email_length >= 10 ? $num = 4 : $num = 2;

        $length = $email_length - $num;

        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '013456789';
        $charactersLength = strlen($characters);
        $numbersLength = strlen($numbers);
        $randomEmail = '';
        for ($i = 0; $i < $length; $i++) {
            $randomEmail .= $characters[rand(0, $charactersLength - 1)];
        }

        for ($i = 0; $i < $num; $i++) {
            $randomEmail .= $numbers[rand(0, $numbersLength - 1)];
        }

        $randomEmail .= "@";

        if (Domain::where('status', 1)->count() > 0) {
            $domain = $this->getDomains();
            $selected_domain = $domain[array_rand($domain)];
            $randomEmail .= $selected_domain;

            return ['email' => $randomEmail, 'domain' => $selected_domain];
        } else {
            return false;
        }
    }

    public function expired()
    {
        return Carbon::now()->addMinutes(10);


        if (getSetting("email_lifetime_type") == 1) {
            $expired = Carbon::now()->addMinutes(getSetting("email_lifetime"));
        } elseif (getSetting("email_lifetime_type") == 60) {
            $expired = Carbon::now()->addHours(getSetting("email_lifetime"));
        } else {
            $expired = Carbon::now()->addDays(getSetting("email_lifetime"));
        }

        return $expired;
    }

    public function cacheExpired()
    {
        $time_unit = getSetting('time_unit');
        $email_lifetime = getSetting('email_lifetime');
        if ($time_unit == 'day') {
            $email_lifetime =  $email_lifetime * 24 * 60;
        } elseif ($time_unit == 'hour') {
            $email_lifetime =  $email_lifetime *  60;
        }
        return $email_lifetime * 60;
    }

    public function cookieExpired()
    {
        $time_unit = getSetting('time_unit');
        $email_lifetime = getSetting('email_lifetime');
        if ($time_unit == 'day') {
            $email_lifetime =  $email_lifetime * 24 * 60;
        } elseif ($time_unit == 'hour') {
            $email_lifetime =  $email_lifetime *  60;
        }
        return $email_lifetime * 1;
    }

    /*public function generateFingerprint()
    {
        if (Cookie::has('fingerprint')) {
            return Cookie::get('fingerprint');
        }

        $ip = get_user_ip();
        $fingerprint = md5($ip . time());
        Cookie::queue(Cookie::make('fingerprint', $fingerprint, 60 * 24 * 365));

        return $fingerprint;
    }
        */

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

    public function emailCollections($email)
    {
        $selected = [];
        $collection = [];

        if (Auth::check()) {
            $take = getFeatureValue('history');

            $emailsQuery = TrashMail::where("user_id", Auth::user()->id)
                ->select('id', 'email', 'created_at')
                ->orderBy('created_at', 'desc');

            // Check if $take is -1, meaning unlimited
            if ($take != -1) {
                $emailsQuery->take($take);
            }

            $emails = $emailsQuery->get();

            TrashMail::where("user_id", Auth::user()->id)->whereNotIn('id', $emails->pluck('id'))->delete();

            // Fetch all records for the provided email
            $matchingEmails = TrashMail::where("user_id", Auth::user()->id)
                ->where('email', $email)
                ->orderBy('created_at', 'desc')
                ->get();

            // If there are multiple records, keep only the most recent one
            if ($matchingEmails->count() > 1) {
                $mostRecentEmail = $matchingEmails->first(); // Get the most recent record
                TrashMail::where("user_id", Auth::user()->id)
                    ->where('email', $email)
                    ->where('id', '!=', $mostRecentEmail->id) // Exclude the most recent record
                    ->delete();
            }
        } else {

            $take = getFeatureValue('history');

            $emailsQuery = TrashMail::where("fingerprint", $this->generateFingerprint())
                ->whereNull('user_id')
                ->select('id', 'email', 'created_at')
                ->orderBy('created_at', 'desc');

            // Check if $take is -1, meaning unlimited
            if ($take != -1) {
                $emailsQuery->take($take);
            }

            $emails = $emailsQuery->get();

            TrashMail::where("fingerprint", $this->generateFingerprint())->whereNull('user_id')->whereNotIn('id', $emails->pluck('id'))->delete();

            $matchingEmails = TrashMail::where("fingerprint", $this->generateFingerprint())
                ->whereNull('user_id')
                ->where('email', $email)
                ->orderBy('created_at', 'desc')
                ->get();

            // If there are multiple records, keep only the most recent one
            if ($matchingEmails->count() > 1) {
                $mostRecentEmail = $matchingEmails->first(); // Get the most recent record
                TrashMail::where("fingerprint", $this->generateFingerprint())
                    ->whereNull('user_id')
                    ->where('email', $email)
                    ->where('id', '!=', $mostRecentEmail->id) // Exclude the most recent record
                    ->delete();
            }
        }

        foreach ($emails as $item) {
            $selected = [
                'email' => $item->email,
                'current' => $item->email == $email ? true : false,
                'time' => $item->created_at,
            ];
            $collection[] = $selected;
        }

        return ['histories' => $collection];
    }

    public function createEmail($email = null, $domain = null, $user_id = null)
    {
        if ($email === null) {
            $random_email = $this->generateRandomEmail();
            if ($random_email == false) {
                return false;
            }
            $email = $random_email['email'];
            $domain = $random_email['domain'];
        }

        $trashmail = new TrashMail();
        $trashmail->user_id = Auth::user()->id ?? $user_id;
        $trashmail->email = $email;
        $trashmail->domain = $domain;
        $trashmail->ip = get_user_ip();
        $trashmail->fingerprint = $this->generateFingerprint();
        $trashmail->expire_at = $this->expired();
        $trashmail->save();

        Cookie::queue(Cookie::make('email', $email, $this->cookieExpired()));

        return $email;
    }


    public function deleteEmails(array $emails)
    {
        if (Auth::check()) {
            return TrashMail::where("user_id", Auth::user()->id)->whereIn('email', $emails)->delete();
        } else {
            return TrashMail::where("fingerprint", $this->generateFingerprint())->whereNull('user_id')->whereIn('email', $emails)->delete();
        }
    }
}
