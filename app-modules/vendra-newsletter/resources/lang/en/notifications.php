<?php

declare(strict_types=1);

return [
    'retry' => [
        'failed' => [
            'error' => [
                'body'  => 'Error: :error',
                'title' => 'Retry failed',
            ],
            'none'     => 'No failed recipients to re-queue',
            'requeued' => 'Re-queued :count failed recipients',
        ],
        'no_post' => [
            'body'  => 'There is no newsletter post to retry.',
            'title' => 'No post found',
        ],
    ],
    'send' => [
        'failed' => [
            'body'  => 'Error: :error',
            'title' => 'Newsletter ":newsletter" sending failed',
        ],
        'success' => 'Newsletter queued for sending',
    ],
];
