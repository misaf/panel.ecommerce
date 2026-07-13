<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Contracts;

interface CurrencyResolver
{
    public function available(): bool;

    public function defaultCode(): string;

    /**
     * @return array<string, string>
     */
    public function options(): array;

    /**
     * @return list<string>
     */
    public function activeCodes(): array;
}
