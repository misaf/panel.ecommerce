<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Misaf\VendraAffiliate\Http\Controllers\AffiliateRedirectController;

Route::middleware('web')
    ->get('/r/{code}', AffiliateRedirectController::class)
    ->name('affiliate.redirect');
