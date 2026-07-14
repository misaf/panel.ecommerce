<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Misaf\VendraNewsletter\Http\Controllers\NewsletterUnsubscribeController;

Route::middleware('web')
    ->get('/newsletter/unsubscribe/{token}', NewsletterUnsubscribeController::class)
    ->name('vendra-newsletter.unsubscribe');
