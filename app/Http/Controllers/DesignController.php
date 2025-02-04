<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        $designs = Design::all();
        return $this->apiResponse->sendResponse(200, "Designs fetched successfully!", $designs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:designs,name',
        ]);

        $design = Design::create($request->all());

        return $this->apiResponse->sendResponse(201, "Design created successfully!", $design);
    }

    public function show(Design $design)
    {
        return $this->apiResponse->sendResponse(200, "Design fetched successfully!", $design);
    }

    public function update(Request $request, Design $design)
    {
        $request->validate([
            'name' => 'required|string|unique:designs,name,' . $design->id,
        ]);

        $design->update($request->all());

        return $this->apiResponse->sendResponse(200, "Design updated successfully!", $design);
    }

    public function destroy(Design $design)
    {
        $design->delete();
        return $this->apiResponse->sendResponse(204, "Design deleted successfully!", null);
    }
}
