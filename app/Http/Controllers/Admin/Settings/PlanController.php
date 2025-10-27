<?php

namespace App\Http\Controllers\Admin\Settings;

use Lobage\Planify\Models\Plan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePlanSettingsRequest;


class PlanController extends Controller
{
    public function index()
    {
        $free = Plan::where('tag', 'free')->first();
        $guest = Plan::where('tag', 'guest')->first();

        return view('admin.settings.plans')->with('free', $free)->with('guest', $guest);
    }

    public function update(UpdatePlanSettingsRequest $request)
    {
        $guest = Plan::where('tag', 'guest')->first();

        $guest->getFeatureByTag('ads')->update(['value' => $request->guest_ads]);
        $guest->getFeatureByTag('history')->update(['value' => $request->guest_history]);
        $guest->getFeatureByTag('attachments')->update(['value' => $request->guest_attachments]);
        $guest->getFeatureByTag('premium_domains')->update(['value' => $request->guest_premium_domains]);

        $free = Plan::where('tag', 'free')->first();

        $free->getFeatureByTag('domains')->update(['value' => $request->domains]);
        $free->getFeatureByTag('messages')->update(['value' => $request->messages]);
        $free->getFeatureByTag('ads')->update(['value' => $request->ads]);
        $free->getFeatureByTag('history')->update(['value' => $request->history]);
        $free->getFeatureByTag('attachments')->update(['value' => $request->attachments]);
        $free->getFeatureByTag('premium_domains')->update(['value' => $request->premium_domains]);

        showToastr(__('lobage.toastr.update'));
        return back();
    }
}
