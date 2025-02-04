<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        $categories = ProductCategory::all();
        return $this->apiResponse->sendResponse(200, "Product categories fetched successfully!", $categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:product_categories,name',
        ]);

        $category = ProductCategory::create($request->all());

        return $this->apiResponse->sendResponse(201, "Product category created successfully!", $category);
    }

    public function show(ProductCategory $productCategory)
    {
        return $this->apiResponse->sendResponse(200, "Product category fetched successfully!", $productCategory);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|unique:product_categories,name,' . $productCategory->id,
        ]);

        $productCategory->update($request->all());

        return $this->apiResponse->sendResponse(200, "Product category updated successfully!", $productCategory);
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return $this->apiResponse->sendResponse(204, "Product category deleted successfully!", null);
    }
}
