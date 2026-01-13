<?php

return [
    'stripe_account_id' => 'Stripe Account ID',
    'go_to_dashboard' => 'Go to Express Dashboard',
    'connect' => [
        'label' => 'Connect with Stripe',
        'description' => 'Connect your Stripe account to collect payments.',
    ],
    'disconnect' => [
        'label' => 'Disconnect Stripe',
        'confirm' => 'Are you sure you want to disconnect your Stripe account?',
    ],
    'notifications' => [
        'connected' => 'Your Stripe account has been connected.',
        'disconnected' => 'Your Stripe account has been disconnected.',
        'now_active' => 'Your Stripe account is now active.',
    ],
    'withdrawal' => [
        'payout_info' => 'Your payout will be automatically transferred to your Stripe account with ID: :stripe_account_id.',
    ],
];
