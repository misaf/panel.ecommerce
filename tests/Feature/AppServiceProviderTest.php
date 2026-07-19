<?php

declare(strict_types=1);

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;

it('uses Jalali date and date-time pickers only for the Persian locale', function (): void {
    $originalLocale = app()->getLocale();

    try {
        app()->setLocale('fa');

        $persianDatePicker = DatePicker::make('persian_date');
        $persianDateTimePicker = DateTimePicker::make('persian_date_time');

        app()->setLocale('en');

        $englishDatePicker = DatePicker::make('english_date');
        $englishDateTimePicker = DateTimePicker::make('english_date_time');

        expect($persianDatePicker->getView())->toBe('filament-jalali::jalali-date-time-picker')
            ->and($persianDateTimePicker->getView())->toBe('filament-jalali::jalali-date-time-picker')
            ->and($persianDatePicker->getViewData())->toHaveKey('defaultFocusedDate')
            ->and($persianDateTimePicker->getViewData())->toHaveKey('defaultFocusedDate')
            ->and($englishDatePicker->hasView())->toBeFalse()
            ->and($englishDateTimePicker->hasView())->toBeFalse();
    } finally {
        app()->setLocale($originalLocale);
    }
});
