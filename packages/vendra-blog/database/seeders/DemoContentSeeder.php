<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Database\Seeders;

use Illuminate\Support\Facades\Validator;
use Misaf\VendraBlog\Database\Factories\BlogPostCategoryFactory;
use Misaf\VendraBlog\Database\Factories\BlogPostFactory;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraSupport\Concerns\RequiresCurrentTenant;
use Misaf\VendraSupport\Database\Seeders\DemoContentSeeder as BaseDemoContentSeeder;

final class DemoContentSeeder extends BaseDemoContentSeeder
{
    use RequiresCurrentTenant;

    protected function seedFactories(): void
    {
        $this->currentTenantOrNull();

        BlogPostCategoryFactory::new()
            ->enabled()
            ->count(4)
            ->create()
            ->each(fn(BlogPostCategory $blogPostCategory): mixed => BlogPostFactory::new()
                ->forCategory($blogPostCategory)
                ->enabled()
                ->count(3)
                ->create());
    }

    /**
     * @param  list<array<string, mixed>>  $records
     */
    protected function seedFixtures(array $records): void
    {
        $this->currentTenantOrNull();

        foreach ($records as $record) {
            $this->seedFixtureRecord($record);
        }
    }

    /**
     * @param  array<string, mixed>  $record
     */
    protected function seedFixtureRecord(array $record): void
    {
        $this->handleSeedFixtureRecord($this->validatedFixtureRecord($record));
    }

    /**
     * @param array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool,
     *     blog_posts: list<array{
     *         name: non-empty-array<string, string>,
     *         description: non-empty-array<string, string>,
     *         slug: non-empty-array<string, string>,
     *         status: bool
     *     }>
     * } $data
     */
    private function handleSeedFixtureRecord(array $data): void
    {
        $blogPostCategory = BlogPostCategory::create([
            'name'        => $data['name'],
            'description' => $data['description'],
            'slug'        => $data['slug'],
            'status'      => $data['status'],
        ]);

        foreach ($data['blog_posts'] as $blogPostRecord) {
            $this->handleBlogPostFixtureRecord($blogPostCategory, $blogPostRecord);
        }
    }

    /**
     * @param array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool
     * } $blogPostRecord
     */
    private function handleBlogPostFixtureRecord(BlogPostCategory $blogPostCategory, array $blogPostRecord): void
    {
        $blogPostCategory->blogPosts()->create([
            'name'        => $blogPostRecord['name'],
            'description' => $blogPostRecord['description'],
            'slug'        => $blogPostRecord['slug'],
            'status'      => $blogPostRecord['status'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $record
     * @return array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool,
     *     blog_posts: list<array{
     *         name: non-empty-array<string, string>,
     *         description: non-empty-array<string, string>,
     *         slug: non-empty-array<string, string>,
     *         status: bool
     *     }>
     * }
     */
    private function validatedFixtureRecord(array $record): array
    {
        /** @var array{
         *     name: non-empty-array<string, string>,
         *     description: non-empty-array<string, string>,
         *     slug: non-empty-array<string, string>,
         *     status: bool,
         *     blog_posts: list<array{
         *         name: non-empty-array<string, string>,
         *         description: non-empty-array<string, string>,
         *         slug: non-empty-array<string, string>,
         *         status: bool
         *     }>
         * } $validated
         */
        $validated = Validator::make(
            data: $record,
            rules: [
                'name'                       => ['required', 'array', 'min:1'],
                'name.*'                     => ['required', 'string'],
                'description'                => ['required', 'array', 'min:1'],
                'description.*'              => ['required', 'string'],
                'slug'                       => ['required', 'array', 'min:1'],
                'slug.*'                     => ['required', 'string'],
                'status'                     => ['required', 'boolean'],
                'blog_posts'                 => ['required', 'array', 'list'],
                'blog_posts.*'               => ['required', 'array:name,description,slug,status'],
                'blog_posts.*.name'          => ['required', 'array', 'min:1'],
                'blog_posts.*.name.*'        => ['required', 'string'],
                'blog_posts.*.description'   => ['required', 'array', 'min:1'],
                'blog_posts.*.description.*' => ['required', 'string'],
                'blog_posts.*.slug'          => ['required', 'array', 'min:1'],
                'blog_posts.*.slug.*'        => ['required', 'string'],
                'blog_posts.*.status'        => ['required', 'boolean'],
            ],
        )->validate();

        return $validated;
    }
}
