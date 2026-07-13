<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistorySubscribers\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistorySubscribers\Schemas\NewsletterSendHistorySubscriberForm;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistorySubscribers\Schemas\NewsletterSendHistorySubscriberTable;

final class NewsletterSendHistorySubscriberRelationManager extends RelationManager
{
    protected static string $relationship = 'newsletterSendHistorySubscribers';

    public static function getModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history_subscriber');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history_subscriber');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history_subscriber');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->newsletterSendHistorySubscribers()->count());
    }

    public function form(Schema $schema): Schema
    {
        return NewsletterSendHistorySubscriberForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return NewsletterSendHistorySubscriberTable::configure($table);
    }
}
