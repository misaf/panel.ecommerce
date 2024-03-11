<?php

declare(strict_types=1);

namespace App\Console\Commands\ConvertData\Retriever;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

final class ProductDataRetriever
{
    public function getOldProductById(int $productId): mixed
    {
        return DB::connection('mysql_old')
            ->table('products')
            ->whereNull('deleted_at')
            ->find($productId);
    }

    public function getOldProductCategories(): LazyCollection
    {
        return DB::connection('mysql_old')
            ->table('product_categories')
            ->whereNull('deleted_at')
            ->lazyById(100);
    }

    public function getOldProductCategoryById(int $id): mixed
    {
        return DB::connection('mysql_old')
            ->table('product_categories')
            ->whereNull('deleted_at')
            ->find($id);
    }

    public function getOldProductImages(): LazyCollection
    {
        return DB::connection('mysql_old')
            ->table('product_images')
            ->select('product_images.image as image', 'products.*')
            ->join('products', 'products.id', '=', 'product_images.product_id')
            ->whereNull('products.deleted_at')
            ->whereNull('product_images.deleted_at')
            ->oldest('product_images.id')
            ->lazy();
    }

    public function getOldProductPriceById(int $id): mixed
    {
        return DB::connection('mysql_old')
            ->table('product_prices')
            ->whereNull('deleted_at')
            ->find($id);
    }

    public function getOldProductPriceByProductId(int $productId): mixed
    {
        return DB::connection('mysql_old')
            ->table('product_prices')
            ->where('product_id', $productId)
            ->whereNull('deleted_at')
            ->lazyById(100);
    }

    public function getOldProductPrices(): LazyCollection
    {
        return DB::connection('mysql_old')
            ->table('product_prices')
            ->select('product_prices.*', 'products.*')
            ->join('products', 'products.id', '=', 'product_prices.product_id')
            ->whereNull('products.deleted_at')
            ->whereNull('product_prices.deleted_at')
            ->oldest('product_prices.id')
            ->lazy();
    }

    public function getOldProducts(): LazyCollection
    {
        return DB::connection('mysql_old')
            ->table('products')
            ->whereNull('deleted_at')
            ->lazyById(100);
    }
}
