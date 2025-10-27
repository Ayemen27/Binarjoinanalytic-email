<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class CacheController extends Controller
{
    public function index()
    {
        $cacheSize = $this->getCacheSize();

        //dd($cacheSize);
        return view('admin.settings.cache')->with('cacheSize', $cacheSize);
    }


    public function clear()
    {
        Artisan::call('cache:clear'); // Clear Laravel cache
        return redirect()->route('admin.settings.cache')->with('success', 'Cache cleared successfully!');
    }



    private function getCacheSize()
    {
        $cachePath = storage_path('framework/cache');
        $size = 0;

        foreach (File::allFiles($cachePath) as $file) {
            $size += $file->getSize();
        }

        return $this->formatSizeUnits($size);
    }

    // Format size to human-readable units
    private function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }
}