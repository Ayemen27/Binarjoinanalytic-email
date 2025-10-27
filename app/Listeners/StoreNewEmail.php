<?php

namespace App\Listeners;

use App\Services\TrashMailService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreNewEmail
{
    /**
     * Create the event listener.
     */
    protected $trashMailService;

    public function __construct(TrashMailService $trashMailService)
    {
        $this->trashMailService = $trashMailService;
    }

    /**
     * Handle the event.
     */
    public function handle($event)
    {

        $user = $event->user; // Get the registered user
        $userId = $user->id; // Get the user ID

        //dd($userId);

        // Only clear cookies if they haven't been cleared yet
        if (Cookie::has('email')) {
            $email = Cookie::get('email');
            $domain = substr(strrchr($email, "@"), 1);
            $this->trashMailService->createEmail($email, $domain, $userId);
        }
    }
}
