<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Imports;

use Carbon\CarbonInterface;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraSupport\Support\TenantAwareness;
use Misaf\VendraUser\Rules\EmailValidation;

final class NewsletterSubscriberImporter extends Importer
{
    protected static ?string $model = NewsletterSubscriber::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('newsletter')
                ->array(',')
                ->example(__('vendra-newsletter::importer.newsletter_slug.example'))
                ->exampleHeader('newsletter')
                ->helperText(__('vendra-newsletter::importer.newsletter_slug.helper_text'))
                ->label('newsletter')
                ->requiredMapping()
                ->rules(['bail', 'required', 'array', 'max:10'])
                ->nestedRecursiveRules(function (array $options, NewsletterSubscriber $record) {
                    $rules = ['bail', 'required', 'string', 'max:255', 'alpha_dash'];

                    $rules[] = Rule::exists('newsletters', 'slug->' . app()->getLocale())
                        ->where('tenant_id', TenantAwareness::currentId());

                    return $rules;
                }),

            ImportColumn::make('user')
                ->example(__('vendra-newsletter::importer.user.example'))
                ->exampleHeader('username')
                ->helperText(__('vendra-newsletter::importer.user.helper_text'))
                ->label('username')
                ->relationship(resolveUsing: 'username')
                ->rules(['bail', 'nullable', 'string', 'max:255']),

            ImportColumn::make('email')
                ->example(__('vendra-newsletter::importer.email.example'))
                ->exampleHeader('email_address')
                ->helperText(__('vendra-newsletter::importer.email.helper_text'))
                ->label('email_address')
                ->requiredMapping()
                ->rules(function (array $options, NewsletterSubscriber $record) {
                    $rules = ['bail', 'required', 'string', 'email:rfc,strict,spoof,filter'];

                    $uniqueRule = Rule::unique('newsletter_subscribers', 'email')
                        ->where('tenant_id', TenantAwareness::currentId());

                    if ($options['update_existing_records'] ?? false) {
                        $uniqueRule->ignore($record->email, 'email');
                    }

                    $rules[] = $uniqueRule;

                    if ($options['enable_real_email_validation'] ?? false) {
                        $rules[] = new EmailValidation(app()->isProduction());
                    }

                    return $rules;
                }),
        ];
    }

    protected function beforeFill(): void
    {
        unset($this->data['newsletter']);
    }

    protected function afterSave(): void
    {
        $newsletterSlugs = explode(',', $this->originalData['newsletter']);

        $newsletterIds = Newsletter::whereIn('slug->' . app()->getLocale(), $newsletterSlugs)->pluck('id');

        $this->record->newsletters()->sync($newsletterIds);
    }

    public function resolveRecord(): NewsletterSubscriber
    {
        return NewsletterSubscriber::query()
            ->firstOrNew(['email' => $this->data['email']]);
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Toggle::make('enable_real_email_validation')
                ->default(true)
                ->label(__('vendra-newsletter::importer.options.enable_real_email_validation.label'))
                ->onIcon('heroicon-m-bolt'),

            Toggle::make('update_existing_records')
                ->helperText(__('vendra-newsletter::importer.options.update_existing_records.helper_text'))
                ->label(__('vendra-newsletter::importer.options.update_existing_records.label'))
                ->onIcon('heroicon-m-bolt'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $successfulCount = number_format($import->successful_rows);
        $successfulRows = str('row')->plural($import->successful_rows)->toString();

        $body = __('vendra-newsletter::importer.notification.completed_body', [
            'successful_count' => $successfulCount,
            'successful_rows'  => $successfulRows,
        ]);

        $failedRowsCount = $import->getFailedRowsCount();
        if ($failedRowsCount > 0) {
            $body .= ' ' . __('vendra-newsletter::importer.notification.failed_rows', [
                'failed_count' => number_format($failedRowsCount),
                'failed_rows'  => str('row')->plural($failedRowsCount)->toString(),
            ]);
        }

        return $body;
    }

    public function getJobQueue(): string
    {
        return 'imports';
    }

    public function getJobConnection(): string
    {
        return 'redis';
    }

    public function getJobRetryUntil(): CarbonInterface
    {
        return Carbon::now()->addSeconds(30);
    }
}
