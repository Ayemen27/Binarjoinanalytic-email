<?php

namespace App\Http\Controllers\Admin;


use Carbon\Carbon;
use App\Models\Page;
use App\Models\User;
use App\Models\Domain;
use App\Models\Message;
use App\Models\BlogPost;
use App\Models\Statistic;
use App\Models\TrashMail;
use App\Models\Notification;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{



    public function index()
    {

        $domains = Domain::count();
        $emails = TrashMail::withTrashed()->count();
        $messages = Statistic::where('key', 'messages')->count();
        $fav_messages = Message::count();
        $users = User::count();
        $last_users = User::orderBy('created_at', 'desc')->take(6)->get();

        $posts = BlogPost::count();
        $pages = Page::count();
        $notifications = Notification::count();

        return view('admin.dashboard', compact('domains', 'emails', 'fav_messages', 'messages', 'users', 'posts', 'pages', 'notifications', 'last_users'));

        //return view('admin.dashboard', compact('users', 'get_all_users', 'get_inactive_users', 'get_active_users', 'get_banned_users'));
    }


    public function getData()
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
