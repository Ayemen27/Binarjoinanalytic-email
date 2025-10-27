<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function header(Request $request)
    {
        if ($request->has('lang')) {
            $menus = Menu::with('children')->filteredByLang($request)->where('type', 0)->where('parent', "=", 0)->orderBy('position')->get();
            return view('admin.menus.header')->with('menus', $menus);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANG', 'en'));
        }
    }

    public function footer(Request $request)
    {
        if ($request->has('lang')) {
            $menus = Menu::with('children')->filteredByLang($request)->where('type', 1)->where('parent', "=", 0)->orderBy('position')->get();
            return view('admin.menus.footer')->with('menus', $menus);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANG', 'en'));
        }
    }

    public function sidebar(Request $request)
    {
        if ($request->has('lang')) {
            $menus = Menu::with('children')->filteredByLang($request)->where('type', 2)->where('parent', "=", 0)->orderBy('position')->get();
            return view('admin.menus.sidebar')->with('menus', $menus);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANG', 'en'));
        }
    }


    public function create_footer()
    {
        return view('admin.menus.create')->with('type', 1);
    }

    public function create_sidebar()
    {
        return view('admin.menus.create')->with('type', 2);
    }


    public function create_header()
    {
        return view('admin.menus.create')->with('type', 0);
    }

    public function store(StoreMenuRequest $request)
    {
        $menu = Menu::create($request->validated());
        showToastr(__('lobage.toastr.success'));

        if ($request->type == 1) {
            return redirect(route('admin.menus.footer', "lang=" . $request->lang . ""));
        } elseif ($request->type == 2) {
            return redirect(route('admin.menus.sidebar', "lang=" . $request->lang . ""));
        } else {
            return redirect(route('admin.menus.header', "lang=" . $request->lang . ""));
        }
    }


    public function update_position(Request $request)
    {

        $data = json_decode($request->position, true);
        $i = 1;
        $sub_i = 1;

        foreach ($data as $arr) {
            $menu = Menu::where('id', $arr['id'])->first();
            $menu->update([$menu->position = $i, $menu->parent = 0]);

            if (isset($arr['children'])) {
                foreach ($arr['children'] as $children) {
                    $menu = Menu::where('id', $children['id'])->first();
                    $menu->update([$menu->position = $sub_i, $menu->parent = $arr['id']]);
                    $sub_i++;
                }
            }

            $i++;
        }

        showToastr(__('lobage.toastr.success'));
        return back();
    }


    public function edit(Menu $menu)
    {
        return view('admin.menus.edit')->with('menu', $menu);
    }


    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated());

        if ($menu->children->count() > 0) {
            foreach ($menu->children as $children) {
                $children->update([
                    $children->lang = $request->lang,
                ]);
            }
        }

        showToastr(__('lobage.toastr.update'));

        if ($request->type == 1) {
            return redirect(route('admin.menus.footer', "lang=" . $request->lang . ""));
        } elseif ($request->type == 2) {
            return redirect(route('admin.menus.sidebar', "lang=" . $request->lang . ""));
        } else {
            return redirect(route('admin.menus.header', "lang=" . $request->lang . ""));
        }
    }


    public function destroy(Menu $menu)
    {
        if ($menu->children->count() > 0) {
            foreach ($menu->children as $children) {
                $children->delete();
            }
        }
        $menu->delete();
        showToastr(__('lobage.toastr.delete'));
        return back();
    }
}
