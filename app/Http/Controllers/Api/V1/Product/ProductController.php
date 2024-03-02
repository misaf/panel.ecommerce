<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product\Product;
use Illuminate\Http\Request;

final class ProductController extends Controller
{
    public function destroy(string $id)
    {
        return ProductResource::collection(Product::destroy($id));
    }

    public function index()
    {
        return ProductResource::collection(Product::with('productCategory')->get());
    }

    public function show(string $id)
    {
        return ProductResource::collection(Product::find($id));
    }

    public function store(Request $request)
    {
        return new ProductResource(Product::create($request));
    }

    public function update(Request $request, string $id)
    {
        return ProductResource::collection(Product::find($id)->update($request));
    }
}
