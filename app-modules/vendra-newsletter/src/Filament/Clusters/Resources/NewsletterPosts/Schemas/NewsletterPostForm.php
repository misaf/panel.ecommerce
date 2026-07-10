<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterPosts\Schemas;

use App\Forms\Components\WysiwygEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\VendraNewsletter\Enums\NewsletterPostStatusEnum;

final class NewsletterPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('newsletter_id')
                    ->columnSpanFull()
                    ->label(__('vendra-newsletter::navigation.newsletter'))
                    ->native(false)
                    ->preload()
                    ->relationship('newsletter', 'name')
                    ->required()
                    ->searchable(),

                TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get->string('slug', isNullable: true) ?? '') === Str::slug($old ?? '')) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('vendra-newsletter::attributes.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        column: fn(Livewire $livewire) => 'name->' . $livewire->activeLocale,
                        modifyRuleUsing: function (Unique $rule, Get $get): void {
                            $rule->withoutTrashed();

                            $newsletterId = $get->integer('newsletter_id', isNullable: true);

                            if (null !== $newsletterId) {
                                $rule->where('newsletter_id', $newsletterId);
                            }
                        },
                    ),

                TextInput::make('slug')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.slug'))
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('vendra-newsletter::attributes.slug_helper_text'))
                    ->label(__('vendra-newsletter::attributes.slug'))
                    ->required()
                    ->unique(modifyRuleUsing: fn(Unique $rule) => $rule->withoutTrashed()),

                WysiwygEditor::make('description')
                    ->label(__('vendra-newsletter::attributes.description'))
                    ->required(),

                Select::make('status')
                    ->columnSpanFull()
                    ->label(__('vendra-newsletter::attributes.status'))
                    ->live(onBlur: true)
                    ->native(false)
                    ->options([
                        NewsletterPostStatusEnum::DRAFT->value => __('vendra-newsletter::newsletter_post_status_enum.draft'),
                        NewsletterPostStatusEnum::READY->value => __('vendra-newsletter::newsletter_post_status_enum.ready'),
                    ])
                    ->required(),
            ]);
    }
}
