<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Section;
use App\Models\Language;
use App\Models\Translate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TranslateFilterService;
use App\Http\Requests\Admin\AutoSendRequest;
use App\Http\Requests\Filter\TranslateFilterRequest;


class LanguageController extends Controller
{

    protected $filterService;

    public function __construct(TranslateFilterService $filterService)
    {
        $this->filterService = $filterService;
    }


    public function index()
    {



        $languages = Language::with('translations')->get(); // Eager load translations

        // Add a completion percentage to each language
        $languages->each(function ($lang) {
            $totalTranslations = $lang->translations->count();
            $completedTranslations = $lang->translations->where('value', '!=', '')->count();

            if ($totalTranslations > 0) {
                $lang->completionPercentage = ($completedTranslations / $totalTranslations) * 100;
            } else {
                $lang->completionPercentage = 0;
            }
        });

        return view('admin.settings.languages.index', compact('languages'));
    }


    public function create()
    {
        $lang_array = Language::pluck('code')->toarray();
        return view('admin.settings.languages.create')->with('lang_array', $lang_array);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'lang' => 'required|max:2|unique:languages,code',
            'direction' => 'boolean',
        ]);

        $language = new Language();
        $language->name = $request->name;
        $language->code = $request->lang;
        $language->direction = $request->direction;

        $language->save();

        if ($language) {

            $translates = Translate::where('lang', getSetting('default_language'))->get();
            foreach ($translates as $translate) {

                $new_translate = new Translate();
                $new_translate->lang = $request->lang;
                $new_translate->key = $translate->key;
                $new_translate->collection = $translate->collection;
                $new_translate->type = $translate->type;
                $new_translate->value = "";
                $new_translate->save();
            }

            $sections = Section::where('lang', getSetting('default_language'))->get();
            foreach ($sections as $section) {
                $new_section = new Section();
                $new_section->title = $section->title;
                $new_section->status = $section->status;
                $new_section->position = $section->position;
                $new_section->name = $section->name;
                $new_section->lang = $request->lang;
                $new_section->type = $section->type;
                $new_section->save();
            }
        }

        showToastr(__('lobage.toastr.success'));
        return redirect(route('admin.settings.languages.index'));
    }

    public function translate($code, TranslateFilterRequest $request)
    {
        $language = Language::where('code', $code)->firstOrFail();

        $collections = Translate::selectRaw('collection, MIN(id) as min_id')
            ->groupBy('collection')
            ->orderBy('min_id', 'ASC')
            ->pluck('collection')
            ->toArray();


        $translates = $this->filterService->filterTranslate($request, $code);

        return view('admin.settings.languages.translates', compact('language', 'translates', 'collections'));
    }



    public function update_translate(Request $request)
    {
        foreach ($request->values as $id => $value) {
            $translation = Translate::where('id', '=', $id)->first();
            if ($translation != null) {
                $translation->value = $value;
                $translation->save();
            }
        }

        showToastr(__('lobage.toastr.update'));
        return redirect()->back();
    }

    public function edit(Language $language)
    {
        $lang_array = Language::pluck('code')->toarray();

        if (($key = array_search($language->code, $lang_array)) !== false) {
            unset($lang_array[$key]);
        }

        return view('admin.settings.languages.edit')->with('language', $language)->with('lang_array', $lang_array);
    }


    public function update(Request $request, Language $language)
    {
        $request->validate([
            'name' => 'required|max:255',
            'lang' => 'required|max:2|unique:languages,code,' . $language->id,
            'direction' => 'boolean',
        ]);

        $language->update([
            'name' => $request->name,
            'code' => $request->lang,
            'direction' => $request->direction,
        ]);

        showToastr(__('lobage.toastr.update'));
        return back();
    }

    public function destroy(Language $language)
    {
        if (getSetting('default_language') == $language->code) {
            showToastr(__('Default language cannot be deleted'), 'error');
            return back();
        }
        $language->delete();
        showToastr(__('lobage.toastr.delete'));
        return redirect(route('admin.settings.languages.index'));
    }



    public function auto()
    {
        if (!isPluginEnabled('facebook_comments')) {
            return redirect(route('admin.settings.languages.index'));
        }

        $languages = Language::with('translations')->get(); // Eager load translations
        $unique_groups = Translate::pluck('collection')->unique()->values();





        return view('admin.settings.languages.instant', compact('languages', 'unique_groups'));
    }



    public function autoSend(AutoSendRequest $request)
    {


        dd($request->all());
    }
}