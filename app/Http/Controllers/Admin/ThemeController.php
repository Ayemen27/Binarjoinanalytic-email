<?php

namespace App\Http\Controllers\Admin;

use App\Models\Theme;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\StoreThemeRequest;
use App\Http\Requests\Admin\UpdateThemeRequest;


class ThemeController extends Controller
{

    public function index()
    {
        $get_themes = $this->get_themes();

        return view('admin.themes.index')->with('themes', Theme::all())
            ->with("get_themes", $get_themes);
    }


    public function active($theme)
    {
        $theme = Theme::where('unique_name', $theme)->firstOrFail();

        $theme->activateOneTheme();
        updateEnvFile('CURRENT_THEME', $theme->unique_name);

        !empty($theme->logo) ? setSetting('logo', $theme->logo) : "";
        !empty($theme->dark_logo) ? setSetting('dark_logo', $theme->dark_logo) : "";
        !empty($theme->favicon) ? setSetting('favicon', $theme->favicon) : "";


        showToastr(__('Theme activated successfully'));
        return back();
    }




    public function advanced()
    {
        $theme = Theme::where('status', '1')->first();
        return view('admin.themes.advanced')->with('theme', $theme);
    }

    public function update_advanced(Request $request)
    {
        $theme = Theme::where('status', '1')->first();

        $request->validate([
            'custom_css' => 'nullable',
            'custom_js' => 'nullable',
        ]);
        $theme->update([
            $theme->custom_css = $request->custom_css,
            $theme->custom_js = $request->custom_js,
        ]);

        showToastr(__('lobage.toastr.update'));
        return back();
    }


    public function appearance()
    {
        $theme = Theme::where('status', '1')->first();

        return view('admin.themes.appearance')->with('theme', $theme);
    }



    public function update_appearance(Request $request, ImageService $imageService)
    {
        $theme = Theme::where('status', '1')->first();
        $validation_rules = [];
        $colors = $theme->colors;
        foreach ($theme->colors as $key => $val) {
            $validation_rules[$key] = 'required';
        }

        $logo = $theme->logo;
        $favicon = $theme->favicon;
        $dark_logo = $theme->dark_logo;
        $path = "assets/themes/" . $theme->unique_name . "/img/";


        $validation_rules['logo'] = 'image|mimes:png,jpg,jpeg,svg,gif,webp|max:2048';
        $validation_rules['dark_logo'] = 'image|mimes:png,jpg,jpeg,svg,gif,webp|max:2048';
        $validation_rules['favicon'] = 'mimes:ico,png,jpg,jpeg,gif,webp|max:2048';

        $request->validate($validation_rules);

        try {
            foreach ($colors as $key => $val) {
                $colors[$key] = $request->$key;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }


        $this->updateCssFile($colors, $theme->unique_name);

        $time = time();

        if ($request->has('logo')) {
            $file = $imageService->updateThemeImage($request->logo, $path, "logo", $theme->logo);
            $logo = $file['filename'] . "?t=" . $time;
            setSetting('logo', $logo);
        }

        if ($request->has('dark_logo')) {
            $file = $imageService->updateThemeImage($request->dark_logo, $path, "dark_logo", $theme->dark_logo);
            $dark_logo = $file['filename'] . "?t=" . $time;
            setSetting('dark_logo', $dark_logo);
        }

        if ($request->has('favicon')) {
            $file = $imageService->updateThemeImage($request->favicon, $path, "favicon", $theme->favicon);
            $favicon = $file['filename'] . "?t=" . $time;
            setSetting('favicon', $favicon);
        }

        $theme->update([
            'colors' => $colors,
            'logo' => $logo,
            'dark_logo' => $dark_logo,
            'favicon' => $favicon,
        ]);

        showToastr(__('lobage.toastr.update'));
        return back();
    }


    public function about()
    {
        $theme = Theme::where('status', '1')->first();
        return view('admin.themes.about')->with('theme', $theme);
    }





    protected function updateCssFile(array $colors, $theme)
    {
        // Path to your CSS file
        $cssFilePath = public_path('assets/themes/' . $theme . '/css/style.css');

        // Check if the file exists to avoid file-related errors
        if (!File::exists($cssFilePath)) {
            throw new \Exception('CSS file does not exist at path: ' . $cssFilePath);
        }


        // dd($colors['primary_color']);

        if (isset($colors['primary_color'])) {

            $primaryGradient = $this->generateGradient($colors['primary_color']);
            $colors['primary_gradient'] = $primaryGradient;
        }

        // Read the existing CSS file
        $cssContent = File::get($cssFilePath);


        //dd($cssContent);

        // Iterate over the color settings and update the CSS variables
        foreach ($colors as $key => $val) {
            // Replace the old value with the new color
            $cssContent = preg_replace('/(--' . preg_quote($key) . ':\s*[^;]+;)/', '--' . $key . ': ' . $val . ';', $cssContent);
        }

        // Write the modified content back to the CSS file
        File::put($cssFilePath, $cssContent);
    }


    protected function hexToRgba($hex, $alpha = 1)
    {
        // Remove '#' if it's there
        $hex = str_replace('#', '', $hex);

        // Convert 3-digit hex to 6-digit hex
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        // Convert hex to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Return RGBA string
        return "rgba($r, $g, $b, $alpha)";
    }



    protected function generateGradient($hex)
    {
        // Remove '#' if it's there
        $hex = str_replace('#', '', $hex);

        // Convert hex to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Darken the color for gradient steps (this example darkens by 10% and 30%)
        $color1 = "rgba($r, $g, $b, 1)"; // Primary color at 0%
        $color2 = "rgba(" . max(0, $r - 25) . ", " . max(0, $g - 25) . ", " . max(0, $b - 25) . ", 1)"; // Darker color at 30%
        $color3 = "rgba(" . max(0, $r - 50) . ", " . max(0, $g - 50) . ", " . max(0, $b - 50) . ", 1)"; // Darker color at 100%

        // Return the radial gradient string
        return "radial-gradient(circle, $color1 0%, $color2 30%, $color3 100%)";
    }


    public function create()
    {
        return view('admin.themes.create');
    }

    public function store()
    {
        showToastr(__('This version does not support uploading any theme at the present time'), 'error');
        return back();
    }


    public function get_themes()
    {
        $cacheKey = 'themes';

        // Attempt to get data from the cache
        $newData = Cache::remember($cacheKey, now()->addHours(4), function () {
            $id = config('lobage.id');

            // Fetch data from the primary API
            $response = Http::get(config('lobage.api') . "get-ads?id=$id&type=1");

            // If the primary response is unsuccessful or null, try the backup
            if (!$response->successful() || is_null($response->json())) {
                // Use the backup API if the primary request fails
                $response = Http::get(config('lobage.api_v2') . "get-ads?id=$id&type=1");
            }

            // Return the final response data (either primary or backup)
            return $response->successful() ? $response->json() : null;
        });

        // If the data is not available or failed to fetch, return early
        if (!$newData || $newData['success'] !== true) {
            return null;
        }

        // Get the existing slugs from the log table
        $existingSlugs = Theme::pluck('unique_name')->toArray();

        // Find new slugs that don't exist in the logs
        $newSlugs = array_diff(array_column($newData['data'], 'slug'), $existingSlugs);

        $data = array_filter($newData['data'], function ($item) use ($newSlugs) {
            return in_array($item['slug'], $newSlugs);
        });

        return $data;
    }
}
