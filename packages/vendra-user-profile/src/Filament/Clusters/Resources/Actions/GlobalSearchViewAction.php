<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Filament\Clusters\Resources\Actions;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

final class GlobalSearchViewAction
{
    public static function make(Model $record): Action
    {
        return Action::make('view')
            ->url(\Misaf\VendraUserProfile\Filament\Clusters\Resources\UserProfileResource::getUrl('view', ['record' => $record]));
    }
}
