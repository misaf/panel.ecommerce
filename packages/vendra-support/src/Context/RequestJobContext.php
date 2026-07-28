<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Context;

use Closure;
use Illuminate\Support\Facades\Context;

final readonly class RequestJobContext
{
    public const string IDEMPOTENCY_KEY = 'idempotency_key';

    public const string PAYMENT_ID = 'payment_id';

    public const string RESELLER_ID = 'reseller_id';

    public const string SUBSCRIPTION_ID = 'subscription_id';

    public const string TENANT_ID = 'tenant_id';

    public function __construct(
        public int|string|null $tenantId = null,
        public int|string|null $resellerId = null,
        public int|string|null $subscriptionId = null,
        public int|string|null $paymentId = null,
        public ?string $idempotencyKey = null,
    ) {}

    public static function current(): self
    {
        return new self(
            tenantId: self::identifier(Context::get(self::TENANT_ID)),
            resellerId: self::identifier(Context::get(self::RESELLER_ID)),
            subscriptionId: self::identifier(Context::get(self::SUBSCRIPTION_ID)),
            paymentId: self::identifier(Context::get(self::PAYMENT_ID)),
            idempotencyKey: self::string(Context::get(self::IDEMPOTENCY_KEY)),
        );
    }

    public function add(): void
    {
        Context::add($this->toArray());
    }

    public function scope(Closure $callback): mixed
    {
        return Context::scope($callback, data: $this->toArray());
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        $context = [];

        foreach ([
            self::TENANT_ID       => $this->tenantId,
            self::RESELLER_ID     => $this->resellerId,
            self::SUBSCRIPTION_ID => $this->subscriptionId,
            self::PAYMENT_ID      => $this->paymentId,
            self::IDEMPOTENCY_KEY => $this->idempotencyKey,
        ] as $key => $value) {
            if (null !== $value) {
                $context[$key] = $value;
            }
        }

        return $context;
    }

    private static function identifier(mixed $value): int|string|null
    {
        return is_int($value) || is_string($value) ? $value : null;
    }

    private static function string(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }
}
