<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        $sizes = Size::all();
        return $this->apiResponse->sendResponse(200, "Sizes fetched successfully!", $sizes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:sizes,name',
        ]);

        $size = Size::create($request->all());

        return $this->apiResponse->sendResponse(201, "Size created successfully!", $size);
    }

    public function show(Size $size)
    {
        return $this->apiResponse->sendResponse(200, "Size fetched successfully!", $size);
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|unique:sizes,name,' . $size->id,
        ]);

        $size->update($request->all());

        return $this->apiResponse->sendResponse(200, "Size updated successfully!", $size);
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return $this->apiResponse->sendResponse(204, "Size deleted successfully!", null);
    }
}