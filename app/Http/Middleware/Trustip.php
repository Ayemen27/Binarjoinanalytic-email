<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Trustip as ProxyCheck;

class Trustip
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (isPluginEnabled('trustip')) {
            try {
                $trustip = ProxyCheck::check(get_user_ip());

                if ($trustip->status == "error") {
                    throw new Exception($trustip['message']);
                }

                if ($trustip->status == "success" && $trustip->data->is_proxy == true) {
                    showToastr(translate('We could not complete the process, please try again letter', 'alerts'), 'error');
                    return back();
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        return $next($request);
    }
}