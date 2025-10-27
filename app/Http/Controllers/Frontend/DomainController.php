<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use App\Models\Domain;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreDomainRequest;

class DomainController extends Controller
{

    public function index()
    {

        setMetaTags(translate('Domains', 'seo'));
        $user = Auth::user();
        $domains = Domain::where('user_id', $user->id)->paginate(15);
        $count_domains = Domain::where('user_id', $user->id)->count();
        $get_domain_feature = getFeatureValue('domains');
        return view('frontend.user.domains.index', compact('domains', 'count_domains', 'get_domain_feature'));
    }

    public function create()
    {
        setMetaTags(translate('Add New Domain', 'seo'));

        if (canUseFeature('domains')) {
            return view('frontend.user.domains.create');
        } else {
            showToastr(translate('You do not have access,Please upgrade your account', 'alerts'), 'error');
            return redirect(route('domains.index'));
        }
    }

    public function store(StoreDomainRequest $request)
    {
        if (canUseFeature('domains')) {
            $user = Auth::user();
            $user->subscription('main')->recordFeatureUsage('domains', 1);
            $domain = Domain::create([
                "domain" => $request->domain,
                "user_id" => $user->id,
                "status" =>  0,
                "type" =>  2,
            ]);

            sendNotification('A new domain ' . $request->domain . ' has been added by ' . $user->getFullName(), 'domain', true, $user->id, route('admin.domains.edit', $domain->id));

            $short_codes = [
                'user_fullname'   => $user->getFullName(),
                'user_email'      => $user->email,
                'domain_name'     => $request->domain,
                'domain_url'      => "https://" . $request->domain,
                'admin_panel_url' => route('admin.domains.edit', $domain->id),
                'website_name'    => config('app.name'),
                'website_url'     => config('app.url'),
            ];

            sendEmail(getSetting('mail_to_address'), "user_added_domain", $short_codes, true);

            showToastr(translate('The domain has been added successfully', 'alerts'));

            return redirect(route('domains.index'));
        } else {
            showToastr(translate('You do not have access,Please upgrade your account', 'alerts'), 'error');
            return back();
        }
    }


    public function destroy(Domain $domain)
    {
        $user = Auth::user();

        $d = Domain::where('id', $domain->id)->where('user_id', $user->id)->first();

        if ($d == null) {
            return redirect(route('domains.index'));
        }


        $d->delete();
        $user->subscription('main')->reduceFeatureUsage('domains', 1);
        showToastr(translate('The domain has been deleted successfully', 'alerts'));
        return redirect(route('domains.index'));
    }
}