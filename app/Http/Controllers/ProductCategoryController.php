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

    public function index(Request $request)
    {
        try {
            $query = ProductCategory::query();

            if ($request->has('parent_id')) {
                $query->where('parent_id', $request->input('parent_id'));
            }

            $categories = $query->get();
            return $this->apiResponse->sendResponse(200, "Product categories fetched successfully!", $categories);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
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
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function show(ProductCategory $productCategory)
    {
        try {
            return $this->apiResponse->sendResponse(200, "Product category fetched successfully!", $productCategory);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
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
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
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
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }
}
