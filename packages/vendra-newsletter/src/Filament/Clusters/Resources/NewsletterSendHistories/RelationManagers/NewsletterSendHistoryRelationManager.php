<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistories\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistories\NewsletterSendHistoryResource;
use Misaf\VendraNewsletter\Models\NewsletterSendHistory;

final class NewsletterSendHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'newsletterSendHistories';

    public static function getModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->newsletterSendHistories()->count());
    }

    public function table(Table $table): Table
    {
        return NewsletterSendHistoryResource::table($table)
            ->recordTitle(fn(NewsletterSendHistory $record): string => $record->token)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
