<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterPosts\Schemas;

use App\Forms\Components\WysiwygEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\Newsletter\Enums\NewsletterPostStatusEnum;

final class NewsletterPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('newsletter_id')
                    ->columnSpanFull()
                    ->label(__('newsletter::navigation.newsletter'))
                    ->native(false)
                    ->preload()
                    ->relationship('newsletter', 'name')
                    ->required()
                    ->searchable(),

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
                        modifyRuleUsing: function (Unique $rule, Get $get): void {
                            $rule->where('newsletter_id', $get('newsletter_id'))
                                ->withoutTrashed();
                        },
                    ),

                TextInput::make('slug')
                    ->afterStateUpdated(function (Livewire $livewire): void {
                        $livewire->validateOnly("data.slug");
                    })
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('شناسه یکتای URL, نیازی به وارد کردن این قسمت نمی باشد به صورت خودکار بعد از وارد کردن فیلد نام پر می گردد.'))
                    ->label(__('form.slug'))
                    ->required()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->withoutTrashed();
                        },
                    )
                    ->label(__('newsletter::attributes.slug')),

                WysiwygEditor::make('description')
                    ->label(__('newsletter::attributes.description'))
                    ->required(),

                Select::make('status')
                    ->columnSpanFull()
                    ->label(__('newsletter::attributes.status'))
                    ->live(onBlur: true)
                    ->native(false)
                    ->options([
                        NewsletterPostStatusEnum::DRAFT->value => __('newsletter::newsletter_post_status_enum.draft'),
                        NewsletterPostStatusEnum::READY->value => __('newsletter::newsletter_post_status_enum.ready'),
                    ])
                    ->required(),
            ]);
    }
}
