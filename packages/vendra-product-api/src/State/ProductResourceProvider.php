<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\EloquentResourceProvider;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use Misaf\VendraProduct\Models\Product;
use Misaf\VendraProduct\Models\ProductCategory;
use Misaf\VendraProduct\Models\ProductPrice;
use Misaf\VendraProductApi\ApiResource\ProductCategoryResource;
use Misaf\VendraProductApi\ApiResource\ProductPriceResource;
use Misaf\VendraProductApi\ApiResource\ProductResource;
use Misaf\VendraSupport\Capabilities\AttributeIntegration;

/**
 * @extends EloquentResourceProvider<Model, ProductCategoryResource|ProductResource|ProductPriceResource>
 */
final class ProductResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        return match ($operation->getClass()) {
            ProductCategoryResource::class => ProductCategory::query()
                ->with([
                    'products:id,product_category_id,name',
                    'multimedia',
                ])
                ->where('active', true),
            ProductPriceResource::class => ProductPrice::query()->with('product:id,name'),
            default                     => $this->productQuery(),
        };
    }

    /**
     * @return Builder<Product>
     */
    private function productQuery(): Builder
    {
        return Product::query()->with([
            'productCategory:id,name,slug,description,position,active,created_at,updated_at',
            'productPrices:id,product_id,currency_code,price',
            'latestProductPrice',
            'multimedia',
            ...$this->attributeRelations(),
        ])->whereHas('productCategory', fn(Builder $query): Builder => $query->where('active', true));
    }

    protected function toResource(Model $model, Operation $operation): ProductCategoryResource|ProductResource|ProductPriceResource
    {
        if ($model instanceof ProductCategory) {
            return $this->toCategoryResource($model);
        }

        if ($model instanceof ProductPrice) {
            return new ProductPriceResource(
                id: $model->id,
                minorAmount: (int) $model->price->getAmount(),
                amount: ProductPrice::toMajorUnits($model->currency_code, (int) $model->price->getAmount()),
                currency: $model->currency_code,
                formatted: $model->formattedPrice(),
                product: new ResourceReference($model->product->id, 'Product', $model->product->getTranslation('name', app()->getLocale())),
            );
        }

        /** @var Product $model */
        return new ProductResource(
            id: $model->id,
            title: $model->getTranslations('name'),
            slugs: $model->getTranslations('slug'),
            description: $model->getTranslations('description'),
            token: $model->token,
            quantity: $model->quantity,
            inStock: $model->in_stock,
            productCategory: new ResourceReference(
                $model->productCategory->id,
                'ProductCategory',
                $model->productCategory->getTranslation('name', app()->getLocale()),
            ),
            productPrices: $model->productPrices
                ->map(fn(ProductPrice $price): ProductPriceResource => $this->toPriceResource($price, $model))
                ->all(),
            latestProductPrice: $model->latestProductPrice instanceof ProductPrice
                ? $this->toPriceResource($model->latestProductPrice, $model)
                : null,
            multimedia: $model->multimedia
                ->map(fn(Model $media): MultimediaResource => $this->toMultimediaResource($media))
                ->all(),
            options: $this->attributeReferences($model),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }

    private function toCategoryResource(ProductCategory $category): ProductCategoryResource
    {
        return new ProductCategoryResource(
            id: $category->id,
            title: $category->getTranslations('name'),
            slugs: $category->getTranslations('slug'),
            description: $category->getTranslations('description'),
            position: $category->position,
            active: $category->active,
            products: $category->products
                ->map(fn(Product $product): ResourceReference => new ResourceReference($product->id, 'Product', $product->getTranslation('name', app()->getLocale())))
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

    private function toPriceResource(ProductPrice $price, Product $product): ProductPriceResource
    {
        return new ProductPriceResource(
            id: $price->id,
            minorAmount: (int) $price->price->getAmount(),
            amount: ProductPrice::toMajorUnits($price->currency_code, (int) $price->price->getAmount()),
            currency: $price->currency_code,
            formatted: $price->formattedPrice(),
            product: new ResourceReference($product->id, 'Product', $product->getTranslation('name', app()->getLocale())),
        );
    }

    private function toMultimediaResource(Model $media): MultimediaResource
    {
        return MultimediaResourceFactory::make($media);
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
