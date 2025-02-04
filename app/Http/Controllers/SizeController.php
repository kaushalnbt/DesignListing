<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SizeController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        try {
            $sizes = Size::all();
            return $this->apiResponse->sendResponse(200, "Sizes fetched successfully!", $sizes);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:sizes,name',
        ]);

        DB::beginTransaction();
        try {
            $size = Size::create($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(201, "Size created successfully!", $size);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function show(Size $size)
    {
        try {
            return $this->apiResponse->sendResponse(200, "Size fetched successfully!", $size);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|unique:sizes,name,' . $size->id,
        ]);

        DB::beginTransaction();
        try {
            $size->update($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Size updated successfully!", $size);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function destroy(Size $size)
    {
        DB::beginTransaction();
        try {
            $size->delete();
            DB::commit();
            return $this->apiResponse->sendResponse(204, "Size deleted successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }
}