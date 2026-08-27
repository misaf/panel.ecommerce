<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraInquiry\Database\Factories\InquiryFactory;
use Misaf\VendraInquiry\Filament\Clusters\Resources\Inquiries\Pages\ListInquiries;
use Misaf\VendraInquiry\Filament\Clusters\Resources\Inquiries\Pages\ViewInquiry;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentAdminTestContext();

    Filament::getPanel('admin')->strictAuthorization();
});

it('renders the enquiries inbox under strict authorization', function (): void {
    $inquiry = InquiryFactory::new()->createOne();

    livewire(ListInquiries::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$inquiry]);
});

it('renders the view enquiry page under strict authorization', function (): void {
    $inquiry = InquiryFactory::new()->createOne();

    livewire(ViewInquiry::class, ['record' => $inquiry->getKey()])->assertOk();
});
