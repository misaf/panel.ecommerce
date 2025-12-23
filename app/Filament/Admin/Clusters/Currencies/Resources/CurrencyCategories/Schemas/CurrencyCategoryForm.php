<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\currencies\Resources\CurrencyCategories\Schemas;

use App\Forms\Components\DescriptionTextarea;
use App\Forms\Components\SlugTextInput;
use App\Forms\Components\StatusToggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Misaf\Tenant\Models\Tenant;

final class CurrencyCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get('slug') ?? '') === Str::slug($old ?? '')) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('form.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id)
                                ->withoutTrashed();
                        },
                    ),

                SlugTextInput::make('slug'),
                DescriptionTextarea::make('description'),
                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('currencies/categories')
                    ->columnSpanFull()
                    ->image()
                    ->label(__('form.image'))
                    ->panelLayout('grid'),

                StatusToggle::make('status'),
            ]);
    }
}
