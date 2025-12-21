<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Tags\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\SpatieTagsInput;

final class AddTagAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'addTag';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('tag::forms.add_tag'))
            ->icon('heroicon-s-tag')
            ->schema([
                SpatieTagsInput::make('tags')
                    ->label(__('tag::navigation.tag'))
                    ->required()
                    ->placeholder(__('tag::forms.tag_placeholder'))
                    ->suggestions([
                        __('tag::forms.tag_suggestions.vip'),
                        __('tag::forms.tag_suggestions.active'),
                        __('tag::forms.tag_suggestions.inactive'),
                        __('tag::forms.tag_suggestions.test'),
                        __('tag::forms.tag_suggestions.premium'),
                        __('tag::forms.tag_suggestions.normal'),
                    ]),
            ])
            ->successNotificationTitle(__('tag::forms.tags_added_successfully'));
    }
}
