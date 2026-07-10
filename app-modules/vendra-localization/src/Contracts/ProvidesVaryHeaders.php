<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Contracts;

interface ProvidesVaryHeaders
{
    /**
     * The request headers this resolver reads, to be echoed in the
     * response Vary header so HTTP caches key on them.
     *
     * @return list<string>
     */
    public function varyHeaders(): array;
}
