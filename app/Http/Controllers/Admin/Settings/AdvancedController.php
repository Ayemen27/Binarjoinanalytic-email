<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Imap;
use App\Models\Setting;
use Illuminate\Http\Request;
use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdvancedSettingsRequest;


class AdvancedController extends Controller
{
    public function index()
    {
        return view('admin.settings.advanced')->with('imap', Imap::where('tag', 'main')->first());
    }

    public function update(UpdateAdvancedSettingsRequest $request)
    {
        $keys = [
            'forbidden_ids',
            'fetch_messages',
            'email_length',
            'fake_emails',
            'fake_messages',
            'allowed_files',
            'time_unit',
            'email_lifetime',
            'imap_retention_days',
            'history_retention_days'
        ];

        //dd($request->all());



        $imap = Imap::updateOrCreate(
            ['tag' => "main"], // Use 'tag' as the unique identifier
            [
                'host' => $request->imap_host,
                'username' => $request->imap_user,
                'password' => $request->imap_pass,
                'encryption' => $request->imap_encryption,
                'validate_certificates' => $request->validate_certificates,
                'port' => $request->imap_port,
            ]
        );

        foreach ($keys as $key) {
            $value = $request->input($key);
            setSetting($key, $request->$key);
            //Setting::where('key', $key)->update(['value' => $value]);
        }

        showToastr(__('lobage.toastr.update'));
        return back();
    }


    public function check_imap(Request $request)
    {
        if (is_demo()) {
            return '<div class="error">ERROR [0s]:  Demo version some features are disabled </div>';
        }

        $host = $request->host;
        $user = $request->user;
        $pass = $request->pass;
        $port = $request->port;
        $encryption = $request->encryption;
        $certificate = $request->certificate;

        $time1 = time();

        try {

            if (empty($host) || empty($user) || empty($pass) || empty($port) || empty($encryption)) {

                return '<div class="error">ERROR:  The server data is incomplete :/ </div>';
            }

            $cm = new ClientManager(base_path('config/imap.php'));

            $client = $cm->make([
                'protocol'      => 'imap',
                'host' => $host,
                'port' => $port,
                'encryption' => $encryption,
                'validate_cert' => $certificate,
                'username' => $user,
                'password' => $pass,
                'authentication' => null,
            ]);

            $client->connect();

            $status = $client->isConnected();

            if ($status) {
                $t = time() - $time1;
                return '<div class="success">SUCCESS [' . $t . 's]: Your IMAP Server Is Connected</div>';
            } else {

                return '<div class="error">ERROR: IMAP connection failed , Please Check your Imap </div>';
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return '<div class="error">ERROR: Please Check your Imap Server</div>';
        }
    }


    public function check_imap2(Request $request)
    {

        if (is_demo()) {
            return response()->json(['success' => false, 'message' => "ERROR:  Demo version some features are disabled "]);
        }

        $host = $request->host;
        $user = $request->user;
        $pass = $request->pass;
        $port = $request->port;
        $encryption = $request->encryption;
        $certificate = $request->certificate;

        $time1 = time();

        try {

            if (empty($host) || empty($user) || empty($pass) || empty($port) || empty($encryption)) {

                return response()->json(['success' => false, 'message' => "ERROR: Please enter a valid IMAP "]);
            }

            $cm = new ClientManager(base_path('config/imap.php'));

            $client = $cm->make([
                'protocol'      => 'imap',
                'host' => $host,
                'port' => $port,
                'encryption' => $encryption,
                'validate_cert' => $certificate,
                'username' => $user,
                'password' => $pass,
                'authentication' => null,
            ]);

            $client->connect();

            $status = $client->isConnected();

            if ($status) {
                $t = time() - $time1;
                $message =  '<div class="success">SUCCESS [' . $t . 's]: Your IMAP server is connected</div>';

                return response()->json(['success' => true, 'message' =>  $message]);
            } else {

                return response()->json(['success' => false, 'message' => "ERROR: IMAP connection failed , Please Check your Imap"]);
            }
        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return response()->json(['success' => false, 'message' => "ERROR: Please Check your Imap Server"]);
        }
    }
}
