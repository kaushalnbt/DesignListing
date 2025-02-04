<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        $products = Product::with(['category', 'sizes', 'finishes'])->get();
        return $this->apiResponse->sendResponse(200, "Products fetched successfully!", $products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:products,name',
            'category_id' => 'required|exists:product_categories,id',
        ]);

        $product = Product::create($request->all());

        return $this->apiResponse->sendResponse(201, "Product created successfully!", $product);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'sizes', 'finishes']);
        return $this->apiResponse->sendResponse(200, "Product fetched successfully!", $product);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:product_categories,id',
        ]);

        $product->update($request->all());

        return $this->apiResponse->sendResponse(200, "Product updated successfully!", $product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return $this->apiResponse->sendResponse(204, "Product deleted successfully!", null);
    }
}
