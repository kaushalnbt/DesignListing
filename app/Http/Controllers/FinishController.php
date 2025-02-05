<?php

namespace App\Http\Controllers;

use App\Models\Finish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinishController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index(Request $request)
    {
        try {
            $query = Finish::query();

            if ($request->has('product_category_id')) {
                $query->whereHas('productCategories', function ($q) use ($request) {
                    $q->where('product_category_id', $request->input('product_category_id'));
                });
            }

            $finishes = $query->get();
            return $this->apiResponse->sendResponse(200, "Finishes fetched successfully!", $finishes);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:finishes,name',
        ]);

        DB::beginTransaction();
        try {
            $finish = Finish::create($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(201, "Finish created successfully!", $finish);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function show(Finish $finish)
    {
        try {
            return $this->apiResponse->sendResponse(200, "Finish fetched successfully!", $finish);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function update(Request $request, Finish $finish)
    {
        $request->validate([
            'name' => 'required|string|unique:finishes,name,' . $finish->id,
        ]);

        DB::beginTransaction();
        try {
            $finish->update($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Finish updated successfully!", $finish);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function destroy(Finish $finish)
    {
        DB::beginTransaction();
        try {
            $finish->delete();
            DB::commit();
            return $this->apiResponse->sendResponse(204, "Finish deleted successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }
}
