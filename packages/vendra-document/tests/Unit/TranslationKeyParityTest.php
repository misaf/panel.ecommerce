<?php

declare(strict_types=1);

it('keeps document translations complete and sorted', function (): void {
    expect(__DIR__ . '/../../resources/lang')
        ->toHaveAtLeastTwoLocales('vendra-document')
        ->toHaveTranslationsInSync('vendra-document')
        ->toHaveSortedTranslationKeys('vendra-document');
});
