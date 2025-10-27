<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Ad;
use App\Http\Requests\Admin\UpdateAdRequest;
use App\Http\Controllers\Controller;

class AdController extends Controller
{

    public function index()
    {
        return view('admin.settings.ads.index')->with('ads', Ad::all());
    }

    public function edit(Ad $ad)
    {
        return view('admin.settings.ads.edit')->with("ad", $ad);
    }

    public function update(UpdateAdRequest $request, Ad $ad)
    {
        $ad->update([
            'code' => $request['code'],
            'desktop' => $request['desktop'] ?? 0,
            'tablet' => $request['tablet'] ?? 0,
            'phone' => $request['phone'] ?? 0,
            'status' => $request['status'],
        ]);

        showToastr(__('lobage.toastr.update'));
        return redirect(route('admin.settings.ads.index'));
    }
}
