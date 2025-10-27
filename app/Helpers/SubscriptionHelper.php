<?php


use App\Models\Tax;
use Lobage\Planify\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Lobage\Planify\Models\PlanFeature;


if (!function_exists('formatCurrency')) {
    function formatCurrency($amount, $currencySymbol = '$', $position = 'left', $number_format = 0)
    {
        if ($position === 'left') {
            return $currencySymbol . number_format($amount, $number_format);
        } elseif ($position === 'right') {
            return number_format($amount, $number_format) . $currencySymbol;
        }
    }
}


if (!function_exists('getTax')) {
    function getTax()
    {
        try {
            $country =  get_user_location()['country'];
        } catch (\Throwable $th) {
            $country = "all";
        }

        $tax = Tax::getTaxesByCountry($country);
        if ($tax === null) {
            $tax = Tax::getTaxesByCountry("all");
            if ($tax === null) {
                return 0;
            }
        }
        return $tax;
    }
}


function calculateTax($amount, $taxRate)
{
    // Calculate the tax amount
    $taxAmount = $amount * ($taxRate / 100);

    // Round the tax amount to two decimal places
    $taxAmount = round($taxAmount, 2);

    return $taxAmount;
}


function total($amount, $taxAmount = 0, $discount = 0)
{
    // Calculate total amount after tax and discount
    $totalAmount = $amount + $taxAmount - $discount;

    return number_format($totalAmount, 2);
}


function Upgrade_btn($feature)
{

    if (Auth::user()) {
        $user = Auth::user();
        if ($user->subscriptions->count() != 0 && $user->subscription('main')->isActive()) {
            $subscription =  $user->subscription('main');

            if ($subscription->plan_id == 1) {
                return '<a href="/plans" class="btn btn-primary btn-sm ms-3">Upgrade</a>';
            }

            $value = $subscription->getFeatureValue($feature);

            if ($value == 'true' || $value == '-1') {
                return false;
            }



            $plans =  Plan::where('invoice_interval', $subscription->invoice_interval)->where('is_active', 1)->orderBy('tier', 'DESC')->pluck('tier')->first();

            if ($plans > $subscription->tier) {
                return '<a href="/plan" class="btn btn-primary btn-sm ms-3">Upgrade</a>';
            }

            return false;
        } else {
            return '<a href="/plan" class="btn btn-primary btn-sm ms-3">Upgrade</a>';
        }
    }

    return '<button class="btn btn-primary btn-sm ms-3">Upgrade</button>';
}

function canUseFeature($feature, $check = true)
{
    if (Auth::user()) {
        $user = Auth::user();
        return $user->subscription('main')->canUseFeature($feature, $check);


        /*
        if ($user->subscriptions->count() != 0 && $user->subscription('main')->isActive()) {
            return $user->subscription('main')->canUseFeature($feature);
        }
        */
    }

    $guest_plan = Plan::where('tag', 'guest')->first();

    $PlanFeature = $guest_plan->getFeatureByTag($feature)->value;


    if ($PlanFeature == null || $PlanFeature === false || $PlanFeature == 'false') {
        return false;
    }

    if ($PlanFeature > 0 || $PlanFeature == true) {
        return true;
    }



    return false;
}


function getFeatureValue($feature, $true_to_text = null)
{
    //return 0;
    if (Auth::user()) {

        $user = Auth::user();
        if ($user->subscriptions->count() != 0 && $user->subscription('main')->isActive()) {

            $value = $user->subscription('main')->getFeatureValue($feature);

            // if ($true_to_text) {
            //     if ($value == 'true' || $value == '-1') {
            //         return 'Unlimited';
            //     }
            // }
            return $value;
        }
    }

    $guest_plan = Plan::where('tag', 'guest')->first();

    $PlanFeature = $guest_plan->getFeatureByTag($feature)->value;

    if ($PlanFeature == null) {
        return 0;
    }

    return  $PlanFeature;

    // if ($PlanFeature->value > 0 || $PlanFeature->value == true) {
    //     if ($true_to_text) {
    //         if ($PlanFeature->value == 'true' || $PlanFeature->value == '-1') {
    //             return 'Unlimited';
    //         }
    //     }
    //     return  $PlanFeature->value;
    // }

    // return 0;
}



function newSubscription($user, $plan)
{

    $count = $user->subscriptions->count();
    $plan_id = null;
    if ($count != 0) {
        $plan_id =  $user->subscription('main')->plan_id;
        if ($plan_id == $plan->id) {
            $user->subscription('main')->renew();
        } else {
            $user->subscription('main')->cancel(true);
            $user->subscription('main')->uncancel()->changePlan($plan)->renew();
        }
    } else {
        $user->newSubscription(
            'main', // identifier tag of the subscription. If your application offers a single subscription, you might call this 'main' or 'primary'
            $plan, // Plan or PlanCombination instance your subscriber is subscribing to
            'Main subscription', // Human-readable name for your subscription
            'Customer main subscription', // Description
            null, // Start date for the subscription, defaults to now()
            'free' // Payment method service defined in config
        );
    }
}
