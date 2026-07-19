<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraSupport\Support\TagIntegration;

final class BlogPostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $components = [
            TextEntry::make('blogPostCategory.name')
                ->label(__('vendra-blog::navigation.blog_post_category')),

            TextEntry::make('name')
                ->label(__('vendra-blog::attributes.name')),

            TextEntry::make('slug')
                ->label(__('vendra-blog::attributes.slug')),

            IconEntry::make('status')
                ->boolean()
                ->label(__('vendra-blog::attributes.status')),

            TextEntry::make('description')
                ->columnSpanFull()
                ->formatStateUsing(fn(array|string|null $state): RichContentRenderer => self::renderRichContent($state))
                ->html()
                ->label(__('vendra-blog::attributes.description')),

            SpatieMediaLibraryImageEntry::make('image')
                ->collection(BlogPost::MEDIA_COLLECTION)
                ->columnSpanFull()
                ->label(__('vendra-blog::attributes.image')),

            self::dateEntry('created_at'),
            self::dateEntry('updated_at'),
        ];

        if (TagIntegration::isAvailable()) {
            $components[] = SpatieTagsEntry::make('tags')
                ->columnSpanFull()
                ->label(__('vendra-support::attributes.tags'))
                ->type(BlogPost::TAG_TYPE);
        }

        return $schema
            ->components($components)
            ->columns(2);
    }

    private static function dateEntry(string $name): TextEntry
    {
        return TextEntry::make($name)
            ->label(__("vendra-blog::attributes.{$name}"))
            ->when(
                app()->isLocale('fa'),
                fn(TextEntry $entry): TextEntry => $entry->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                fn(TextEntry $entry): TextEntry => $entry->dateTime('Y-m-d H:i'),
            );
    }

    /** @param array<array-key, mixed>|string|null $state */
    private static function renderRichContent(array|string|null $state): RichContentRenderer
    {
        if ( ! is_array($state)) {
            return RichContentRenderer::make($state);
        }

        $content = [];

        foreach ($state as $key => $value) {
            $content[(string) $key] = $value;
        }

        return RichContentRenderer::make($content);
    }
}
