<?php

declare(strict_types=1);

return [
    /*
     * The Filament panel ids the affiliate plugin registers on.
     */
    'panels' => ['admin'],

    /*
     * The navigation group translation key used for the admin cluster.
     */
    'navigation_group' => 'vendra-affiliate::navigation.marketing',

    /*
     * Defaults applied to newly created affiliates. Amounts are integer
     * minor units (e.g. cents).
     */
    'defaults' => [
        'commission_percent' => 20,
        'signup_bounty'      => 0,
    ],

    /*
     * The conversion types that credit commissions. Each can be toggled
     * independently.
     */
    'conversions' => [
        'deposit'  => ['enabled' => true],
        'signup'   => ['enabled' => false],
        'checkout' => ['enabled' => false],
    ],

    /*
     * When enabled, credited commissions are immediately payable; otherwise
     * they stay pending until approved in the admin panel.
     */
    'commissions' => [
        'auto_approve' => true,
    ],

    /*
     * Referral attribution via the click-redirect cookie.
     */
    'attribution' => [
        'cookie_name'     => 'vendra_affiliate_ref',
        'cookie_ttl_days' => 30,
        'redirect_url'    => '/',
    ],

    /*
     * Payout settlement. `minimum` is the smallest payable balance in minor
     * units; the gateway slug must exist in vendra-transaction.
     */
    'payout' => [
        'minimum'             => 1000,
        'transaction_gateway' => 'internal-transactions',
    ],
];
