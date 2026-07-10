<?php

declare(strict_types=1);

return [
    'retry' => [
        'failed' => [
            'error' => [
                'body'  => 'Fehler: :error',
                'title' => 'Wiederholung fehlgeschlagen',
            ],
            'none'     => 'Keine fehlgeschlagenen Empfänger zum erneuten Einreihen',
            'requeued' => ':count fehlgeschlagene Empfänger erneut eingereiht',
        ],
        'no_post' => [
            'body'  => 'Es gibt keinen Newsletter-Beitrag, der erneut versucht werden kann.',
            'title' => 'Kein Beitrag gefunden',
        ],
    ],
    'send' => [
        'failed' => [
            'body'  => 'Fehler: :error',
            'title' => 'Senden des Newsletters ":newsletter" fehlgeschlagen',
        ],
        'success' => 'Newsletter zum Senden in die Warteschlange gestellt',
    ],
];
