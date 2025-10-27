<?php

declare(strict_types=1);

return [
    'main_subscription_tag' => 'main',
    'fallback_plan_tag' => null,
    // Database Tables
    'tables' => [
        'plans' => 'plans',
        'plan_features' => 'plan_features',
        'plan_subscriptions' => 'plan_subscriptions',
        'plan_subscription_usage' => 'plan_subscription_usage',
    ],

    // Models
    'models' => [
        'plan' => \Lobage\Planify\Models\Plan::class,
        'plan_feature' => \Lobage\Planify\Models\PlanFeature::class,
        'plan_subscription' => \Lobage\Planify\Models\PlanSubscription::class,
        'plan_subscription_usage' => \Lobage\Planify\Models\PlanSubscriptionUsage::class,
    ],

];