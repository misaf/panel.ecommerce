<?php

declare(strict_types=1);

return [
    'description' => [
        'transactions'         => 'Alle finanziellen Transaktionen anzeigen und bearbeiten.',
        'wallets'              => 'Guthaben und Einstellungen von Benutzer-Wallets verwalten.',
        'transaction_gateways' => 'Zahlungsgateway-Anbieter konfigurieren.',
    ],

    'empty_state' => [
        'heading' => [
            'transactions'         => 'Noch keine Transaktionen',
            'wallets'              => 'Noch keine Wallets',
            'transaction_gateways' => 'Noch keine Zahlungsgateways',
        ],

        'description' => [
            'transactions'         => 'Transaktionen erscheinen, wenn Benutzer Einzahlungen, Abhebungen oder Überweisungen tätigen.',
            'wallets'              => 'Wallets werden automatisch erstellt, wenn Benutzer angelegt werden.',
            'transaction_gateways' => 'Fügen Sie ein Zahlungsgateway hinzu, um finanzielle Transaktionen zu ermöglichen.',
        ],
    ],
];
