<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraAddress\Models\Address;
use Misaf\VendraDocument\Models\Document;
use Misaf\VendraPhone\Models\PhoneNumber;
use Misaf\VendraUserProfile\Models\UserProfile;
use Misaf\VendraUserProfile\Tests\Support\UserProfileModuleTestContext;
use Misaf\VendraVerification\Models\Verification;

it('persists independently installed profile provider records', function (): void {
    UserProfileModuleTestContext::createCurrentTenant();
    $user = UserProfileModuleTestContext::createUser();
    $profile = UserProfile::factory()->forUser($user)->create();

    $address = Address::factory()->create([
        'user_profile_id'     => $profile->id,
        'country_code'        => 'JP',
        'administrative_area' => 'Tokyo',
        'metadata'            => ['building' => 'North Tower'],
    ]);
    $phone = PhoneNumber::factory()->create([
        'user_profile_id' => $profile->id,
        'country_code'    => 'DE',
        'number'          => '+4930123456',
    ]);
    $document = Document::factory()->create([
        'user_profile_id'      => $profile->id,
        'issuing_country_code' => 'IR',
        'metadata'             => ['authority' => 'Civil Registry'],
    ]);
    $verification = Verification::factory()->create([
        'user_profile_id' => $profile->id,
        'country_code'    => 'AE',
        'provider'        => 'Example KYC',
    ]);

    expect($profile->addresses())->toBeInstanceOf(HasMany::class)
        ->and($profile->phoneNumbers())->toBeInstanceOf(HasMany::class)
        ->and($profile->documents())->toBeInstanceOf(HasMany::class)
        ->and($profile->verifications())->toBeInstanceOf(HasMany::class)
        ->and($profile->addresses()->sole()->is($address))->toBeTrue()
        ->and($profile->phoneNumbers()->sole()->is($phone))->toBeTrue()
        ->and($profile->documents()->sole()->is($document))->toBeTrue()
        ->and($profile->verifications()->sole()->is($verification))->toBeTrue()
        ->and($address->tenant_id)->toBe(1)
        ->and($phone->tenant_id)->toBe(1)
        ->and($document->tenant_id)->toBe(1)
        ->and($verification->tenant_id)->toBe(1);
});

it('uses scalar country-aware columns and structured metadata', function (): void {
    expect(Schema::getColumnType('addresses', 'country_code'))->toBeIn(['string', 'varchar'])
        ->and(Schema::getColumnType('addresses', 'metadata'))->toBeIn(['json', 'text'])
        ->and(Schema::getColumnType('phone_numbers', 'number'))->toBeIn(['string', 'varchar'])
        ->and(Schema::getColumnType('phone_numbers', 'metadata'))->toBeIn(['json', 'text'])
        ->and(Schema::getColumnType('documents', 'issuing_country_code'))->toBeIn(['string', 'varchar'])
        ->and(Schema::getColumnType('documents', 'metadata'))->toBeIn(['json', 'text'])
        ->and(Schema::getColumnType('verifications', 'country_code'))->toBeIn(['string', 'varchar'])
        ->and(Schema::getColumnType('verifications', 'metadata'))->toBeIn(['json', 'text']);
});
