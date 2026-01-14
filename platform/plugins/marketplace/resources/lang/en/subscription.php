<?php

return [
    'name' => 'Subscriptions',
    'plans' => [
        'name' => 'Subscription Plans',
        'create' => 'New Subscription Plan',
        'edit' => 'Edit Plan :name',
        'duration' => 'Duration',
        'price' => 'Price',
        'priority_boost' => 'Priority Boost',
        'verified_eligible' => 'Verification Eligible',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'cannot_delete_active' => 'Cannot delete plan with active subscriptions',
    ],
    'subscriptions' => [
        'name' => 'Vendor Subscriptions',
        'vendor' => 'Vendor',
        'store' => 'Store',
        'plan' => 'Plan',
        'starts_at' => 'Starts At',
        'expires_at' => 'Expires At',
        'view' => 'View Subscription #:id',
        'cancel' => 'Cancel',
        'cancelled_success' => 'Subscription cancelled successfully',
    ],
    'no_store' => 'You must have a store to subscribe',
    'plan_not_available' => 'This plan is not available',
    'already_subscribed' => 'You already have an active subscription',
    'subscribed_success' => 'Subscription activated successfully',
    'renewed_success' => 'Subscription renewed successfully',
];
