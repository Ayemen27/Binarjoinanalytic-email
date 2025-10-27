<?php

use App\Mail\GlobalMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;




if (!function_exists('getSetting')) {
    function getSetting($key, $default = null)
    {
        return Cache::remember('setting_' . $key, 60, function () use ($key, $default) {
            $setting = \App\Models\Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
}



if (!function_exists('setSetting')) {
    // Set a setting by its key with a given value.

    function setSetting($key, $value)
    {
        $setting = \App\Models\Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        return $setting;
    }
}


if (!function_exists('updateEnvFile')) {
    /**
     * Update a key's value in the .env file.
     */
    function updateEnvFile($key, $value)
    {
        // Path to the .env file
        $envPath = base_path('.env');

        // Ensure the .env file exists
        if (!file_exists($envPath)) {
            return false;
        }

        // Read the current .env file contents
        $currentEnv = file_get_contents($envPath);

        // Prepare the new value (enclosed in double quotes)
        $value = '"' . trim($value) . '"';

        // Create a regex pattern to search for the key in the .env file
        $pattern = "/^{$key}=(.*)$/m";

        // Check if the key already exists in the .env file
        if (preg_match($pattern, $currentEnv, $matches)) {
            // Key exists, update its value
            $newEnv = preg_replace($pattern, "{$key}={$value}", $currentEnv);
        } else {
            // Key doesn't exist, add it to the end of the .env file
            //! we don't need to create key in this project
            //* $newEnv = "{$currentEnv}\n{$key}={$value}";
            return false;
        }

        // Write the updated .env file
        if (file_put_contents($envPath, $newEnv) !== false) {
            return true;
        }

        return false;
    }
}



if (!function_exists('isPluginEnabled')) {
    function isPluginEnabled($name)
    {
        return App\Models\Plugin::where('unique_name', $name)->value('status') ?? false;
    }
}

function plugin($name)
{
    return App\Models\Plugin::where('status', 1)->where('unique_name', $name)->value('code');
}


function ad($block)
{
    if (!canUseFeature('ads')) {
        return false;
    }
    return App\Models\Ad::where('status', '1')->where('shortcode', $block)->value('code') ?? false;
}



if (!function_exists('showToastr')) {
    //Show a Toastr message.
    function showToastr($message, $type = 'success')
    {
        $fulltype = 'add' . ucfirst($type);
        // flash()->translate('es')->option('position', 'top-right')->option('timeout', 5000)->$type($message);
        flash()->option('position', 'top-right')->option('timeout', 5000)->$fulltype($message, translate($type));
    }
}


if (!function_exists('toDate')) {
    /**
     * Format a date string to the specified format.
     */

    function toDate($date, $format = 'Y-M-d h:m A')
    {
        if ($date === null) {
            return "NaN";
        }
        \Carbon\Carbon::setLocale(getCurrentLang());
        return \Carbon\Carbon::parse($date)->format($format);
    }
}



if (!function_exists('toDiffForHumans')) {
    /**
     * Get the human-readable difference for a date with timezone.
     */
    function toDiffForHumans($date, $timezone = null)
    {
        // Set the locale for Carbon
        \Carbon\Carbon::setLocale(getCurrentLang());

        // If no timezone is passed, use the default timezone from the .env
        $timezone = $timezone ?? config('app.timezone');

        // Parse the date and set the timezone
        return \Carbon\Carbon::parse($date, $timezone)->diffForHumans();
    }
}



if (!function_exists('translate')) {

    function translate($key, $coll = 'general',  $lang = null)
    {
        try {



            if ($lang == null) {
                $lang = App::getLocale();
            }


            $translation = Cache::remember($key . '_' . $coll . '_' . $lang, 100000 * 60, function () use ($key, $coll, $lang) {
                $translation = App\Models\Translate::where('lang', $lang)->where('key', $key)->where('collection', $coll)->first();
                if ($translation == null) {

                    foreach (getAllLanguages() as $lang) {
                        $translation = new App\Models\Translate;
                        $translation->lang = $lang['code'];
                        $translation->key = $key;
                        $translation->value = null;
                        $translation->collection = $coll;
                        $translation->save();
                    }
                }

                return $translation;
            });


            /*
        $translation = App\Models\Translate::where('lang', $lang)->where('key', $key)->where('collection', $coll)->first();
        if ($translation == null) {

            foreach (getAllLanguages() as $lang) {
                $translation = new App\Models\Translate;
                $translation->lang = $lang['code'];
                $translation->key = $key;
                $translation->value = null;
                $translation->collection = $coll;
                $translation->save();
            }
        }
            */

            if ($translation->value != null) {
                return $translation->value;
            } else {
                return $key;
            }
        } catch (\Exception $e) {
            return $key;
        }
    };
}



if (!function_exists('MyLog')) {
    //Log a message to the default log file.
    function MyLog($message, $context = [], $level = "INFO")
    {
        Illuminate\Support\Facades\Log::$level($message, $context);
    }
}


if (!function_exists('getSections')) {
    function getSections()
    {
        $sections =  App\Models\Section::where('lang', getCurrentLang())->where('status', 1)
            ->orderBy('position')->get();

        return $sections;
    }
}





function get_user_ip()
{
    // List of headers to check for the IP address (in order of priority)
    $headers = [
        'HTTP_CF_CONNECTING_IP', // Cloudflare
        'HTTP_X_FORWARDED_FOR',  // Proxies and load balancers
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR',           // Default server IP
    ];

    // Loop through headers and return the first valid IP address
    foreach ($headers as $header) {
        if (isset($_SERVER[$header])) {
            $ipList = explode(',', $_SERVER[$header]); // Handle multiple IPs in X_FORWARDED_FOR
            $ip = trim($ipList[0]); // Take the first IP in the list

            // Validate the IP address
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
                return $ip;
            }
        }
    }

    // Fallback to request()->ip() if available
    if (function_exists('request') && request()->ip() != null) {
        $ip = request()->ip();
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
            return $ip;
        }
    }

    // If no valid IP is found, return 'UNKNOWN'
    return 'UNKNOWN';
}



function fetch_ip_data($url)
{
    try {
        $json = @file_get_contents($url);
        return json_decode($json, true);
    } catch (\Exception $e) {
        return null;
    }
}

function geolocation_db($ip)
{
    $data = fetch_ip_data("https://geolocation-db.com/json/$ip&position=true");

    if (!empty($data) && isset($data['country_name'])) {
        return [
            'ip'            => $ip,
            'country'       => $data['country_name'] ?? 'Unknown',
            'country_code'  => $data['country_code'] ?? 'Unknown',
            'city'          => $data['city'] ?? 'Unknown',
            'timezone'      => 'Unknown', // This API does not provide timezone
            'currency'      => 'Unknown', // This API does not provide currency
        ];
    }

    return null;
}

function ip_api($ip)
{
    $data = fetch_ip_data("http://ip-api.com/json/$ip?fields=query,status,country,countryCode,regionName,city,lat,lon,timezone");

    if (!empty($data) && $data['status'] === 'success') {
        return [
            'ip'            => $ip,
            'country'       => $data['country'] ?? 'Unknown',
            'country_code'  => $data['countryCode'] ?? 'Unknown',
            'city'          => $data['city'] ?? 'Unknown',
            'timezone'      => $data['timezone'] ?? 'Unknown',
            'currency'      => 'Unknown', // This API does not provide currency
        ];
    }

    return null;
}

function geoplugin($ip)
{
    $data = fetch_ip_data("http://www.geoplugin.net/json.gp?ip=$ip");

    if (!empty($data) && isset($data['geoplugin_countryName'])) {
        return [
            'ip'            => $ip,
            'country'       => $data['geoplugin_countryName'] ?? 'Unknown',
            'country_code'  => $data['geoplugin_countryCode'] ?? 'Unknown',
            'city'          => $data['geoplugin_city'] ?? 'Unknown',
            'timezone'      => $data['geoplugin_timezone'] ?? 'Unknown',
            'currency'      => $data['geoplugin_currencyCode'] ?? 'Unknown',
        ];
    }

    return null;
}

function get_user_location()
{
    $ip = get_user_ip();

    if (Cache::has($ip)) {
        return Cache::get($ip);
    } else {
        $providers = ['geoplugin', 'ip_api', 'geolocation_db'];

        foreach ($providers as $provider) {
            $locationData = $provider($ip);
            if ($locationData) {
                Cache::forever($ip, $locationData);
                return $locationData;
            }
        }

        // Default fallback if all APIs fail
        $defaultLocation = [
            'ip'            => $ip,
            'country'       => 'Unknown',
            'country_code'  => 'Unknown',
            'city'          => 'Unknown',
            'timezone'      => 'Unknown',
            'latitude'      => 'Unknown',
            'longitude'     => 'Unknown',
            'currency'      => 'Unknown',
        ];

        Cache::forever($ip, $defaultLocation);
        return $defaultLocation;
    }
}



function extractDomain($url)
{

    $regex = '/^(?:https?:\/\/)?(?:www\.)?([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/';

    if (preg_match($regex, $url, $matches)) {
        return $matches[1];
    }

    return false;

    /*
    $parsedUrl = parse_url($url);
    $host = $parsedUrl['host'] ?? '';

    // If host is empty, extract from the path
    if (empty($host)) {
        $path = explode('/', $parsedUrl['path'], 2);
        $host = $path[0];
    }

    // Remove the 'www.' prefix if it exists
    $host = preg_replace('/^www\./', '', $host);

    return $host;
    */
}


function decode_hash($hash, $connection = "main")
{
    $id = Vinkla\Hashids\Facades\Hashids::connection($connection)->decode($hash);

    if (is_array($id) && count($id) > 0) {
        return $id[0];
    }

    return false;
}

function encode_hash($id, $connection = "main")
{
    return Vinkla\Hashids\Facades\Hashids::connection($connection)->encode($id);
}



function obfuscateDomain($domain)
{
    $parts = explode('.', $domain);
    $length = strlen($parts[0]);

    if ($length > 4) {
        $parts[0] = substr($parts[0], 0, 1) . str_repeat('*', $length - 2) . substr($parts[0], -1);
    } elseif ($length > 2) {
        $parts[0] = substr($parts[0], 0, 1) . str_repeat('*', $length - 1);
    } else {
        $parts[0] = str_repeat('*', $length);
    }

    return implode('.', $parts);
}


function get_file_icon($extension)
{
    if (file_exists('uploads/site/files_icon/' . $extension . '.png')) {
        return asset('uploads/site/files_icon/' . $extension . '.png');
    } else {
        return asset('uploads/site/files_icon/default.png');
    }
}



function is_demo()
{
    //return 1;
    if (env('DEMO_MODE') == 1) {
        return 1;
    }
    return 0;
}


function adminAuth()
{
    $admin = false;

    if (Auth::guard('admin')->check()) {
        $admin = Auth::guard('admin')->user();
    }

    return $admin;
}

function userAuth()
{
    $user = false;

    if (Auth::guard('web')->check()) {
        $user = Auth::guard('web')->user();
    }

    return $user;
}


function setMetaTags($title = null, $description = null, $keyword = null, $image = null, $type = 'article', $useSeparator = true)
{
    // Fetch SEO details, using a single query with a fallback to the default language
    $seo = App\Models\Seo::where('lang', getCurrentLang())
        ->orWhere('lang', getSetting('default_language'))
        ->first();

    // Use null coalescing to set values
    $title = $title ?? $seo->title;
    $description = $description ?? $seo->description;
    $keyword = $keyword ?? $seo->keyword;
    $image = $image ?? $seo->image;

    // Set the canonical URL
    $canonical = url()->current();

    // Get site name and separator
    $siteName = getSetting('site_name');
    $separator = config('seotools.meta.defaults.separator'); // Configurable separator

    // Set title with or without separator based on $useSeparator
    if ($useSeparator) {
        SEOMeta::setTitle($title . $separator . $siteName);
        OpenGraph::setTitle($title . $separator . $siteName);
    } else {
        SEOMeta::setTitle($title);
        OpenGraph::setTitle($title);
    }

    // Common meta tags
    SEOMeta::setDescription($description);
    SEOMeta::setKeywords($keyword);
    SEOMeta::setCanonical($canonical);


    // OpenGraph settings
    OpenGraph::setDescription($description);
    OpenGraph::setSiteName($siteName);
    OpenGraph::setUrl($canonical);
    OpenGraph::addProperty('type', $type);

    // Add image if it exists
    if (!empty($image)) {
        OpenGraph::addImage(asset($image));
    }
}


if (!function_exists('replacePlaceholders')) {

    function replacePlaceholders(string $text, array $replacements): string
    {
        foreach ($replacements as $key => $value) {
            // Replace {{key}} with the corresponding value
            $text = str_replace('{{' . $key . '}}', $value, $text);
        }

        return $text;
    }
}


function sendNotification($message, $icon = null, $to_admin = false, $user_id = null, $action = null)
{
    \App\Models\Notification::create([
        'user_id' => $user_id, // Admins do not have a user_id
        'to_admin' => $to_admin,
        'message' => $message,
        'icon' => $icon,
        'is_read' => false,
        'action' => $action,
    ]);
}


function mailTemplate($alias)
{
    return \App\Models\EmailTemplate::where('alias', $alias)->first();
}



function sendEmail($receiver_email, $mail_template_alias, $short_codes, $markdown = null)
{
    try {

        if (!empty(getSetting('mail_from_address')) && !empty(getSetting('mail_to_address'))) {
            $mailTemplate = mailTemplate($mail_template_alias);

            if ($mailTemplate && $mailTemplate->status) {

                $subject = replace_shortcodes($mailTemplate->subject, $short_codes);
                $body = replace_shortcodes($mailTemplate->body, $short_codes);
                Mail::to($receiver_email)->send(new GlobalMail($subject, $body, $markdown));

                //dump('mail send');
            }
        }
    } catch (\Exception $e) {
        $msg = "The SMTP server is not configured. Please review your SMTP server settings and ensure that a proper SMTP server is set up.";
        sendNotification($msg, 'error', true, null, route('admin.settings.smtp'));
    }
}


function replace_shortcodes($content, $short_codes)
{
    foreach ($short_codes as $key => $value) {
        $content = str_replace("{{" . $key . "}}", $value, $content);
    }

    return $content;
}



function SubMenus($parent)
{
    //$menus = Cache::rememberForever('sub_menus_' . getLang(), function () use ($parent) {
    return \App\Models\Menu::where('parent', $parent)->orderBy('position', 'ASC')->get();
    //});

    //return $menus;
}




function encrypt_email($email)
{
    $key = config('app.key'); // Use Laravel's app key
    $iv = '1234567890123456'; // Fixed IV (16 bytes)

    // Ensure the key is 32 bytes long for AES-256-CBC
    $key = substr(hash('sha256', $key), 0, 32);

    // Encrypt the email
    $encrypted = openssl_encrypt($email, 'AES-256-CBC', $key, 0, $iv);

    // Encode the encrypted string to make it URL-safe
    return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
}



function decrypt_email($encryptedEmail)
{
    $key = config('app.key'); // Use Laravel's app key
    $iv = '1234567890123456'; // Fixed IV (16 bytes)

    // Ensure the key is 32 bytes long for AES-256-CBC
    $key = substr(hash('sha256', $key), 0, 32);

    // Decode the URL-safe Base64 string
    $encryptedEmail = base64_decode(strtr($encryptedEmail, '-_', '+/'));

    // Decrypt the email
    return openssl_decrypt($encryptedEmail, 'AES-256-CBC', $key, 0, $iv);
}
