<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearEmailCookies
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event)
    {
        // Only clear cookies if they haven't been cleared yet
        if (Cookie::has('email')) {
            Cookie::queue(Cookie::forget('email'));
        }
    }
}
