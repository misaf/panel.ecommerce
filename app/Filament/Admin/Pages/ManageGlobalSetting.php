<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Clusters\Setting;
use App\Settings\GlobalSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

final class ManageGlobalSetting extends SettingsPage
{
    protected static ?string $cluster = Setting::class;

    protected static ?int $navigationSort = 4;

    protected static string $settings = GlobalSetting::class;

    protected static ?string $slug = 'configurations';

    public static function getModelLabel(): string
    {
        return __('page.configuration');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('page.setting');
    }

    public static function getNavigationLabel(): string
    {
        return __('page.configuration');
    }

    public static function getpluralModelLabel(): string
    {
        return __('page.configuration');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('global_settings')
                            ->translateLabel(true)
                            ->label('setting.general')
                            ->schema([
                                Forms\Components\TextInput::make('site_title')
                                    ->columnSpanFull()
                                    ->label(__('form.title'))
                                    ->required()
                                    ->rules('required'),

                                Forms\Components\Textarea::make('site_description')
                                    ->columnSpanFull()
                                    ->label(__('form.description'))
                                    ->rows(5)
                                    ->rules('required'),

                                Forms\Components\Textarea::make('site_tags')
                                    ->columnSpanFull()
                                    ->label(__('form.tags'))
                                    ->rows(5)
                                    ->rules('required'),

                                Forms\Components\FileUpload::make('site_sidebar_logo_light')
                                    ->columnSpanFull()
                                    ->directory('logos')
                                    ->image()
                                    ->label(__('form.site_sidebar_logo_light'))
                                    ->orientImagesFromExif(false)
                                    ->visibility('private'),

                                Forms\Components\FileUpload::make('site_sidebar_logo_dark')
                                    ->columnSpanFull()
                                    ->directory('logos')
                                    ->image()
                                    ->label(__('form.site_sidebar_logo_dark'))
                                    ->orientImagesFromExif(false)
                                    ->visibility('private'),

                                Forms\Components\Toggle::make('site_status')
                                    ->columnSpanFull()
                                    ->inline(false)
                                    ->label(__('form.status'))
                                    ->rules('required'),
                            ]),

                        Forms\Components\Tabs\Tab::make('global_authentication')
                            ->translateLabel(true)
                            ->label('setting.authentication')
                            ->schema([
                                Forms\Components\Toggle::make('user_email_verification')
                                    ->columnSpanFull()
                                    ->inline(false)
                                    ->label(__('auth.email_verification'))
                                    ->rules('required'),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public function getTitle(): string | Htmlable
    {
        return __('page.configuration');
    }
}
