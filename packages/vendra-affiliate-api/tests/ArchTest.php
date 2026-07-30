<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the affiliate API module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraAffiliateApi')
    ->not->toUse('Misaf\VendraTenant');

arch('API state providers and processors never persist or open transactions directly — writes delegate to domain Actions')
    ->expect('Misaf\VendraAffiliateApi\State')
    ->not->toUse('Illuminate\Support\Facades\DB');

arch('the referral visit processor delegates its write to a domain Action')
    ->expect('Misaf\VendraAffiliateApi\State\RecordReferralVisitProcessor')
    ->toUse('Misaf\VendraAffiliate\Actions\RecordAffiliateClickAction');
