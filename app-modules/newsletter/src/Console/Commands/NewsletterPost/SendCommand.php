<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Console\Commands\NewsletterPost;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Database\Eloquent\Builder;

use function Laravel\Prompts\search;

use Misaf\Newsletter\Actions\Newsletter\SendWithPostAction;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\Tenant\Models\Tenant;

final class SendCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'newsletter-post:send
                            {tenant : The tenant to send the newsletter for}
                            {newsletter : The newsletter to send}
                            {newsletterPost : The newsletter post to send}
                            {--dry-run : Show what would be sent without actually sending}';

    protected $description = 'Send a newsletter with a specific post';

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'tenant' => fn() => search(
                label: 'Search for a tenant:',
                placeholder: 'E.g. tenant-slug',
                options: fn(string $value) => Tenant::enabled()
                    ->when(filled($value), fn(Builder $query) => $query->where('slug', 'like', "%{$value}%"))
                    ->pluck('slug', 'id')
                    ->all(),
            ),

            'newsletter' => fn() => search(
                label: "Search for a newsletter of the tenant {$this->argument('tenant')}:",
                placeholder: 'E.g. newsletter-slug',
                options: fn(string $value) => Newsletter::enabled()
                    ->where('tenant_id', $this->argument('tenant'))
                    ->when(filled($value), fn(Builder $query) => $query->whereJsonContainsLocale('slug', app()->getLocale(), "%{$value}%", 'like'))
                    ->pluck('slug', 'id')
                    ->all(),
            ),

            'newsletterPost' => fn() => search(
                label: "Search for a newsletter post of the newsletter {$this->argument('newsletter')}:",
                placeholder: 'E.g. newsletter-post-slug',
                options: fn(string $value) => NewsletterPost::ready()
                    ->where('newsletter_id', $this->argument('newsletter'))
                    ->when(filled($value), fn(Builder $query) => $query->whereJsonContainsLocale('slug', app()->getLocale(), "%{$value}%", 'like'))
                    ->pluck('slug', 'id')
                    ->all()
            ),
        ];
    }

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        if (app()->isDownForMaintenance() && ! $isDryRun) {
            $this->warn('Application is in maintenance mode. Command aborted.');
            return self::FAILURE;
        }

        $newsletter = Newsletter::findOrFail($this->argument('newsletter'));
        $newsletterPost = NewsletterPost::findOrFail($this->argument('newsletterPost'));

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No emails will be queued');
        }

        $this->info("Sending newsletter {$newsletter->slug} with post {$newsletterPost->slug}");

        $stats = (new SendWithPostAction())->execute($newsletter, $newsletterPost, $isDryRun);

        $this->info("Queued {$stats['queued']} emails");

        return self::SUCCESS;
    }
}
