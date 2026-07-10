<?php

declare(strict_types=1);

return [
    'email' => [
        'example'     => 'valid-email@example.com',
        'helper_text' => 'Geben Sie eine gültige E-Mail-Adresse ein (z. B. user@domain.com).',
    ],
    'newsletter_slug' => [
        'example'     => 'weekly-newsletter,monthly-update',
        'helper_text' => 'Geben Sie die Newsletter-Slugs durch Kommas getrennt ein.',
    ],
    'notification' => [
        'completed_body' => 'Ihr Import von Newsletter-Abonnenten wurde abgeschlossen und :successful_count :successful_rows wurden importiert.',
        'failed_rows'    => ':failed_count :failed_rows konnten nicht importiert werden.',
    ],
    'options' => [
        'enable_real_email_validation' => [
            'helper_text' => 'Validieren Sie E-Mail-Adressen gegen echte E-Mail-Server, um sicherzustellen, dass sie existieren.',
            'label'       => 'Echte E-Mail-Validierung aktivieren',
        ],
        'skip_duplicate_users' => [
            'helper_text' => 'Überspringen Sie den Import von Datensätzen, bei denen der Benutzer bereits abonniert ist, um Duplikate zu vermeiden.',
            'label'       => 'Doppelte Benutzer überspringen',
        ],
        'update_existing_records' => [
            'helper_text' => 'Aktualisieren Sie vorhandene Abonnenten, anstatt neue zu erstellen, wenn Duplikate gefunden werden.',
            'label'       => 'Vorhandene Datensätze aktualisieren',
        ],
    ],
    'user' => [
        'example'     => 'john_doe',
        'helper_text' => 'Geben Sie den Benutzernamen ein, der diesem Abonnenten zugeordnet werden soll.',
    ],
];
