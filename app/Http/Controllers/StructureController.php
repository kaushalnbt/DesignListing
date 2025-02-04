<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        $structures = Structure::all();
        return $this->apiResponse->sendResponse(200, "Structures fetched successfully!", $structures);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:structures,name',
        ]);

        $structure = Structure::create($request->all());

        return $this->apiResponse->sendResponse(201, "Structure created successfully!", $structure);
    }

    public function show(Structure $structure)
    {
        return $this->apiResponse->sendResponse(200, "Structure fetched successfully!", $structure);
    }

    public function update(Request $request, Structure $structure)
    {
        $request->validate([
            'name' => 'required|string|unique:structures,name,' . $structure->id,
        ]);

        $structure->update($request->all());

        return $this->apiResponse->sendResponse(200, "Structure updated successfully!", $structure);
    }

    public function destroy(Structure $structure)
    {
        $structure->delete();
        return $this->apiResponse->sendResponse(204, "Structure deleted successfully!", null);
    }
}
