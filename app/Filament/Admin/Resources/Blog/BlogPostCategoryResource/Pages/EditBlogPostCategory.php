<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Blog\BlogPostCategoryResource\Pages;

use App\Filament\Admin\Resources\Blog\BlogPostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

final class EditBlogPostCategory extends EditRecord
{
    use Translatable;

    protected static string $resource = BlogPostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
