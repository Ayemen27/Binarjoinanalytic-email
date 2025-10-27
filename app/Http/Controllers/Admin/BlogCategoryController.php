<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use App\Http\Requests\Admin\StoreBlogCategoryRequest;
use App\Http\Requests\Admin\UpdateBlogCategoryRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filter\FilterRequest;
use App\Services\BlogCategoryFilterService;

class BlogCategoryController extends Controller
{

    protected $filterService;

    public function __construct(BlogCategoryFilterService $filterService)
    {
        $this->filterService = $filterService;
    }



    public function index(FilterRequest $request)
    {
        $categories = $this->filterService->filterCategories($request);
        return view('admin.blog.categories.index')->with('categories', $categories);
    }

    public function create()
    {
        return view('admin.blog.categories.create');
    }


    public function store(StoreBlogCategoryRequest $request)
    {
        BlogCategory::create($request->validated());
        showToastr(__('lobage.toastr.success'));
        return redirect(route('admin.blog.categories.index'));
    }


    public function edit(BlogCategory $category)
    {
        return view('admin.blog.categories.edit')->with('category', $category);
    }


    public function update(UpdateBlogCategoryRequest $request, BlogCategory $category)
    {
        $category->update($request->validated());
        showToastr(__('lobage.toastr.update'));
        return redirect(route('admin.blog.categories.index'));
    }


    public function destroy(BlogCategory $category)
    {
        $category->delete();
        showToastr(__('lobage.toastr.delete'));
        return back();
    }
}
