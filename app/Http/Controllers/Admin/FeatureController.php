<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feature;
use App\Http\Requests\Admin\StoreFeatureRequest;
use App\Http\Requests\Admin\UpdateFeatureRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filter\FilterRequest;
use App\Services\FeatureFilterService;


class FeatureController extends Controller
{

    protected $filterService;

    public function __construct(FeatureFilterService $filterService)
    {
        $this->filterService = $filterService;
    }



    public function index(FilterRequest $request)
    {
        $features = $this->filterService->filterFeatures($request);

        return view('admin.features.index')->with('features', $features);
    }


    public function create()
    {
        return view('admin.features.create');
    }


    public function store(StoreFeatureRequest $request)
    {

        $feature = new Feature($request->validated());
        $feature->translate_id = uniqid();
        $feature->save();
        showToastr(__('lobage.toastr.success'));
        return redirect(route('admin.features.index'));
    }


    public function edit(Feature $feature)
    {
        return view('admin.features.edit')->with('feature', $feature);
    }


    public function update(UpdateFeatureRequest $request, Feature $feature)
    {
        $feature->update($request->validated());
        showToastr(__('lobage.toastr.update'));
        return redirect(route('admin.features.index'));
    }


    public function destroy(Feature $feature)
    {
        $feature->delete();
        showToastr(__('lobage.toastr.delete'));
        return back();
    }
}