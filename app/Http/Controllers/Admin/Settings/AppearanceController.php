<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;



class AppearanceController extends Controller
{
    public function index()
    {
        return view('admin.settings.appearance');
    }

    public function update(Request $request)
    {

        $validation_rules = [];
        $colors = json_decode(getSetting('colors'), true);

        foreach ($colors as $key => $val) {
            $validation_rules[$key] = 'required';
        }

        $request->validate($validation_rules);

        foreach ($colors as $key => $val) {
            $colors[$key] = $request->$key;
        }

        SetSetting('colors', $colors);

        $this->updateCssFile($colors);
        $this->updateCssFile($colors, 'assets/css/error_style.css');

        showToastr(__('lobage.toastr.update'));
        return back();
    }


    protected function updateCssFile(array $colors, $path = 'assets/css/style.css')
    {
        // Path to your CSS file
        $cssFilePath = public_path($path);

        // Check if the file exists to avoid file-related errors
        if (!File::exists($cssFilePath)) {
            throw new \Exception('CSS file does not exist at path: ' . $cssFilePath);
        }


        // dd($colors['primary_color']);

        if (isset($colors['primary_color'])) {
            $primaryOpacity = $this->hexToRgba($colors['primary_color'], 0.1);
            $colors['primary_opacity'] = $primaryOpacity;
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
}