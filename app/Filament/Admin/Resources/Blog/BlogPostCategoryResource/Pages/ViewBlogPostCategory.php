<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Blog\BlogPostCategoryResource\Pages;

use App\Filament\Admin\Resources\Blog\BlogPostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ViewRecord\Concerns\Translatable;

final class ViewBlogPostCategory extends ViewRecord
{
    use Translatable;

    protected static string $resource = BlogPostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
