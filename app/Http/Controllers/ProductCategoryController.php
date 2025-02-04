<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        try {
            $categories = ProductCategory::all();
            return $this->apiResponse->sendResponse(200, "Product categories fetched successfully!", $categories);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while fetching product categories.", null);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:product_categories,name',
        ]);

        DB::beginTransaction();
        try {
            $category = ProductCategory::create($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(201, "Product category created successfully!", $category);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while creating the product category.", null);
        }
    }

    public function show(ProductCategory $productCategory)
    {
        try {
            return $this->apiResponse->sendResponse(200, "Product category fetched successfully!", $productCategory);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while fetching the product category.", null);
        }
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|unique:product_categories,name,' . $productCategory->id,
        ]);

        DB::beginTransaction();
        try {
            $productCategory->update($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Product category updated successfully!", $productCategory);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while updating the product category.", null);
        }
    }

    public function destroy(ProductCategory $productCategory)
    {
        DB::beginTransaction();
        try {
            $productCategory->delete();
            DB::commit();
            return $this->apiResponse->sendResponse(204, "Product category deleted successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while deleting the product category.", null);
        }
    }
}
