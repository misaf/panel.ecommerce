<?php

declare(strict_types=1);

namespace App\Http\Resources\V1\Blog;

use App\Http\Resources\V1\MediaResource;
use App\Traits\LocalizableAttributesTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class BlogPostResource extends JsonResource
{
    use LocalizableAttributesTrait;

    public function toArray(Request $request): array
    {
        $locale = $request->query('locale');

        return [
            'type'       => 'blog-post',
            'id'         => $this->id,
            'attributes' => [
                'blog_post_category_id' => $this->blog_post_category_id,
                'name'                  => $this->getLocalizedAttribute('name', $locale),
                'description'           => $this->getLocalizedAttribute('description', $locale),
                'slug'                  => $this->getLocalizedAttribute('slug', $locale),
                'position'              => $this->position,
                'status'                => $this->status,
                'created_at'            => $this->created_at,
                'updated_at'            => $this->updated_at,
            ],
            'relationships' => [

            ],
            'included' => $this->loadIncludedResources(),
        ];
    }

    private function loadIncludedResources(): array
    {
        return [
            new BlogPostCategoryResource($this->whenLoaded('blogPostCategory')),
            MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
