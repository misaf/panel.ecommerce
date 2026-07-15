<?php

declare(strict_types=1);

use Misaf\VendraNewsletter\Enums\NewsletterStatusEnum;

it('has at least two available locales', function (): void {
    expect(__DIR__ . '/../../resources/lang')->toHaveAtLeastTwoLocales('vendra-newsletter');
});

it('keeps translation files and keys in sync across locales', function (): void {
    expect(__DIR__ . '/../../resources/lang')->toHaveTranslationsInSync('vendra-newsletter');
});

it('keeps translation file keys sorted', function (): void {
    expect(__DIR__ . '/../../resources/lang')->toHaveSortedTranslationKeys('vendra-newsletter');
});

it('translates enum labels from the shared enum locale file', function (): void {
    app()->setLocale('en');

    expect(NewsletterStatusEnum::Draft->getLabel())->toBe('Draft')
        ->and(NewsletterStatusEnum::Scheduled->getLabel())->toBe('Scheduled')
        ->and(NewsletterStatusEnum::Sent->getLabel())->toBe('Sent');
});
