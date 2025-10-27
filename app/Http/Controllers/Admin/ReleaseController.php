<?php

namespace App\Http\Controllers\Admin;


use App\Models\Log;
use App\Models\Plugin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


class ReleaseController extends Controller
{
    public function index()
    {
        $cacheKey = 'plugins';

        // Attempt to get data from the cache
        $newData = Cache::remember($cacheKey, now()->addDay(), function () {
            // Fetch data from external API
            $response = Http::get(config('lobage.api') . 'get-ads?id=1&type=1');

            // Handle potential errors from the external request
            if (!$response->successful()) {
                // You might want to log this error or return a specific message
                return null;
            }

            return $response->json();
        });

        // If the data is not available or failed to fetch, return early
        if (!$newData || $newData['success'] !== true) {
            return null;
        }

        // Get the existing slugs from the log table
        $existingSlugs = Plugin::pluck('unique_name')->toArray();

        // Find new slugs that don't exist in the logs
        $newSlugs = array_diff(array_column($newData['data'], 'slug'), $existingSlugs);

        $newSlugsData = array_filter($newData['data'], function ($item) use ($newSlugs) {
            return in_array($item['slug'], $newSlugs);
        });

        // Count the number of new slugs
        dd($newSlugsData);
        #dd($newSlugs);

        return response()->json(['new_slugs_count' => $newSlugsCount, 'new_slugs' => $newSlugs]);
    }


    public function get_data()
    {
        $cacheKey = 'broadcasts_data';

        // Attempt to get data from the cache
        $newData = Cache::remember($cacheKey, now()->addDay(), function () {
            // Fetch data from external API
            $response = Http::get(config('lobage.api') . 'get-broadcasts?id=1');

            // Handle potential errors from the external request
            if (!$response->successful()) {
                // You might want to log this error or return a specific message
                return null;
            }

            return $response->json();
        });

        // If the data is not available or failed to fetch, return early
        if (!$newData || $newData['success'] !== true) {
            return null;
        }

        Cache::forget('existing_slugs_release');

        // Get the existing slugs from the log table
        $existingSlugs = Log::where('key', 'release')->pluck('value')->toArray();

        // Find new slugs that don't exist in the logs
        $newSlugs = array_diff(array_column($newData['data'], 'slug'), $existingSlugs);

        if (count($newSlugs) === 1) {
            $newSlugs = array_values($newSlugs); // Converts to a numeric array
        }

        // Count the number of new slugs
        $newSlugsCount = count($newSlugs);

        if ($newSlugsCount > 0) {

            if (!empty($newSlugs)) {
                $newSlugsString = implode(',', $newSlugs);
            } else {
                $newSlugsString = ''; // If no new slugs, set an empty string
            }

            $response = Http::get(config('lobage.api') . 'get-broadcasts?id=1&slug=' . $newSlugsString);

            if (!$response->successful()) {
                // You might want to log this error or return a specific message
                return null;
            }

            if (!$response || $response['success'] !== true) {
                return null;
            }

            foreach ($newSlugs as $slug) {
                Log::create([
                    'key' => 'release',
                    'value' => $slug,
                ]);
            }

            return true;
        }
    }
}
