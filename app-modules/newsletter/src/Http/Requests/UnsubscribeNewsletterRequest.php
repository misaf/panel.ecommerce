<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

final class UnsubscribeNewsletterRequest extends FormRequest
{
    /**
     * @return bool
     */
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
            'email' => 'required|email:rfc,strict,spoof,filter,filter_unicode',
            'token' => 'required|string|size:64|alpha_num',
        ];
    }

    /**
     * @param Validator $validator
     * @return void
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response('Invalid request parameters.', Response::HTTP_BAD_REQUEST)
                ->header('Content-Type', 'text/plain')
        );
    }
}
