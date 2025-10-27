<?php

namespace App\Http\Controllers\Admin;

use App\Models\Section;
use App\Http\Requests\Admin\StoreSectionRequest;
use App\Http\Requests\Admin\UpdateSectionRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function index(Request $request)
    {
        if ($request->has('lang')) {

            $sections = Section::filteredByLang($request)->orderBy('position')->get();

            return view('admin.sections.index')->with('sections', $sections);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANG', 'en'));
        }
    }

    public function create()
    {
        return view('admin.sections.create');
    }

    public function store(StoreSectionRequest $request)
    {
        $lastPosition = Section::orderBy('position', 'desc')->first()->position ?? 0;

        $name = str_replace(" ", "-", $request['title']) . "-" . time();
        $section = new Section($request->validated());
        $section->name = $name;
        $section->position = $lastPosition;
        $section->type = "html";
        $section->save();
        showToastr(__('lobage.toastr.success'));

        return redirect(route('admin.sections.index', "lang=" . $request->lang . ""));
    }


    public function update_position(Request $request)
    {
        $data = json_decode($request->position, true);
        $i = 1;

        foreach ($data as $arr) {
            $section = Section::where('id', $arr['id'])->first();
            $section->update([$section->position = $i]);
            $i++;
        }

        showToastr(__('lobage.toastr.update'));
        return back();
    }

    public function edit(Section $section)
    {
        return view('admin.sections.edit')->with('section', $section);
    }


    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->update($request->all());
        showToastr(__('lobage.toastr.update'));

        return redirect(route('admin.sections.index', "lang=" . $section->lang . ""));
    }


    public function destroy(Section $section)
    {
        if ($section->type != "html") {
            showToastr(__('This section cannot be deleted'), "error");
            return back();
        }
        $section->delete();
        showToastr(__('lobage.toastr.delete'));
        return back();
    }
}
