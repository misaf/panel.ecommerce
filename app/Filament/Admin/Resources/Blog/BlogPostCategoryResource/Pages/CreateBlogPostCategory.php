<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Blog\BlogPostCategoryResource\Pages;

use App\Filament\Admin\Resources\Blog\BlogPostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

final class CreateBlogPostCategory extends CreateRecord
{
    use Translatable;

    protected static string $resource = BlogPostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
