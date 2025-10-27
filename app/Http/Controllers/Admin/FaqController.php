<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    public function index(Request $request)
    {
        if ($request->has('lang')) {
            $faqs = Faq::filteredByLang($request)->orderBy('position')->get();
            return view('admin.faqs.index')->with('faqs', $faqs);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANG', 'en'));
        }
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(StoreFaqRequest $request)
    {
        $lastPosition = Faq::orderBy('position', 'desc')->first()->position ?? 0;

        $faq = new Faq($request->validated());
        $faq->position = $lastPosition;
        $faq->translate_id = uniqid();
        $faq->save();

        showToastr(__('lobage.toastr.success'));
        return redirect(route('admin.faqs.index', "lang=" . $request->lang . ""));
    }


    public function update_position(Request $request)
    {
        $data = json_decode($request->position, true);
        $i = 1;

        foreach ($data as $arr) {
            $faq = Faq::where('id', $arr['id'])->first();
            $faq->update([$faq->position = $i]);
            $i++;
        }

        showToastr(__('lobage.toastr.update'));
        return back();
    }


    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit')->with('faq', $faq);
    }


    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $faq->update($request->validated());
        showToastr(__('lobage.toastr.update'));
        return redirect(route('admin.faqs.index', "lang=" . $request->lang . ""));
    }


    public function destroy(Faq $faq)
    {
        $faq->delete();
        showToastr(__('lobage.toastr.delete'));
        return back();
    }
}
