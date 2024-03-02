<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::middleware(['localeSessionRedirect'])
    ->prefix(LaravelLocalization::setLocale())
    ->group(function (): void {
        Livewire::setUpdateRoute(fn($handle) => Route::post('/livewire/update', $handle));
    });
