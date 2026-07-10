<?php

declare(strict_types=1);

return [
    'email' => [
        'example'     => 'valid-email@example.com',
        'helper_text' => 'Enter a valid email address (e.g., user@domain.com).',
    ],
    'newsletter_slug' => [
        'example'     => 'weekly-newsletter,monthly-update',
        'helper_text' => 'Enter the newsletter slug(s) separated by commas.',
    ],
    'notification' => [
        'completed_body' => 'Your newsletter subscriber import has completed and :successful_count :successful_rows imported.',
        'failed_rows'    => ':failed_count :failed_rows failed to import.',
    ],
    'options' => [
        'enable_real_email_validation' => [
            'helper_text' => 'Validate email addresses against real email servers to ensure they exist.',
            'label'       => 'Enable Real Email Validation',
        ],
        'skip_duplicate_users' => [
            'helper_text' => 'Skip importing records where the user is already subscribed to avoid duplicates.',
            'label'       => 'Skip Duplicate Users',
        ],
        'update_existing_records' => [
            'helper_text' => 'Update existing subscribers instead of creating new ones when duplicates are found.',
            'label'       => 'Update Existing Records',
        ],
    ],
    'user' => [
        'example'     => 'john_doe',
        'helper_text' => 'Enter the username to associate with this subscriber.',
    ],
];
