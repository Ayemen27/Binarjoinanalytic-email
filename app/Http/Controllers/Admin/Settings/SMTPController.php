<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Mail\TestSMTP;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMailSettingsRequest;
use Mail;

class SMTPController extends Controller
{
    public function index()
    {
        return view('admin.settings.smtp');
    }

    public function update(UpdateMailSettingsRequest $request)
    {

        if ($request->mail_mailer == "smtp") {
            $keys = [
                'mail_mailer',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from_address',
                'mail_from_name',
                'mail_to_address',
            ];
        } else {
            $keys = ['mail_mailer', 'mail_from_address', 'mail_to_address'];
        }

        foreach ($keys as $key) {
            updateEnvFile(strtoupper($key), $request->$key);
            setSetting($key, $request->$key);
        }

        showToastr(__('lobage.toastr.update'));
        return back();
    }


    public function send(Request $request)
    {

        $request->validate([
            'test_email' => 'required|email',
        ]);


        $setting = Setting::pluck('value', 'key')->all();

        if ($setting['mail_mailer'] == "smtp") {

            if (
                empty($setting['mail_mailer']) || empty($setting['mail_host']) ||
                empty($setting['mail_port']) || empty($setting['mail_username']) ||
                empty($setting['mail_password']) || empty($setting['mail_encryption']) ||
                empty($setting['mail_from_name']) || empty($setting['mail_from_address'])
            ) {
                showToastr(__('Fill in all SMTP fields first !'), 'error');
                return back();
            } else {

                try {
                    \Mail::to($request->test_email)->send(new TestSMTP());
                    showToastr(__('Message has been sent successfully'));
                    return view('admin.settings.smtp');
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    showToastr(__('Incorrect authentication data'), 'error');
                    return back();
                }
            }
        } else {

            if (
                empty($setting['mail_mailer']) || empty($setting['mail_from_address'])
            ) {
                showToastr(__('Fill in all fields first !'), 'error');
                return back();
            } else {
                try {
                    Mail::to($request->test_email)->send(new TestSMTP());
                    showToastr(__('Message has been sent successfully'));
                    return view('admin.settings.smtp');
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    showToastr(__('Incorrect authentication data'), 'error');
                    return back();
                }
            }
        }
    }
}