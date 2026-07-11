<?php

declare(strict_types=1);

use Misaf\VendraSupport\Support\NullTenantResolver;

it('executes the callback directly when tenancy is disabled', function (): void {
    $resolver = new NullTenantResolver();

    expect($resolver->execute(1, fn(): string => 'ran'))->toBe('ran');
});
