<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Misaf\VendraNewsletter\Http\Controllers\NewsletterUnsubscribeController;
use Misaf\VendraNewsletter\Http\Controllers\NewsletterUnsubscribeSpecificController;

Route::get('/unsubscribe', NewsletterUnsubscribeController::class)
    ->name('newsletter.unsubscribe');

Route::get('/unsubscribe/specific', NewsletterUnsubscribeSpecificController::class)
    ->name('newsletter.unsubscribe.specific');
