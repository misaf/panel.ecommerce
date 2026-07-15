<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

final class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?int $navigationSort = -2;

    protected static string $routePath = '/admin';

    public function getMaxContentWidth(): string
    {
        return 'screen-2xl';
    }

    public function getWidgetsContentComponent(): Component
    {
        return Grid::make([
            'lg' => 3,
        ])->schema([
            Group::make(fn(): array => $this->getWidgetsSchemaComponents($this->getWidgets()))
                ->columns([
                    'md' => 3,
                ])
                ->columnSpan([
                    'lg' => 2,
                ]),

            Section::make(__('page.dashboard_changelog'))
                ->icon(Heroicon::OutlinedDocumentText)
                ->schema([
                    View::make('filament.admin.dashboard-markdown')
                        ->viewData(fn(): array => [
                            'content' => $this->getDashboardChangelog(),
                        ]),
                ])
                ->columnSpan([
                    'lg' => 1,
                ]),
        ]);
    }

    private function getDashboardChangelog(): HtmlString
    {
        return str(__('page.dashboard_changelog_items'))
            ->markdown([
                'html_input'         => 'strip',
                'allow_unsafe_links' => false,
            ])
            ->toHtmlString();
    }
}
