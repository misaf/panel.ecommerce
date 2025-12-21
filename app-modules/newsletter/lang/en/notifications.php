<?php

declare(strict_types=1);

return [
    'send' => [
        'success' => 'Newsletter queued for sending',
        'failed'  => [
            'title' => 'Newsletter ":newsletter" sending failed',
            'body'  => 'Error: :error',
        ],
    ],
    'retry' => [
        'no_post' => [
            'title' => 'No post found',
            'body'  => 'There is no newsletter post to retry.',
        ],
        'failed' => [
            'none'     => 'No failed recipients to re-queue',
            'requeued' => 'Re-queued :count failed recipients',
            'error'    => [
                'title' => 'Retry failed',
                'body'  => 'Error: :error',
            ],
        ],
    ],
];
