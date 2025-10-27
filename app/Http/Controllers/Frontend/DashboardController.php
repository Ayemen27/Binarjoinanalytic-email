<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Domain;
use App\Models\Message;
use App\Models\TrashMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Payments\PaypalController;

class DashboardController extends Controller
{

    public function index()
    {


        $domains = Domain::where('user_id', userAuth()->id)->count();
        $emails = TrashMail::withTrashed()->where('user_id', userAuth()->id)->count();
        $history = TrashMail::where('user_id', userAuth()->id)->orderBy('created_at', 'DESC')->get();
        $messages = Message::where('user_id', userAuth()->id)->count();


        $text = translate('Welcome back,{{user}} 👋', 'general');
        $replacements = [
            'user' => userAuth()->firstname,
        ];

        $welcomeUser = replacePlaceholders($text, $replacements);

        setMetaTags(translate('Dashboard', 'seo'));
        return view('frontend.user.dashboard', compact('welcomeUser', 'domains', 'emails', 'history', 'messages'));
    }

    public function getData()
    {
        // Get the authenticated user's ID
        $userId = userAuth()->id;

        // Create arrays to hold the counts and dates (labels) for the last 7 days
        $email_data = [];
        $email_labels = [];


        for ($i = 0; $i < 14; $i++) {
            // Get the date for the current iteration
            $date = Carbon::today()->subDays($i);

            // Format the date for the label (e.g., '30 SEP')
            $label = $date->format('d M');

            // Count the number of TrashMail records for this date
            $count = TrashMail::withTrashed()
                ->where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->count();

            // Add the count and the formatted date to the arrays
            $email_data[] = $count;
            $email_labels[] = strtoupper($label); // Format as uppercase
        }

        return response()->json([
            'email_data' => array_reverse($email_data),
            'email_labels' =>   array_reverse($email_labels),
        ]);
    }



    public function getData2()
    {
        $user_data = [];
        $user_labels = [];
        $email_data = [];
        $email_labels = [];
        $message_data = [];
        $message_labels = [];

        // Get the last 7 days
        for ($i = 0; $i < 30; $i++) {
            // Get the date for the current iteration
            $date = Carbon::today()->subDays($i);

            // Format the date for the label (e.g., '30 SEP')
            $label = $date->format('d M');

            // Count the number of TrashMail records for this date
            $count = User::whereDate('created_at', $date)->count();

            // Add the count and the formatted date to the arrays
            $user_data[] = $count;
            $user_labels[] = strtoupper($label); // Format as uppercase
        }


        for ($i = 0; $i < 14; $i++) {
            // Get the date for the current iteration
            $date = Carbon::today()->subDays($i);

            // Format the date for the label (e.g., '30 SEP')
            $label = $date->format('d M');

            // Count the number of TrashMail records for this date
            $count = TrashMail::withTrashed()
                ->whereDate('created_at', $date)
                ->count();

            // Add the count and the formatted date to the arrays
            $email_data[] = $count;
            $email_labels[] = strtoupper($label); // Format as uppercase
        }



        for ($i = 0; $i < 14; $i++) {
            // Get the date for the current iteration
            $date = Carbon::today()->subDays($i);

            // Format the date for the label (e.g., '30 SEP')
            $label = $date->format('d M');

            // Count the number of TrashMail records for this date
            $count = Statistic::whereDate('created_at', $date)
                ->count();

            // Add the count and the formatted date to the arrays
            $message_data[] = $count;
            $message_labels[] = strtoupper($label); // Format as uppercase
        }


        // Return both data and labels as JSON response
        return response()->json([
            'user_labels' => array_reverse($user_labels), // Reverse to match the order
            'user_data' =>   array_reverse($user_data),      // [0, 0, 1, 0, 0, 0, 0]Reverse to match the order
            'email_data' => array_reverse($email_data),
            'email_labels' =>   array_reverse($email_labels),
            'message_data' => array_reverse($message_data),
            'message_labels' =>   array_reverse($message_labels)
        ]);
    }
}
