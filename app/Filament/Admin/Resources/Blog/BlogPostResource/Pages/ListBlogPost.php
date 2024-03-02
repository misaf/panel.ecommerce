<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Blog\BlogPostResource\Pages;

use App\Filament\Admin\Resources\Blog\BlogPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

final class ListBlogPost extends ListRecords
{
    use Translatable;

    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
