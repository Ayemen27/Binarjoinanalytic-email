<?php

namespace App\Http\Controllers\Frontend;


use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{

    public function index()
    {
        setMetaTags(translate('Blog', 'seo'));

        $limit = getSetting('total_posts_per_page');
        $posts = BlogPost::where("status", 1)->where("lang", getCurrentLang())->orderBy('created_at', 'DESC')->paginate($limit);
        return view()->theme('blog.index')->with('posts', $posts);
    }


    public function posts($slug)
    {
        $post = BlogPost::where('slug', $slug)->firstorfail();

        if ($post['status'] == 1) {

            $post->incrementViewCount();

            setMetaTags(
                $post->meta_title ?? $post->title,
                $post->meta_description ?? $post->description,
                $post->tags ?? null,
                $post->image
            );

            return view()->theme('blog.post')->with('post', $post);
        } else {
            abort(404);
        }
    }


    public function category($category)
    {

        $category = BlogCategory::where('slug', $category)->firstorfail();

        if (getCurrentLang() == $category->lang) {
            $limit = getSetting('total_posts_per_page');

            $posts = BlogPost::where("category_id", $category->id)->where("status", 1)->orderBy('created_at', 'DESC')->paginate($limit);

            setMetaTags($category->name);

            return view()->theme('blog.category', compact('posts', 'category'));
        } else {

            return redirect(route('index'));
        }
    }
}
