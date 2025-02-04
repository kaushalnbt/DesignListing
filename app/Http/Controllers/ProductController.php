<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        try {
            $products = Product::with(['category', 'sizes', 'finishes'])->get();
            return $this->apiResponse->sendResponse(200, "Products fetched successfully!", $products);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while fetching products.", null);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:products,name',
            'category_id' => 'required|exists:product_categories,id',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(201, "Product created successfully!", $product);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while creating the product.", null);
        }
    }

    public function show(Product $product)
    {
        try {
            $product->load(['category', 'sizes', 'finishes']);
            return $this->apiResponse->sendResponse(200, "Product fetched successfully!", $product);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while fetching the product.", null);
        }
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:product_categories,id',
        ]);

        DB::beginTransaction();
        try {
            $product->update($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Product updated successfully!", $product);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while updating the product.", null);
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            $product->delete();
            DB::commit();
            return $this->apiResponse->sendResponse(204, "Product deleted successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while deleting the product.", null);
        }
    }

    public function import(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = json_decode($request->file('file')->get());
            foreach ($data as $item) {
                // Process each item and store in the DB
            }
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Products imported successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while importing products.", null);
        }
    }

    public function export()
    {
        try {
            $products = Product::all();
            $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
            $csv->insertOne(['Product ID', 'Name', 'Category', 'Size', 'Finish']);
            foreach ($products as $product) {
                $csv->insertOne([$product->id, $product->name, $product->category->name, $product->size->name, $product->finish->name]);
            }

            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="products.csv"',
            ]);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while exporting products.", null);
        }
    }
}
