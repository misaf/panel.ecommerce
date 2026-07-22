<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Plans;

use App\Filament\Console\Resources\Plans\Pages\CreatePlan;
use App\Filament\Console\Resources\Plans\Pages\EditPlan;
use App\Filament\Console\Resources\Plans\Pages\ListPlans;
use App\Filament\Console\Resources\Plans\Schemas\PlanForm;
use App\Filament\Console\Resources\Plans\Tables\PlanTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Misaf\VendraSubscription\Models\Plan;

final class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $slug = 'plans';

    public static function getModelLabel(): string
    {
        return __('console.plan');
    }

    public static function getPluralModelLabel(): string
    {
        return __('console.plans');
    }

    public static function getNavigationLabel(): string
    {
        return __('console.plans');
    }

    public static function getNavigationGroup(): string
    {
        return __('console.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return PlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlanTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPlans::route('/'),
            'create' => CreatePlan::route('/create'),
            'edit'   => EditPlan::route('/{record}/edit'),
        ];
    }
}
