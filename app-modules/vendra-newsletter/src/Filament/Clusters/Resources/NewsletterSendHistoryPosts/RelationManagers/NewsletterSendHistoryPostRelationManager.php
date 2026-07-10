<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistoryPosts\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use LaraZeus\SpatieTranslatable\Resources\RelationManagers\Concerns\Translatable;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistories\NewsletterSendHistoryResource;

final class NewsletterSendHistoryPostRelationManager extends RelationManager
{
    use Translatable;

    protected static string $relationship = 'newsletterPosts';

    public static function getModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history_post');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history_post');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history_post');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->newsletterPosts()->count());
    }

    public function table(Table $table): Table
    {
        return NewsletterSendHistoryResource::table($table);
    }
}
