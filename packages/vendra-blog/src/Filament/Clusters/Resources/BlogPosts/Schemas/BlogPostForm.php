<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraSupport\Filament\Concerns\InteractsWithTagFields;
use Misaf\VendraSupport\Filament\Concerns\InteractsWithTranslatedFormFields;

use Misaf\VendraSupport\Support\TenantAwareness;

final class BlogPostForm
{
    use InteractsWithTagFields;
    use InteractsWithTranslatedFormFields;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('blog_post_category_id')
                    ->columnSpanFull()
                    ->label(__('vendra-blog::navigation.blog_post_category'))
                    ->native(false)
                    ->preload()
                    ->relationship('blogPostCategory', 'name')
                    ->required()
                    ->searchable(),

                TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get->string('slug', isNullable: true) ?? '') === Str::slug($old ?? '')) {
                            $set('slug', Str::slug($state ?? ''));
                        }
                    })
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('vendra-blog::attributes.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        column: fn(Livewire $livewire): string => 'name->' . self::activeFormLocale($livewire),
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                            ->withoutTrashed(),
                    ),

                TextInput::make('slug')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.slug'))
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('vendra-blog::attributes.slug_helper_text'))
                    ->label(__('vendra-blog::attributes.slug'))
                    ->required()
                    ->unique(
                        column: fn(Livewire $livewire): string => 'slug->' . self::activeFormLocale($livewire),
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                            ->withoutTrashed(),
                    ),

                RichEditor::make('description')
                    ->columnSpanFull()
                    ->label(__('vendra-blog::attributes.description'))
                    ->required()
                    ->json(),

                ...self::tagFields(),

                SpatieMediaLibraryFileUpload::make('image')
                    ->collection(BlogPost::MEDIA_COLLECTION)
                    ->columnSpanFull()
                    ->image()
                    ->label(__('vendra-blog::attributes.image'))
                    ->multiple()
                    ->panelLayout('grid')
                    ->responsiveImages(),

                Toggle::make('status')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.status'))
                    ->columnSpanFull()
                    ->default(false)
                    ->label(__('vendra-blog::attributes.status'))
                    ->onIcon(Heroicon::Bolt)
                    ->required()
                    ->rules([
                        'boolean',
                    ]),
            ]);
    }


}
