<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCategoryResource;
use App\Models\Product\ProductCategory;
use Illuminate\Http\Request;

final class ProductCategoryController extends Controller
{
    public function destroy(string $id)
    {
        return ProductCategoryResource::collection(ProductCategory::destroy($id));
    }

    public function index()
    {
        return ProductCategoryResource::collection(ProductCategory::all());
    }

    public function show(string $id)
    {
        return ProductCategoryResource::collection(ProductCategory::find($id));
    }

    public function store(Request $request)
    {
        return new ProductCategoryResource(ProductCategory::create($request));
    }

    public function update(Request $request, string $id)
    {
        return ProductCategoryResource::collection(ProductCategory::find($id)->update($request));
    }
}
