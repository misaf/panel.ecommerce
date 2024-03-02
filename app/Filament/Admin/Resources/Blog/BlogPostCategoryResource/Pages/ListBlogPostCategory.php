<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Blog\BlogPostCategoryResource\Pages;

use App\Filament\Admin\Resources\Blog\BlogPostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

final class ListBlogPostCategory extends ListRecords
{
    use Translatable;

    protected static string $resource = BlogPostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
