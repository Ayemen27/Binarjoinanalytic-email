<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\Frontend\RunCronRequest;
use App\Http\Controllers\Controller;

class CronJobController extends Controller
{
    public function execute(RunCronRequest $request)
    {
        ini_set('max_execution_time', 0);

        if (getSetting('cronjob_key') != $request->key) {
            return response()->json(['message' => __('Invalid Cron Job Key')], 403);
        }

        Artisan::call('schedule:run');

        setSetting('cronjob_last_time', Carbon::now());

        return response()->json([
            'status' => 'success',
            'message' => translate('Cron Job executed successfully'),
        ], 200);
    }
}