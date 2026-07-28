<?php

declare(strict_types=1);

use Misaf\VendraNewsletter\Enums\NewsletterStatusEnum;

it('translates enum labels from the shared enum locale file', function (): void {
    app()->setLocale('en');

    expect(NewsletterStatusEnum::Draft->getLabel())->toBe('Draft')
        ->and(NewsletterStatusEnum::Scheduled->getLabel())->toBe('Scheduled')
        ->and(NewsletterStatusEnum::Sent->getLabel())->toBe('Sent');
});
