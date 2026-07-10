<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Database\Seeders;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Misaf\VendraNewsletter\Database\Factories\NewsletterFactory;
use Misaf\VendraNewsletter\Database\Factories\NewsletterPostFactory;
use Misaf\VendraNewsletter\Database\Factories\NewsletterSubscriberFactory;
use Misaf\VendraNewsletter\Enums\NewsletterPostStatusEnum;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraSupport\Concerns\RequiresCurrentTenant;
use Misaf\VendraSupport\Database\Seeders\DemoContentSeeder as BaseDemoContentSeeder;
use Misaf\VendraTenant\Models\Tenant;

final class DemoContentSeeder extends BaseDemoContentSeeder
{
    use RequiresCurrentTenant;

    protected function seedFactories(): void
    {
        $tenant = $this->currentTenant();

        $this->seedFactoryRecords($tenant);
    }

    /**
     * @param  list<array<string, mixed>>  $records
     */
    protected function seedFixtures(array $records): void
    {
        $tenant = $this->currentTenant();

        foreach ($records as $record) {
            $this->seedFixtureRecord($tenant, $record);
        }
    }

    protected function seedFactoryRecords(Tenant $tenant): void
    {
        $newsletters = NewsletterFactory::new()
            ->enabled()
            ->count(2)
            ->create(['tenant_id' => $tenant->id]);

        if ( ! $newsletters instanceof Collection) {
            $newsletters = new Collection([$newsletters]);
        }

        $newsletters->each(function (Newsletter $newsletter): void {
            NewsletterPostFactory::new()
                ->withNewsletter($newsletter)
                ->ready()
                ->count(2)
                ->create();

            $newsletterSubscribers = NewsletterSubscriberFactory::new()
                ->withoutUser()
                ->count(3)
                ->create();

            if ( ! $newsletterSubscribers instanceof Collection) {
                $newsletterSubscribers = new Collection([$newsletterSubscribers]);
            }

            $newsletter->newsletterSubscribers()->syncWithoutDetaching(
                $newsletterSubscribers->mapWithKeys(fn(NewsletterSubscriber $newsletterSubscriber): array => [
                    $newsletterSubscriber->id => ['subscribed_at' => now()],
                ])->all(),
            );
        });
    }

    protected function seedFixtureRecord(Tenant $tenant, array $record): void
    {
        $data = $this->validatedFixtureRecord($record);

        $this->handleSeedFixtureRecord($tenant, $data);
    }

    /**
     * @param array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool,
     *     posts: list<array{
     *         name: non-empty-array<string, string>,
     *         description: non-empty-array<string, string>,
     *         slug: non-empty-array<string, string>,
     *         status: string
     *     }>,
     *     subscribers: list<array{email: string}>
     * } $data
     */
    private function handleSeedFixtureRecord(Tenant $tenant, array $data): void
    {
        $newsletter = new Newsletter([
            'name'        => $data['name'],
            'description' => $data['description'],
            'slug'        => $data['slug'],
            'status'      => $data['status'],
        ]);

        $newsletter->tenant_id = $tenant->id;
        $newsletter->save();

        foreach ($data['posts'] as $newsletterPostRecord) {
            $this->handleNewsletterPostFixtureRecord($newsletter, $newsletterPostRecord);
        }

        foreach ($data['subscribers'] as $newsletterSubscriberRecord) {
            $this->handleNewsletterSubscriberFixtureRecord($newsletter, $newsletterSubscriberRecord);
        }
    }

    /**
     * @param array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: string
     * } $newsletterPostRecord
     */
    private function handleNewsletterPostFixtureRecord(Newsletter $newsletter, array $newsletterPostRecord): void
    {
        $newsletter->newsletterPosts()->create([
            'name'        => $newsletterPostRecord['name'],
            'description' => $newsletterPostRecord['description'],
            'slug'        => $newsletterPostRecord['slug'],
            'status'      => NewsletterPostStatusEnum::from($newsletterPostRecord['status']),
        ]);
    }

    /**
     * @param array{
     *     email: string
     * } $newsletterSubscriberRecord
     */
    private function handleNewsletterSubscriberFixtureRecord(Newsletter $newsletter, array $newsletterSubscriberRecord): void
    {
        $newsletterSubscriber = NewsletterSubscriber::query()->firstOrCreate([
            'email' => $newsletterSubscriberRecord['email'],
        ]);

        $newsletter->newsletterSubscribers()->syncWithoutDetaching([
            $newsletterSubscriber->id => ['subscribed_at' => now()],
        ]);
    }

    /**
     * @param  array<string, mixed>  $record
     * @return array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool,
     *     posts: list<array{
     *         name: non-empty-array<string, string>,
     *         description: non-empty-array<string, string>,
     *         slug: non-empty-array<string, string>,
     *         status: string
     *     }>,
     *     subscribers: list<array{email: string}>
     * }
     */
    private function validatedFixtureRecord(array $record): array
    {
        /** @var array{
         *     name: non-empty-array<string, string>,
         *     description: non-empty-array<string, string>,
         *     slug: non-empty-array<string, string>,
         *     status: bool,
         *     posts: list<array{
         *         name: non-empty-array<string, string>,
         *         description: non-empty-array<string, string>,
         *         slug: non-empty-array<string, string>,
         *         status: string
         *     }>,
         *     subscribers: list<array{email: string}>
         * } $validated
         */
        $validated = Validator::make(
            data: $record,
            rules: [
                'name'                  => ['required', 'array', 'min:1'],
                'name.*'                => ['required', 'string'],
                'description'           => ['required', 'array', 'min:1'],
                'description.*'         => ['required', 'string'],
                'slug'                  => ['required', 'array', 'min:1'],
                'slug.*'                => ['required', 'string'],
                'status'                => ['required', 'boolean'],
                'posts'                 => ['required', 'array', 'list'],
                'posts.*'               => ['required', 'array:name,description,slug,status'],
                'posts.*.name'          => ['required', 'array', 'min:1'],
                'posts.*.name.*'        => ['required', 'string'],
                'posts.*.description'   => ['required', 'array', 'min:1'],
                'posts.*.description.*' => ['required', 'string'],
                'posts.*.slug'          => ['required', 'array', 'min:1'],
                'posts.*.slug.*'        => ['required', 'string'],
                'posts.*.status'        => ['required', new Enum(NewsletterPostStatusEnum::class)],
                'subscribers'           => ['required', 'array', 'list'],
                'subscribers.*'         => ['required', 'array:email'],
                'subscribers.*.email'   => ['required', 'email'],
            ],
        )->validate();

        return $validated;
    }
}
