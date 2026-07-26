<?php

declare(strict_types=1);

namespace App\Filament\Reseller\Resources\Properties\Actions;

use App\Filament\Actions\ReplaceDomainAction as BaseReplaceDomainAction;
use App\Filament\Reseller\Resources\Properties\PropertyResource;
use Closure;

final class ReplaceDomainAction extends BaseReplaceDomainAction
{
    /**
     * @return Closure(): bool
     */
    protected function authorizationCallback(): Closure
    {
        return fn(): bool => PropertyResource::canCreate();
    }
}
