<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Blog\BlogPostResource\Pages;

use App\Filament\Admin\Resources\Blog\BlogPostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

final class CreateBlogPost extends CreateRecord
{
    use Translatable;

    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
