<?php

declare(strict_types=1);

namespace Misaf\VendraInquiry\Filament\Clusters\Resources\Inquiries\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Misaf\VendraInquiry\Filament\Clusters\Resources\Inquiries\Actions\AnswerInquiryAction;
use Misaf\VendraInquiry\Filament\Clusters\Resources\Inquiries\Actions\CloseInquiryAction;
use Misaf\VendraInquiry\Filament\Clusters\Resources\Inquiries\Actions\ReopenInquiryAction;
use Misaf\VendraInquiry\Filament\Clusters\Resources\Inquiries\InquiryResource;

final class ViewInquiry extends ViewRecord
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            AnswerInquiryAction::make(),
            CloseInquiryAction::make(),
            ReopenInquiryAction::make(),
            DeleteAction::make(),
        ];
    }
}
