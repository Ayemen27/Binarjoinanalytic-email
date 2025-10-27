<?php

namespace App\Http\Controllers\Admin\Settings;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\InstallService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;


class LicenseController extends Controller
{
    protected $installService;

    public function __construct(InstallService $installService)
    {
        $this->installService = $installService;
    }


    public function index()
    {
        // Path to the JSON file in the public directory
        $filePath = public_path('license.json');

        // Read the file contents
        if (File::exists($filePath)) {
            $jsonData = json_decode(File::get($filePath), true);
        } else {
            $jsonData = []; // Empty array if the file doesn't exist
        }


        $supportUntil = $jsonData['support'] ?? null;

        // Check if the support has expired
        $isSupportExpired = false;
        if ($supportUntil) {
            $isSupportExpired = Carbon::now()->gt(Carbon::parse($supportUntil));
            setSetting("is_support_expired", $isSupportExpired);
        }

        // Pass the JSON data to the view
        return view('admin.settings.license', compact('jsonData', 'isSupportExpired'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|string',
        ]);

        // Process the license key
        $data = $this->installService->checkLicense($request->input('purchase_code'));

        if (!$data['status'] || $data === null) {
            return back()
                ->withErrors(['message' => $data['message']])
                ->with('action', $data['action'] ?? false);
        }

        $jsonData = json_encode($data['data'], JSON_PRETTY_PRINT);

        // Define the file name with extension
        $filename = 'license.json';

        File::put($filename, $jsonData);

        return back();
    }
}
