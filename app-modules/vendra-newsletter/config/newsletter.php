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

    'batch_chunk_size' => env('NEWSLETTER_BATCH_CHUNK_SIZE', 100),

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
        'tries'         => env('NEWSLETTER_QUEUE_TRIES', 3),
        'timeout'       => env('NEWSLETTER_QUEUE_TIMEOUT', 300),
        'email_timeout' => env('NEWSLETTER_EMAIL_QUEUE_TIMEOUT', 30),
    ],

];
