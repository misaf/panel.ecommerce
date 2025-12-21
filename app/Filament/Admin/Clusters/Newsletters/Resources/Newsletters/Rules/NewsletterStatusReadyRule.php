<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Misaf\Newsletter\Enums\NewsletterStatusEnum;

final class NewsletterStatusReadyRule implements DataAwareRule, ValidationRule
{
    private array $data = [];
    private const ALLOWED_STATUSES = [
        // Only these statuses are allowed to be selected by the user
        // when creating or editing a newsletter via the form.
        // DRAFT is always allowed; READY is allowed only on edit.
        // The READY-specific constraints are checked below.
        NewsletterStatusEnum::DRAFT->value,
        NewsletterStatusEnum::READY->value,
    ];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string, ?string=):PotentiallyTranslatedString $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $newsletterId = $this->data['record']['id'] ?? null;
        $isCreate = empty($newsletterId);

        if ($isCreate && $value !== NewsletterStatusEnum::DRAFT->value) {
            $fail(__('newsletter::validation.status.only_draft_allowed_on_create'));
            return;
        }

        if ( ! in_array($value, self::ALLOWED_STATUSES, true)) {
            $fail(__('newsletter::validation.status.invalid'));
            return;
        }

        if ($value === NewsletterStatusEnum::READY->value) {
            if ( ! NewsletterPostService::where('newsletter_id', $newsletterId)->ready()->exists()) {
                $fail(__('newsletter::validation.status.ready_requires_posts'));
            }
        }
    }
}
