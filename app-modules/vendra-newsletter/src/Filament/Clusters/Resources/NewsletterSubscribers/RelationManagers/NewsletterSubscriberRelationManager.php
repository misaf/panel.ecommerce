<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Schemas\NewsletterSubscriberForm;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Schemas\NewsletterSubscriberTable;

final class NewsletterSubscriberRelationManager extends RelationManager
{
    protected static string $relationship = 'newsletterSubscribers';

    public static function getModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_subscriber');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-newsletter::navigation.newsletter_subscriber');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->newsletterSubscribers()->count());
    }

    public function form(Schema $schema): Schema
    {
        return NewsletterSubscriberForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return NewsletterSubscriberTable::configure($table);
    }
}
