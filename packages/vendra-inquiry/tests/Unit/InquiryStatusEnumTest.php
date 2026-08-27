<?php

declare(strict_types=1);

use Misaf\VendraInquiry\Enums\InquiryStatusEnum;

it('exposes every status value', function (): void {
    expect(InquiryStatusEnum::values())->toBe(['new', 'answered', 'closed']);
});

it('gives every status a colour and an icon', function (InquiryStatusEnum $status): void {
    expect($status->getColor())->toBeArray()->not->toBeEmpty()
        ->and($status->getIcon())->not->toBeNull();
})->with(InquiryStatusEnum::cases());
