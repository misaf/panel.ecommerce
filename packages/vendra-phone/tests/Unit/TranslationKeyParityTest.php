<?php

declare(strict_types=1);

it('keeps phone translations complete and sorted', function (): void {
    expect(__DIR__ . '/../../resources/lang')
        ->toHaveAtLeastTwoLocales('vendra-phone')
        ->toHaveTranslationsInSync('vendra-phone')
        ->toHaveSortedTranslationKeys('vendra-phone');
});
