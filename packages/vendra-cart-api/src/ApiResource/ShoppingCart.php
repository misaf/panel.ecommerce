<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraCartApi\State\ShoppingCartProvider;

#[ApiResource(
    shortName: 'Cart',
    operations: [
        new Get(
            uriTemplate: '/sales/carts/{id}',
            provider: ShoppingCartProvider::class,
            policy: 'view',
            middleware: 'auth:sanctum',
        ),
        new GetCollection(
            uriTemplate: '/sales/carts',
            provider: ShoppingCartProvider::class,
            policy: 'viewAny',
            middleware: 'auth:sanctum',
        ),
    ],
)]
final readonly class ShoppingCart
{
    /**
     * @param array<int, CartLine> $lines
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public string $token,
        public ?string $expiresAt,
        public array $lines,
        private ?string $ownerType,
        private ?int $ownerId,
    ) {}

    #[ApiProperty(readable: false, writable: false)]
    public function isOwnedBy(Authenticatable $user): bool
    {
        return $user instanceof Model
            && $this->ownerType === $user->getMorphClass()
            && $this->ownerId === $user->getAuthIdentifier();
    }
}
