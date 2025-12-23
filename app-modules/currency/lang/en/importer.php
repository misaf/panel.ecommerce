<?php

declare(strict_types=1);

return [
    'newsletter_slug' => [
        'helper_text'    => 'Enter the newsletter slug(s) separated by commas.',
        'example'        => 'weekly-newsletter,monthly-update',
    ],
    'user' => [
        'helper_text'    => 'Enter the username to associate with this subscriber.',
        'example'        => 'john_doe',
    ],
    'email' => [
        'helper_text'    => 'Enter a valid email address (e.g., user@domain.com).',
        'example'        => 'valid-email@example.com',
    ],
    'options' => [
        'enable_real_email_validation' => [
            'label'       => 'Enable Real Email Validation',
            'helper_text' => 'Validate email addresses against real email servers to ensure they exist.',
        ],
        'update_existing_records' => [
            'label'       => 'Update Existing Records',
            'helper_text' => 'Update existing subscribers instead of creating new ones when duplicates are found.',
        ],
        'skip_duplicate_users' => [
            'label'       => 'Skip Duplicate Users',
            'helper_text' => 'Skip importing records where the user is already subscribed to avoid duplicates.',
        ],
    ],
    'notification' => [
        'completed_body' => 'Your newsletter subscriber import has completed and :successful_count :successful_rows imported.',
        'failed_rows'    => ':failed_count :failed_rows failed to import.',
    ],
];
