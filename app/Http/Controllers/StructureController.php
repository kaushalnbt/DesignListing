<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StructureController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        try {
            $structures = Structure::all();
            return $this->apiResponse->sendResponse(200, "Structures fetched successfully!", $structures);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:structures,name',
        ]);

        DB::beginTransaction();
        try {
            $structure = Structure::create($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(201, "Structure created successfully!", $structure);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function show(Structure $structure)
    {
        try {
            return $this->apiResponse->sendResponse(200, "Structure fetched successfully!", $structure);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function update(Request $request, Structure $structure)
    {
        $request->validate([
            'name' => 'required|string|unique:structures,name,' . $structure->id,
        ]);

        DB::beginTransaction();
        try {
            $structure->update($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Structure updated successfully!", $structure);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function destroy(Structure $structure)
    {
        DB::beginTransaction();
        try {
            $structure->delete();
            DB::commit();
            return $this->apiResponse->sendResponse(204, "Structure deleted successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }
}
