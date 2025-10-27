<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;

class ContactController extends Controller
{

    public function index()
    {
        setMetaTags(translate('Contact Us', 'seo'));
        return view()->theme('contact');
    }


    public function store(ContactRequest $request)
    {

        if (is_demo()) {
            return back()->with('error', 'Demo version some features are disabled');
        }

        $receiver_email = getSetting('mail_to_address');

        $mail_template_alias = 'contact_us';
        $short_codes = [
            'fullname'   => $request->input('fullname'),              // The sender's name from the form
            'email'      => $request->input('email'),             // The sender's email from the form
            'message'    => $request->input('message'),
            'subject'    => $request->input('subject'),           // The message content from the form
            'ip'         => $request->ip(),                       // The sender's IP address
            'user_id'    => auth()->check() ? auth()->id() : '',  // If the user is logged in, use their user ID, otherwise empty
            'country'    => get_user_location()['country'] ?? 'UNKNOWN',  // The sender's country (or N/A if not provided)
            'user_agent' => $request->header('User-Agent'),       // The sender's browser and device info
            'website_name'    => config('app.name'),
            'website_url'     => config('app.url'),             // The website URL (from config)
        ];

        sendEmail($receiver_email, $mail_template_alias, $short_codes, true);

        return back()->with('message', translate('Thank you for reaching out to us! Your message has been received, and we will get back to you shortly', 'alerts'));
    }
}
