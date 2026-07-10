<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Database\Seeders;

use Illuminate\Support\Facades\Validator;
use Misaf\VendraCustomPage\Database\Factories\CustomPageCategoryFactory;
use Misaf\VendraCustomPage\Database\Factories\CustomPageFactory;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraSupport\Database\Seeders\DemoContentSeeder as BaseDemoContentSeeder;

final class DemoContentSeeder extends BaseDemoContentSeeder
{
    protected function seedFactories(): void
    {
        $this->currentTenantOrNull();

        CustomPageCategoryFactory::new()
            ->enabled()
            ->count(2)
            ->create()
            ->each(fn(CustomPageCategory $customPageCategory): mixed => CustomPageFactory::new()
                ->forCategory($customPageCategory)
                ->enabled()
                ->count(2)
                ->create());
    }

    /**
     * @param list<array<string, mixed>> $records
     */
    protected function seedFixtures(array $records): void
    {
        $this->currentTenantOrNull();

        foreach ($records as $record) {
            $this->handleSeedFixtureRecord($this->validatedFixtureRecord($record));
        }
    }

    /**
     * @param array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool,
     *     custom_pages: list<array{
     *         name: non-empty-array<string, string>,
     *         description: non-empty-array<string, string>,
     *         slug: non-empty-array<string, string>,
     *         status: bool
     *     }>
     * } $data
     */
    private function handleSeedFixtureRecord(array $data): void
    {
        $customPageCategory = CustomPageCategory::create([
            'name'        => $data['name'],
            'description' => $data['description'],
            'slug'        => $data['slug'],
            'status'      => $data['status'],
        ]);

        foreach ($data['custom_pages'] as $customPageRecord) {
            $this->handleCustomPageFixtureRecord($customPageCategory, $customPageRecord);
        }
    }

    /**
     * @param array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool
     * } $customPageRecord
     */
    private function handleCustomPageFixtureRecord(CustomPageCategory $customPageCategory, array $customPageRecord): void
    {
        $customPageCategory->customPages()->create([
            'name'        => $customPageRecord['name'],
            'description' => $customPageRecord['description'],
            'slug'        => $customPageRecord['slug'],
            'status'      => $customPageRecord['status'],
        ]);
    }

    /**
     * @param array<string, mixed> $record
     *
     * @return array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool,
     *     custom_pages: list<array{
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
         *     custom_pages: list<array{
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
                'name'                         => ['required', 'array', 'min:1'],
                'name.*'                       => ['required', 'string'],
                'description'                  => ['required', 'array', 'min:1'],
                'description.*'                => ['required', 'string'],
                'slug'                         => ['required', 'array', 'min:1'],
                'slug.*'                       => ['required', 'string'],
                'status'                       => ['required', 'boolean'],
                'custom_pages'                 => ['required', 'array', 'list'],
                'custom_pages.*'               => ['required', 'array:name,description,slug,status'],
                'custom_pages.*.name'          => ['required', 'array', 'min:1'],
                'custom_pages.*.name.*'        => ['required', 'string'],
                'custom_pages.*.description'   => ['required', 'array', 'min:1'],
                'custom_pages.*.description.*' => ['required', 'string'],
                'custom_pages.*.slug'          => ['required', 'array', 'min:1'],
                'custom_pages.*.slug.*'        => ['required', 'string'],
                'custom_pages.*.status'        => ['required', 'boolean'],
            ],
        )->validate();

        return $validated;
    }
}
