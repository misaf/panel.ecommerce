<?php

declare(strict_types=1);

namespace Misaf\VendraInquiryApi\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Illuminate\Http\Request;
use Misaf\VendraInquiry\Actions\SubmitInquiryAction;
use Misaf\VendraInquiryApi\ApiResource\InquiryResource;

/**
 * Record a contact enquiry from the storefront.
 *
 * Nothing is echoed back: answering `204` keeps the endpoint from confirming
 * whether an address is already known, and there is nothing here a sender
 * needs to read. Where it came from is captured from the request rather than
 * accepted from the body, so the source cannot be spoofed.
 *
 * @implements ProcessorInterface<InquiryResource, void>
 */
final readonly class SubmitInquiryProcessor implements ProcessorInterface
{
    public function __construct(
        private SubmitInquiryAction $submitInquiry,
        private Request $request,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $this->submitInquiry->execute(
            name: $data->name,
            email: $data->email,
            message: $data->message,
            phone: $data->phone,
            occasion: $data->occasion,
            source: 'storefront',
            locale: $data->preferredLocale ?? $this->request->getPreferredLanguage(),
        );
    }
}
