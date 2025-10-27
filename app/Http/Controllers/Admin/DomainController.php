<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Domain;
use App\Http\Controllers\Controller;
use App\Services\DomainFilterService;
use App\Http\Requests\Admin\StoreDomainRequest;
use App\Http\Requests\Admin\UpdateDomainRequest;
use App\Http\Requests\Filter\DomainFilterRequest;



class DomainController extends Controller
{

    protected $filterService;

    public function __construct(DomainFilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function index(DomainFilterRequest $request)
    {

        $all_domains = Domain::all()->count();
        $free_domains = Domain::where('type', 0)->get()->count();
        $premium_domains = Domain::where('type', 1)->get()->count();
        $custom_domains = Domain::where('type', 2)->get()->count();



        $domains = $this->filterService->filterDomains($request);

        return view('admin.domains.index', compact('domains', 'all_domains', 'free_domains', 'premium_domains', 'custom_domains'));
    }

    public function create()
    {
        return view('admin.domains.create')->with('users', User::all());
    }


    public function store(StoreDomainRequest $request)
    {

        if ($request->type == 2) {
            $user = User::where('id', $request->user_id)->first();

            if ($user->subscription('main')->canUseFeature('domains')) {
                $user->subscription('main')->recordFeatureUsage('domains', 1);
            } else {
                showToastr(__('This user has reached the maximum limit.'), 'error');
                return back();
            }
        }

        $domain = new Domain($request->validated());
        $domain->status = 1;
        $domain->save();



        showToastr(__('lobage.toastr.success'));
        return redirect(route('admin.domains.index'));
    }


    public function edit(Domain $domain)
    {
        return view('admin.domains.edit')->with('domain', $domain)->with('users', User::all());
    }


    public function update(UpdateDomainRequest $request, Domain $domain)
    {
        $originalUserId = $domain->user_id;

        if ($request->type != 2) {
            $request->user_id = NULL;
        }

        if ($originalUserId != $request->user_id) {

            $user = User::where('id', $request->user_id)->first();


            if ($user) {
                if ($user->subscription('main')->canUseFeature('domains')) {
                    $user->subscription('main')->recordFeatureUsage('domains', 1);
                } else {
                    showToastr(__('This user has reached the maximum limit.'), 'error');
                    return back();
                }
            }

            $user = User::where('id', $originalUserId)->first();

            if ($user) {
                $user->subscription('main')->reduceFeatureUsage('domains', 1);
            }
        }


        $originalStatus = $domain->status;
        $domain->update($request->validated());

        if ($request->type != 2) {
            $domain->update([$domain->user_id = NULL]);
        }
        // Check if the user_id was updated

        if ($originalStatus != $domain->status && $domain->type == 2) {

            $user = User::where('id', $domain->user_id)->first();

            $short_codes = [
                'user_fullname'   => $user->getFullName(),
                'domain_name'     => $domain->domain,
                'domain_url'      => "https://" . $domain->domain,
                'website_name'    => config('app.name'),
                'website_url'     => config('app.url'),
            ];

            if ($domain->status == 1) {
                sendEmail($user->email, "domain_accepted", $short_codes, true);
            }
            if ($domain->status == 2) {
                sendEmail($user->email, "domain_rejected", $short_codes, true);
            }
        }

        showToastr(__('lobage.toastr.update'));
        return redirect(route('admin.domains.index'));
    }


    public function destroy(Domain $domain)
    {

        if ($domain->type == 2) {
            $user = User::where('id', $domain->user_id)->first();
            $user->subscription('main')->reduceFeatureUsage('domains');
        }

        $domain->delete();
        showToastr(__('lobage.toastr.delete'));
        return back();
    }
}
