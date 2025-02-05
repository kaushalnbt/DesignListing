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

    public function index(Request $request)
    {
        try {
            $query = Product::with(['category', 'sizes', 'finishes', 'designs']);

            if ($request->has('product_category_id')) {
                $query->where('category_id', $request->input('product_category_id'));
            }

            if ($request->has('finish_id')) {
                $query->whereHas('finishes', function ($q) use ($request) {
                    $q->where('finish_id', $request->input('finish_id'));
                });
            }

            if ($request->has('size_id')) {
                $query->whereHas('sizes', function ($q) use ($request) {
                    $q->where('size_id', $request->input('size_id'));
                });
            }

            $products = $query->get();
            return $this->apiResponse->sendResponse(200, "Products fetched successfully!", $products);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
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
            $product->designs()->sync($request->input('design_ids', []));
            DB::commit();
            return $this->apiResponse->sendResponse(201, "Product created successfully!", $product);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function show(Product $product)
    {
        try {
            $product->load(['category', 'sizes', 'finishes', 'designs']);
            return $this->apiResponse->sendResponse(200, "Product fetched successfully!", $product);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
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
            $product->designs()->sync($request->input('design_ids', []));
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Product updated successfully!", $product);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
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
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function import(Request $request)
    {
        DB::beginTransaction();
        try {
            $file = $request->file('file');
    
            if (!$file) {
                return $this->apiResponse->sendResponse(400, "No file uploaded!", null);
            }
    
            $jsonData = json_decode(file_get_contents($file), true);
    
            if (!$jsonData) {
                return $this->apiResponse->sendResponse(400, "Invalid JSON format!", null);
            }
    
            foreach ($jsonData as $item) {
                // Ensure category exists or create new
                $category = ProductCategory::firstOrCreate(['name' => $item['category']]);
    
                // Create product
                $product = Product::create([
                    'name' => $item['name'],
                    'category_id' => $category->id,
                ]);
    
                // Attach sizes (if provided)
                if (!empty($item['sizes'])) {
                    $sizeIds = Size::whereIn('name', $item['sizes'])->pluck('id')->toArray();
                    $product->sizes()->attach($sizeIds);
                }
    
                // Attach finishes (if provided)
                if (!empty($item['finishes'])) {
                    $finishIds = Finish::whereIn('name', $item['finishes'])->pluck('id')->toArray();
                    $product->finishes()->attach($finishIds);
                }

                // Attach designs (if provided)
                if (!empty($item['designs'])) {
                    $designIds = Design::whereIn('name', $item['designs'])->pluck('id')->toArray();
                    $product->designs()->attach($designIds);
                }
            }
    
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Products imported successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "Error importing products: " . $e->getMessage(), null);
        }
    }

    public function export()
    {
        try {
            $products = Product::with(['category', 'sizes', 'finishes', 'designs'])->get();
    
            $csv = Writer::createFromFileObject(new \SplTempFileObject());
            $csv->insertOne(['Product ID', 'Name', 'Category', 'Sizes', 'Finishes', 'Designs']);
    
            foreach ($products as $product) {
                $sizeNames = $product->sizes->pluck('name')->implode(', ');
                $finishNames = $product->finishes->pluck('name')->implode(', ');
                $designNames = $product->designs->pluck('name')->implode(', ');
    
                $csv->insertOne([
                    $product->id,
                    $product->name,
                    $product->category->name ?? 'N/A',
                    $sizeNames ?: 'N/A',
                    $finishNames ?: 'N/A',
                    $designNames ?: 'N/A',
                ]);
            }
    
            return response((string) $csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="products.csv"',
            ]);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "Error exporting products: " . $e->getMessage(), null);
        }
    }
}
