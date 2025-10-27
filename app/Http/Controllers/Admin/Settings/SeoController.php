<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Seo;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSeoRequest;
use App\Http\Requests\Admin\UpdateSeoRequest;

class SeoController extends Controller
{

    public function index()
    {
        return view('admin.settings.seo.index')->with('all_seo', Seo::all());
    }


    public function create()
    {
        $lang_array = Seo::pluck('lang')->toarray();
        return view('admin.settings.seo.create')->with("lang_array", $lang_array);
    }

    public function store(StoreSeoRequest $request, ImageService $imageService)
    {

        $validatedPostData = $request->validated();
        unset($validatedPostData['image']);

        $seo = Seo::create($validatedPostData);
        if ($request->hasFile('image')) {
            $file = $imageService->storeSeoImage($request->file('image'));
            $seo->update(['image' => $file['filename']]);
        }

        showToastr(__('lobage.toastr.success'));
        return redirect(route('admin.settings.seo.index'));
    }


    public function edit(Seo $seo)
    {
        $lang_array = Seo::where('lang', '!=', $seo->lang)->pluck('lang')->toarray();


        return view('admin.settings.seo.edit')->with("seo", $seo)->with("lang_array", $lang_array);
    }

    public function update(UpdateSeoRequest $request, Seo $seo, ImageService $imageService)
    {

        $validatedPostData = $request->validated();
        unset($validatedPostData['image']);
        $seo->update($validatedPostData);


        if ($request->hasFile('image')) {
            $file = $imageService->updateSeoImage($request->image, $seo->image);
            $seo->update(['image' => $file['filename']]);
        }

        showToastr(__('lobage.toastr.update'));
        return back();
    }


    public function destroy(Seo $seo)
    {
        if ($seo->lang == getSetting('default_language') || Seo::all()->count() == 1) {
            showToastr(__('The default seo cannot be deleted'), "error");
            return back();
        }

        removeFileOrFolder($seo->image);
        $seo->delete();
        showToastr(__('lobage.toastr.delete'));
        return back();
    }
}
