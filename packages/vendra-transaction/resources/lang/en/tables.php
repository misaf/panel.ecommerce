<?php

declare(strict_types=1);

return [
    'description' => [
        'transactions'         => 'View and process all financial transactions.',
        'wallets'              => 'Manage user wallet balances and settings.',
        'transaction_gateways' => 'Configure payment gateway providers.',
    ],

    'empty_state' => [
        'heading' => [
            'transactions'         => 'No transactions yet',
            'wallets'              => 'No wallets yet',
            'transaction_gateways' => 'No transaction gateways yet',
        ],

        'description' => [
            'transactions'         => 'Transactions appear when users make deposits, withdrawals, or transfers.',
            'wallets'              => 'Wallets are created automatically when users are created.',
            'transaction_gateways' => 'Add a payment gateway to enable financial transactions.',
        ],
    ],
];
