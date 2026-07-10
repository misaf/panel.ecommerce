<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Database\Seeders;

use Illuminate\Support\Facades\Validator;
use Misaf\VendraFaq\Database\Factories\FaqCategoryFactory;
use Misaf\VendraFaq\Database\Factories\FaqFactory;
use Misaf\VendraFaq\Models\FaqCategory;
use Misaf\VendraSupport\Database\Seeders\DemoContentSeeder as BaseDemoContentSeeder;

final class DemoContentSeeder extends BaseDemoContentSeeder
{
    protected function seedFactories(): void
    {
        $this->currentTenantOrNull();

        FaqCategoryFactory::new()
            ->enabled()
            ->count(4)
            ->create()
            ->each(fn(FaqCategory $faqCategory): mixed => FaqFactory::new()
                ->forCategory($faqCategory)
                ->enabled()
                ->count(3)
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
     *     faqs: list<array{
     *         name: non-empty-array<string, string>,
     *         description: non-empty-array<string, string>,
     *         slug: non-empty-array<string, string>,
     *         status: bool
     *     }>
     * } $data
     */
    private function handleSeedFixtureRecord(array $data): void
    {
        $faqCategory = FaqCategory::create([
            'name'        => $data['name'],
            'description' => $data['description'],
            'slug'        => $data['slug'],
            'status'      => $data['status'],
        ]);

        foreach ($data['faqs'] as $faqRecord) {
            $this->handleFaqFixtureRecord($faqCategory, $faqRecord);
        }
    }

    /**
     * @param array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool
     * } $faqRecord
     */
    private function handleFaqFixtureRecord(FaqCategory $faqCategory, array $faqRecord): void
    {
        $faqCategory->faqs()->create([
            'name'        => $faqRecord['name'],
            'description' => $faqRecord['description'],
            'slug'        => $faqRecord['slug'],
            'status'      => $faqRecord['status'],
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
     *     faqs: list<array{
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
         *     faqs: list<array{
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
                'name'                 => ['required', 'array', 'min:1'],
                'name.*'               => ['required', 'string'],
                'description'          => ['required', 'array', 'min:1'],
                'description.*'        => ['required', 'string'],
                'slug'                 => ['required', 'array', 'min:1'],
                'slug.*'               => ['required', 'string'],
                'status'               => ['required', 'boolean'],
                'faqs'                 => ['required', 'array', 'list'],
                'faqs.*'               => ['required', 'array:name,description,slug,status'],
                'faqs.*.name'          => ['required', 'array', 'min:1'],
                'faqs.*.name.*'        => ['required', 'string'],
                'faqs.*.description'   => ['required', 'array', 'min:1'],
                'faqs.*.description.*' => ['required', 'string'],
                'faqs.*.slug'          => ['required', 'array', 'min:1'],
                'faqs.*.slug.*'        => ['required', 'string'],
                'faqs.*.status'        => ['required', 'boolean'],
            ],
        )->validate();

        return $validated;
    }
}
