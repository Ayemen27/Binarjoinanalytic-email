<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filter\FilterRequest;
use App\Services\PageFilterService;

class PageController extends Controller
{

    protected $filterService;

    public function __construct(PageFilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function index(FilterRequest $request)
    {
        $pages  = $this->filterService->filterPages($request);
        return view('admin.pages.index')->with('pages', $pages);
    }


    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(StorePageRequest $request)
    {
        Page::create($request->validated());
        showToastr(__('lobage.toastr.success'));
        return redirect(route('admin.pages.index'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit')->with('page', $page);
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $page->update($request->validated());
        showToastr(__('lobage.toastr.update'));
        return redirect(route('admin.pages.index'));
    }

    public function destroy(Page $page)
    {
        $page->delete();
        showToastr(__('lobage.toastr.delete'));
        return back();
    }
}
