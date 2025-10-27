<?php

namespace App\Http\Controllers\Frontend;


use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{

    public function index($slug)
    {

        $page = Page::where('slug', $slug)->firstorfail();


        if ($page['status'] == 1) {

            $page->incrementViewCount();

            setMetaTags(
                $page->meta_title ?? $page->title,
                $page->meta_description ?? substr(strip_tags($page->content), 0, 155)
            );

            return view()->theme('page')->with('page', $page);
        } else {
            abort(404);
        }
    }
}