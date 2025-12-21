<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\Tenant\Models\Tenant;

final class NewsletterForm
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
                    ->label(__('newsletter::attributes.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        column: fn(Livewire $livewire) => 'name->' . $livewire->activeLocale,
                        modifyRuleUsing: fn(Unique $rule) => $rule->where('tenant_id', Tenant::current()->id)->withoutTrashed(),
                    ),

                TextInput::make('slug')
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('newsletter::attributes.slug_helper_text'))
                    ->label(__('newsletter::attributes.slug'))
                    ->required()
                    ->unique(
                        column: fn(Livewire $livewire) => 'slug->' . $livewire->activeLocale,
                        modifyRuleUsing: fn(Unique $rule) => $rule->where('tenant_id', Tenant::current()->id)->withoutTrashed(),
                    ),

                RichEditor::make('description')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly("data.description"))
                    ->columnSpanFull()
                    ->helperText(__('newsletter::attributes.description_helper_text'))
                    ->label(__('newsletter::attributes.description')),

                DateTimePicker::make('scheduled_at')
                    ->afterOrEqual(Carbon::now()->addMinutes(30)->toDateTimeString())
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly("data.scheduled_at"))
                    ->columnSpanFull()
                    ->displayFormat('Y-m-d H:i')
                    ->helperText(__('newsletter::attributes.scheduled_at_helper_text'))
                    ->label(__('newsletter::attributes.scheduled_at'))
                    ->live(onBlur: true)
                    ->minDate(Carbon::now()->toDateString())
                    ->minutesStep(30)
                    ->native(false)
                    ->seconds(false)
                    ->unless(app()->isLocale('fa'), fn(DateTimePicker $column) => $column->jalali()),

                Toggle::make('status')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly("data.status"))
                    ->columnSpanFull()
                    ->default(false)
                    ->label(__('newsletter::attributes.status'))
                    ->onIcon('heroicon-m-bolt')
                    ->required()
                    ->rules([
                        'boolean',
                    ]),
            ]);
    }
}
