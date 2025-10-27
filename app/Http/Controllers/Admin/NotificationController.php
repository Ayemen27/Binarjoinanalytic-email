<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use App\Http\Controllers\Controller;


class NotificationController extends Controller
{
    public function index()
    {
        $all_notifications = Notification::where("to_admin", 1)->orderBy('created_at', 'DESC')->paginate(25);

        return view('admin.notifications', compact('all_notifications'));
    }


    public function markAllAsRead()
    {
        Notification::where("to_admin", 1)->update(['is_read' => true]);

        showToastr(__('All notifications have been marked as read'));
        return back();
    }

    public function show(Notification $notification)
    {
        try {
            // Mark the notification as read
            $notification->update([
                'is_read' => 1
            ]);

            // Check if the action URL exists and is valid
            if ($notification->action) {
                return redirect($notification->action);
            } else {
                throw new \Exception('Action URL not found');
            }
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it or display a friendly message)
            // Redirect to a fallback route if the action is missing or invalid
            return redirect()->route('admin.notifications.index')->with('error', 'The action could not be performed.');
        }
    }


    public function delete()
    {
        Notification::where("to_admin", 1)->delete();
        showToastr(__('All notifications have been deleted'));
        return redirect(route('admin.notifications.index'));
    }
}