<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\EloquentResourceProvider;
use Misaf\VendraProduct\Models\Product;
use Misaf\VendraProduct\Models\ProductCategory;
use Misaf\VendraProduct\Models\ProductPrice;
use Misaf\VendraProductApi\ApiResource\CatalogGroup;
use Misaf\VendraProductApi\ApiResource\CatalogItem;
use Misaf\VendraProductApi\ApiResource\PriceQuote;
use Misaf\VendraSupport\Capabilities\AttributeIntegration;

/**
 * @extends EloquentResourceProvider<Model, CatalogGroup|CatalogItem|PriceQuote>
 */
final class ProductResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        return match ($operation->getClass()) {
            CatalogGroup::class => ProductCategory::query()
                ->with('products:id,product_category_id,name')
                ->where('active', true),
            PriceQuote::class => ProductPrice::query()->with('product:id,name'),
            default           => Product::query()->with([
                'productCategory:id,name',
                'productPrices:id,product_id,currency_code,price',
                'multimedia',
                ...$this->attributeRelations(),
            ])->whereHas('productCategory', fn(Builder $query): Builder => $query->where('active', true)),
        };
    }

    protected function toResource(Model $model, Operation $operation): CatalogGroup|CatalogItem|PriceQuote
    {
        if ($model instanceof ProductCategory) {
            return new CatalogGroup(
                id: $model->id,
                title: $model->getTranslations('name'),
                slugs: $model->getTranslations('slug'),
                items: $model->products
                    ->map(fn(Product $product): ResourceReference => new ResourceReference($product->id, 'Product', $product->getTranslation('name', app()->getLocale())))
                    ->all(),
            );
        }

        if ($model instanceof ProductPrice) {
            return new PriceQuote(
                id: $model->id,
                minorAmount: (int) $model->price->getAmount(),
                currency: $model->currency_code,
                formatted: $model->formattedPrice(),
                item: new ResourceReference($model->product->id, 'Product', $model->product->getTranslation('name', app()->getLocale())),
            );
        }

        /** @var Product $model */
        return new CatalogItem(
            id: $model->id,
            title: $model->getTranslations('name'),
            description: $model->getTranslations('description'),
            quantity: $model->quantity,
            inStock: $model->in_stock,
            group: new ResourceReference($model->productCategory->id, 'ProductCategory', $model->productCategory->getTranslation('name', app()->getLocale())),
            prices: $model->productPrices
                ->map(fn(ProductPrice $price): ResourceReference => new ResourceReference($price->id, 'ProductPrice', $price->formattedPrice()))
                ->all(),
            multimedia: $model->multimedia
                ->map(fn(Model $media): ResourceReference => new ResourceReference($media->getKey(), 'Multimedia', $media->getAttribute('name')))
                ->all(),
            options: $this->attributeReferences($model),
        );
    }

    /**
     * @return array<int, string>
     */
    private function attributeRelations(): array
    {
        return AttributeIntegration::isAvailable() ? ['selectedAttributeValues'] : [];
    }

    /**
     * @return array<int, ResourceReference>
     */
    private function attributeReferences(Product $product): array
    {
        if ( ! AttributeIntegration::isAvailable()) {
            return [];
        }

        return $product->selectedAttributeValues
            ->map(fn(Model $value): ResourceReference => new ResourceReference($value->getKey(), 'AttributeValue', $value->getAttribute('value')))
            ->all();
    }
}
