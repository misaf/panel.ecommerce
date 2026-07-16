<?php

declare(strict_types=1);

it('keeps address translations complete and sorted', function (): void {
    expect(__DIR__ . '/../../resources/lang')
        ->toHaveAtLeastTwoLocales('vendra-address')
        ->toHaveTranslationsInSync('vendra-address')
        ->toHaveSortedTranslationKeys('vendra-address');
});
