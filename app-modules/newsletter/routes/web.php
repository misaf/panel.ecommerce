<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Misaf\Newsletter\Http\Controllers\NewsletterUnsubscribeController;
use Misaf\Newsletter\Http\Controllers\NewsletterUnsubscribeSpecificController;

Route::get('/unsubscribe', NewsletterUnsubscribeController::class)
    ->name('newsletter.unsubscribe');

Route::get('/unsubscribe/specific', NewsletterUnsubscribeSpecificController::class)
    ->name('newsletter.unsubscribe.specific');
