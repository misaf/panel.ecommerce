<?php

declare(strict_types=1);

namespace App\Filament\Properties\Schemas;

use App\Models\StorefrontDeployment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

final class StorefrontConfigurationFields
{
    /**
     * @return list<Section>
     */
    public static function make(bool $optional): array
    {
        return [
            Section::make(__('console.storefront_configuration'))
                ->description(__('console.storefront_configuration_description'))
                ->schema([
                    ...($optional ? [
                        Toggle::make('create_storefront')
                            ->label(__('console.create_storefront'))
                            ->helperText(__('console.create_storefront_hint'))
                            ->default(false)
                            ->live()
                            ->columnSpanFull(),
                    ] : []),
                    Grid::make(2)
                        ->schema(self::fields($optional))
                        ->visible(fn(Get $get): bool => ! $optional || true === $get('create_storefront'))
                        ->columnSpanFull(),
                ])
                ->visibleOn('create')
                ->columnSpanFull(),
        ];
    }

    /**
     * @return list<TextInput|Select>
     */
    private static function fields(bool $optional): array
    {
        $required = fn(Get $get): bool => ! $optional || true === $get('create_storefront');
        $themes = collect(Config::array('services.storefront.themes'))
            ->mapWithKeys(fn(mixed $theme): array => is_string($theme)
                ? [$theme => Str::headline($theme)]
                : [])
            ->all();

        return [
            TextInput::make('storefront_slug')
                ->label(__('console.storefront_slug'))
                ->helperText(__('console.storefront_slug_hint'))
                ->required($required)
                ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                ->unique(StorefrontDeployment::class, 'slug')
                ->maxLength(100),
            Select::make('storefront_theme')
                ->label(__('console.storefront_theme'))
                ->options($themes)
                ->default('default')
                ->required($required)
                ->native(false),
            TextInput::make('storefront_name_en')
                ->label(__('console.storefront_name_en'))
                ->required($required)
                ->maxLength(255),
            TextInput::make('storefront_name_fa')
                ->label(__('console.storefront_name_fa'))
                ->required($required)
                ->maxLength(255),
            TextInput::make('storefront_business_type')
                ->label(__('console.storefront_business_type'))
                ->default('Florist')
                ->required($required)
                ->maxLength(100),
            TextInput::make('storefront_price_currency')
                ->label(__('console.storefront_price_currency'))
                ->default('IRR')
                ->required($required)
                ->length(3),
            TextInput::make('storefront_og_image')
                ->label(__('console.storefront_og_image'))
                ->helperText(__('console.storefront_og_image_hint'))
                ->maxLength(2048),
            TextInput::make('storefront_locality')
                ->label(__('console.storefront_locality'))
                ->required($required)
                ->maxLength(255),
            TextInput::make('storefront_country')
                ->label(__('console.storefront_country'))
                ->default('IR')
                ->required($required)
                ->length(2),
            TextInput::make('storefront_mobile_phone')
                ->label(__('console.storefront_mobile_phone'))
                ->required($required)
                ->tel()
                ->maxLength(50),
            TextInput::make('storefront_office_phone')
                ->label(__('console.storefront_office_phone'))
                ->required($required)
                ->tel()
                ->maxLength(50),
            TextInput::make('storefront_contact_email')
                ->label(__('console.storefront_contact_email'))
                ->required($required)
                ->email()
                ->maxLength(255),
            TextInput::make('storefront_hours_open')
                ->label(__('console.storefront_hours_open'))
                ->placeholder('08:00')
                ->required($required)
                ->regex('/^(?:[01]\\d|2[0-3]):[0-5]\\d$/'),
            TextInput::make('storefront_hours_close')
                ->label(__('console.storefront_hours_close'))
                ->placeholder('21:00')
                ->required($required)
                ->regex('/^(?:[01]\\d|2[0-3]):[0-5]\\d$/'),
            TextInput::make('storefront_map_query')
                ->label(__('console.storefront_map_query'))
                ->required($required)
                ->maxLength(500),
            TextInput::make('storefront_whatsapp_phone')
                ->label(__('console.storefront_whatsapp_phone'))
                ->required($required)
                ->tel()
                ->maxLength(50),
            TextInput::make('storefront_telegram_username')
                ->label(__('console.storefront_telegram_username'))
                ->required($required)
                ->maxLength(100),
            TextInput::make('storefront_instagram_username')
                ->label(__('console.storefront_instagram_username'))
                ->required($required)
                ->maxLength(100),
        ];
    }
}
