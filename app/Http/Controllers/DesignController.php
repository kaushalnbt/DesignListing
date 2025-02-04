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

    public function index()
    {
        try {
            $designs = Design::all();
            return $this->apiResponse->sendResponse(200, "Designs fetched successfully!", $designs);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while fetching designs.", null);
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
            return $this->apiResponse->sendResponse(500, "An error occurred while creating the design.", null);
        }
    }

    public function show(Design $design)
    {
        try {
            return $this->apiResponse->sendResponse(200, "Design fetched successfully!", $design);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while fetching the design.", null);
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
            return $this->apiResponse->sendResponse(500, "An error occurred while updating the design.", null);
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
            return $this->apiResponse->sendResponse(500, "An error occurred while deleting the design.", null);
        }
    }
}
