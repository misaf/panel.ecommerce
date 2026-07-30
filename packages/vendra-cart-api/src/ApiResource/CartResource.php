<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\McpToolCollection;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\McpCollectionInput;
use Misaf\VendraApi\ApiResource\McpResourceIdentifierInput;
use Misaf\VendraCart\Models\Cart;
use Misaf\VendraCartApi\State\CartResourceProvider;

#[ApiResource(
    shortName: 'Cart',
    stateOptions: new Options(modelClass: Cart::class, handleLinks: CartResourceProvider::class),
    mcp: [
        'get_cart' => new McpTool(
            description: 'Get one cart owned by the authenticated user.',
            input: McpResourceIdentifierInput::class,
            provider: CartResourceProvider::class,
            policy: 'view',
        ),
        'list_carts' => new McpToolCollection(
            description: 'List carts owned by the authenticated user.',
            input: McpCollectionInput::class,
            provider: CartResourceProvider::class,
            policy: 'viewAny',
        ),
    ],
)]
#[Get(
    uriTemplate: '/sales/carts/{id}',
    provider: CartResourceProvider::class,
    policy: 'view',
    middleware: 'auth:sanctum',
)]
#[GetCollection(
    uriTemplate: '/sales/carts',
    provider: CartResourceProvider::class,
    policy: 'viewAny',
    middleware: 'auth:sanctum',
)]
final readonly class CartResource
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
