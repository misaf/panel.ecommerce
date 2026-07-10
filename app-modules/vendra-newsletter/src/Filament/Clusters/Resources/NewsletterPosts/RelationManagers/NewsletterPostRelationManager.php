<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterPosts\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use LaraZeus\SpatieTranslatable\Resources\RelationManagers\Concerns\Translatable;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterPosts\Schemas\NewsletterPostForm;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterPosts\Tables\NewsletterPostTable;

final class NewsletterPostRelationManager extends RelationManager
{
    use Translatable;

    protected static string $relationship = 'newsletterPosts';

    public static function getModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_post');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-newsletter::navigation.newsletter_post');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->newsletterPosts()->count());
    }

    public function form(Schema $schema): Schema
    {
        return NewsletterPostForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return NewsletterPostTable::configure($table);
    }
}
