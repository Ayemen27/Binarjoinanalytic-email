<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Http\Controllers\Controller;
use App\Services\ImageService;
use App\Http\Requests\Filter\FilterRequest;
use App\Services\BlogPostFilterService;



class BlogPostController extends Controller
{
    protected $filterService;

    public function __construct(BlogPostFilterService $filterService)
    {
        $this->filterService = $filterService;
    }



    public function index(FilterRequest $request)
    {
        $posts = $this->filterService->filterPosts($request);

        return view('admin.blog.posts.index')->with('posts', $posts);
    }

    public function create()
    {
        $categories = BlogCategory::all();
        if ($categories->count() > 0) {
            return view('admin.blog.posts.create');
        } else {
            showToastr(__('Create a Category first'));
            return redirect(route('admin.blog.categories.create'));
        }
    }

    public function store(StoreBlogPostRequest $request, ImageService $imageService)
    {
        $validatedPostData = $request->validated();
        $validatedPostData['category_id'] = $validatedPostData['category'];
        unset($validatedPostData['category']);
        unset($validatedPostData['image']);

        $file = $imageService->storePostImage($request->file('image'));  // Use the ImageService

        $post = new BlogPost($validatedPostData);
        $post->image = $file['filename'];
        $post->small_image = $file['small_filename'];
        $post->save();

        showToastr(__('lobage.toastr.success'));
        return redirect(route('admin.blog.posts.index'));
    }

    public function edit(BlogPost $post)
    {
        $categories = BlogCategory::where('lang', $post->lang)->get();

        return view('admin.blog.posts.edit')->with('post', $post)->with('categories', $categories);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $post, ImageService $imageService)
    {

        $validatedPostData = $request->validated();
        $validatedPostData['category_id'] = $validatedPostData['category'];
        unset($validatedPostData['category']);
        unset($validatedPostData['image']);

        $post->update($validatedPostData);

        if ($request->hasFile('image')) {
            $file = $imageService->updatePostImage($request->image, $post->image, $post->small_image);
            $post->update(['image' => $file['filename'], 'small_image' => $file['small_filename']]);
        }

        showToastr(__('lobage.toastr.update'));
        return redirect(route('admin.blog.posts.index'));
    }

    public function destroy(BlogPost $post)
    {
        removeFileOrFolder($post->image);
        removeFileOrFolder($post->small_image);
        $post->delete();
        showToastr(__('lobage.toastr.delete'));
        return back();
    }

    public function getCategory($lang)
    {
        $categories = BlogCategory::where('lang', $lang)->pluck("name", "id");
        if ($categories->count() > 0) {
            return response()->json($categories);
        } else {
            return response()->json(['message' => __('No categories on this language')]);
        }
    }
}
