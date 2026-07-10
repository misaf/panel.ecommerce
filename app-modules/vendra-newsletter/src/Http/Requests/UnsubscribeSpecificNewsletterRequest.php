<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

final class UnsubscribeSpecificNewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'email'           => 'required|email:rfc,strict,spoof,filter,filter_unicode',
            'token'           => 'required|string|size:64|alpha_num',
            'newsletter_slug' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response('Invalid request parameters.', Response::HTTP_BAD_REQUEST)
                ->header('Content-Type', 'text/plain')
        );
    }
}
