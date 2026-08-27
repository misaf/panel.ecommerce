<?php

declare(strict_types=1);

namespace Misaf\VendraInquiry\Filament\Clusters\Resources\Inquiries\Pages;

use Filament\Resources\Pages\ListRecords;
use Misaf\VendraInquiry\Filament\Clusters\Resources\Inquiries\InquiryResource;

final class ListInquiries extends ListRecords
{
    protected static string $resource = InquiryResource::class;
}
