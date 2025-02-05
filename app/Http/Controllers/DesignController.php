<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesignController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index(Request $request)
    {
        try {
            $query = Design::query();

            if ($request->has('product_category_id')) {
                $query->whereHas('productCategories', function ($q) use ($request) {
                    $q->where('product_category_id', $request->input('product_category_id'));
                });
            }

            if ($request->has('size_id')) {
                $query->whereHas('sizes', function ($q) use ($request) {
                    $q->where('size_id', $request->input('size_id'));
                });
            }

            if ($request->has('finish_id')) {
                $query->whereHas('finishes', function ($q) use ($request) {
                    $q->where('finish_id', $request->input('finish_id'));
                });
            }

            $designs = $query->get();
            return $this->apiResponse->sendResponse(200, "Designs fetched successfully!", $designs);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:designs,name',
        ]);

        DB::beginTransaction();
        try {
            $design = Design::create($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(201, "Design created successfully!", $design);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function show(Design $design)
    {
        try {
            return $this->apiResponse->sendResponse(200, "Design fetched successfully!", $design);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function update(Request $request, Design $design)
    {
        $request->validate([
            'name' => 'required|string|unique:designs,name,' . $design->id,
        ]);

        DB::beginTransaction();
        try {
            $design->update($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Design updated successfully!", $design);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function destroy(Design $design)
    {
        DB::beginTransaction();
        try {
            $design->delete();
            DB::commit();
            return $this->apiResponse->sendResponse(204, "Design deleted successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }
}
