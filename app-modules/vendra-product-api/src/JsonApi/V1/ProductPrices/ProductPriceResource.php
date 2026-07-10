<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\JsonApi\V1\ProductPrices;

use Cknow\Money\Money;
use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraProduct\Models\ProductPrice;

/**
 * @mixin ProductPrice
 */
final class ProductPriceResource extends JsonApiResource
{
    /**
     * @return iterable<string, mixed>
     */
    public function attributes($request): iterable
    {
        $price = $this->getRawOriginal('price');

        return [
            'currency_code' => $this->currency_code,
            'price'         => is_numeric($price) ? (int) $price : 0,
            'money'         => $this->money(),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }

    /**
     * @return iterable<int, mixed>
     */
    public function relationships($request): iterable
    {
        return [
            $this->relation('product'),
            $this->relation('productCategory'),
        ];
    }

    /**
     * @return array{amount: int, currency: string, formatted: string}
     */
    private function money(): array
    {
        /** @var Money $money */
        $money = $this->price;

        return [
            'amount'    => (int) $money->getAmount(),
            'currency'  => $money->getCurrency()->getCode(),
            'formatted' => (string) $money,
        ];
    }
}
