<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Batch Processing Settings
    |--------------------------------------------------------------------------
    |
    | Settings for batch processing of newsletter emails.
    |
    */

    'batch_chunk_size' => (int) env('NEWSLETTER_BATCH_CHUNK_SIZE', 100),

    /*
    |--------------------------------------------------------------------------
    | Queue Settings
    |--------------------------------------------------------------------------
    |
    | Queue configuration for newsletter jobs.
    |
    */

    'queue' => [
        'name'          => env('NEWSLETTER_QUEUE_NAME', 'marketing-email'),
        'tries'         => (int) env('NEWSLETTER_QUEUE_TRIES', 3),
        'timeout'       => (int) env('NEWSLETTER_QUEUE_TIMEOUT', 300),
        'email_timeout' => (int) env('NEWSLETTER_EMAIL_QUEUE_TIMEOUT', 30),
    ],

];
