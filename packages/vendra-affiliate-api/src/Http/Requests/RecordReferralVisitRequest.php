<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RecordReferralVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'code'       => ['required', 'string', 'max:64'],
            'landingUrl' => ['nullable', 'url:http,https', 'max:2048'],
        ];
    }
}
