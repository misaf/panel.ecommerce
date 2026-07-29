<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\State\Concerns;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use Misaf\VendraProduct\Models\Product;
use Misaf\VendraProduct\Models\ProductCategory;
use Misaf\VendraProduct\Models\ProductPrice;
use Misaf\VendraProductApi\ApiResource\ProductCategoryResource;
use Misaf\VendraProductApi\ApiResource\ProductPriceResource;
use Misaf\VendraProductApi\ApiResource\ProductResource;
use Misaf\VendraSupport\Capabilities\AttributeIntegration;
use UnexpectedValueException;

/**
 * Shared mapping between catalog Eloquent models and their API resources.
 *
 * Catalog resources are compositionally nested — a product embeds its prices
 * and a category reference, a category embeds product references — so the
 * per-resource providers share one mapper rather than duplicating logic.
 */
trait MapsCatalogResources
{
    use NormalizesResourceValues;

    protected function toProductResource(Product $product): ProductResource
    {
        return new ProductResource(
            id: $product->id,
            title: $this->normalizeTranslations($product->getTranslations('name')),
            slugs: $this->normalizeTranslations($product->getTranslations('slug')),
            description: $this->normalizeTranslations($product->getTranslations('description')),
            token: $product->token,
            quantity: $product->quantity,
            inStock: $product->in_stock,
            productCategory: $this->categoryReference($product->productCategory),
            productPrices: $product->productPrices
                ->map(fn(ProductPrice $price): ProductPriceResource => $this->toPriceResource($price, $product))
                ->all(),
            latestProductPrice: $product->latestProductPrice instanceof ProductPrice
                ? $this->toPriceResource($product->latestProductPrice, $product)
                : null,
            multimedia: $product->multimedia
                ->map(fn(Model $media): MultimediaResource => $this->toMultimediaResource($media))
                ->all(),
            options: $this->attributeReferences($product),
            createdAt: $product->created_at->toAtomString(),
            updatedAt: $product->updated_at->toAtomString(),
        );
    }

    protected function toCategoryResource(ProductCategory $category): ProductCategoryResource
    {
        return new ProductCategoryResource(
            id: $category->id,
            title: $this->normalizeTranslations($category->getTranslations('name')),
            slugs: $this->normalizeTranslations($category->getTranslations('slug')),
            description: $this->normalizeTranslations($category->getTranslations('description')),
            position: $category->position,
            active: $category->active,
            products: $category->products
                ->map(fn(Product $product): ResourceReference => $this->productReference($product))
                ->all(),
            multimedia: $category->relationLoaded('multimedia')
                ? $category->multimedia
                    ->map(fn(Model $media): MultimediaResource => $this->toMultimediaResource($media))
                    ->all()
                : [],
            createdAt: $category->created_at->toAtomString(),
            updatedAt: $category->updated_at->toAtomString(),
        );
    }

    protected function toPriceResource(ProductPrice $price, ?Product $product = null): ProductPriceResource
    {
        $product ??= $price->product;

        return new ProductPriceResource(
            id: $price->id,
            minorAmount: (int) $price->price->getAmount(),
            amount: ProductPrice::toMajorUnits($price->currency_code, (int) $price->price->getAmount()),
            currency: $price->currency_code,
            formatted: $price->formattedPrice(),
            product: $this->productReference($product),
        );
    }

    protected function toMultimediaResource(Model $media): MultimediaResource
    {
        return MultimediaResourceFactory::make($media);
    }

    protected function productReference(?Product $product): ResourceReference
    {
        if ( ! $product instanceof Product) {
            throw new UnexpectedValueException('A product price must belong to a product.');
        }

        $productName = $product->getTranslation('name', app()->getLocale());

        return new ResourceReference(
            $product->id,
            'Product',
            is_string($productName) ? $productName : null,
        );
    }

    protected function categoryReference(?ProductCategory $category): ResourceReference
    {
        if ( ! $category instanceof ProductCategory) {
            throw new UnexpectedValueException('A product must belong to a category.');
        }

        $categoryName = $category->getTranslation('name', app()->getLocale());

        return new ResourceReference(
            $category->id,
            'ProductCategory',
            is_string($categoryName) ? $categoryName : null,
        );
    }

    /**
     * @return array<int, string>
     */
    protected function attributeRelations(): array
    {
        return AttributeIntegration::isAvailable() ? ['selectedAttributeValues'] : [];
    }

    /**
     * @return array<int, ResourceReference>
     */
    protected function attributeReferences(Product $product): array
    {
        if ( ! AttributeIntegration::isAvailable()) {
            return [];
        }

        return $product->selectedAttributeValues
            ->map(function (Model $value): ResourceReference {
                $identifier = $value->getKey();
                $label = $value->getAttribute('value');

                if ( ! is_int($identifier) && ! is_string($identifier)) {
                    throw new UnexpectedValueException('Attribute value identifiers must be integers or strings.');
                }

                return new ResourceReference(
                    $identifier,
                    'AttributeValue',
                    is_string($label) ? $label : null,
                );
            })
            ->all();
    }
}
