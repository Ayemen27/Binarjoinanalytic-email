<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class BlogController extends Controller
{
    public function index()
    {
        return view('admin.settings.blog');
    }


    public function update(Request $request)
    {

        $request->validate([
            'enable_blog' => 'required',
            'popular_post_order_by' => 'required',
            'total_popular_posts_home' => 'required',
            'total_posts_per_page' => 'required',
        ]);

        $settings = Setting::whereIn(
            'key',
            [
                'enable_blog',
                'popular_post_order_by',
                'total_popular_posts_home',
                'total_posts_per_page'
            ]
        )->get();

        foreach ($settings as $setting) {
            $key = $setting->key;
            setSetting($key, $request->$key);
        }
        showToastr(__('lobage.toastr.update'));
        return back();
    }
}
